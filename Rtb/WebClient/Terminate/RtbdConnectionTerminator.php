<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

/**
 * Send terminate connection command to rtbd
 *
 * SAM-3955: Apply ratchetphp/Pawl for calling rtbd commands from web
 *
 * Find user by ip blocking function at User Edit page of admin side in “User additional info“ section.
 * When you click “Block”, \Sam\View\Admin\Form\UserEditForm::handleBtnBlockClick() action handler destroys user’s session
 * and calls \Sam\Rtb\WebClient\Terminate\RtbdConnectionTerminator::terminate(). It connects to rtb daemon process
 * through web-sockets using Ratchet’s client and sends Terminate Connection request to it.
 * Search by Constants\Rtb::CMD_TERMINATE_CONNECTION_Q constant, request is handled
 * by \httpdServerClient::terminateBlockUser(). It finds connected user console by user id and ip,
 * produces Constants\Rtb::CMD_TERMINATE_CONNECTION_S response and closes socket connection.
 * Search it in JS code. Currently, this response is handled at Live/Hybrid Bidder consoles, it causes alert message
 * and page reload. After page reload user becomes unauthorized, hence Viewer console is opened for him.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 19, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\WebClient\Terminate;

use Exception;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Session\RtbSessionManagerCreateTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class RtbdConnectionTerminator
 * @package Sam\Rtb\WebClient\Terminate
 */
class RtbdConnectionTerminator extends CustomizableClass
{
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbSessionManagerCreateTrait;
    use UserAwareTrait;

    protected ?string $ipAddress = null;
    /**
     * @var int[]
     */
    protected array $terminatedAuctionIds = [];
    protected LoopInterface $loop;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Send terminate connection command to rtbd server
     */
    public function terminate(): void
    {
        $this->terminatedAuctionIds = [];
        $this->loop = Factory::create();
        $connector = new \Ratchet\Client\Connector($this->loop, new \React\Socket\Connector($this->loop));

        foreach ($this->detectRtbdUris() as $rtbdUri) {
            log_debug($this->decorateMessage("Trying to connect {$rtbdUri} for notifying rtb daemon about user connection terminating"));
            $this->terminateRtbd($connector($rtbdUri));
        }

        $this->loop->run();
    }

    /**
     * @param \React\Promise\PromiseInterface $connectPromise
     */
    protected function terminateRtbd(\React\Promise\PromiseInterface $connectPromise): void
    {
        $connectPromise->then(
            function (WebSocket $rtbdSocket) {
                $host = $rtbdSocket->request->getHeader('Host')[0] ?? null;
                log_debug($this->decorateMessage("Successfully connected to Rtbd {$host}"));

                $rtbdSocket->on(
                    'message',
                    function ($responseJson) use ($rtbdSocket) {
                        log_debug($this->decorateMessage("We got response from Rtbd {$responseJson}"));
                        $response = json_decode($responseJson, true);
                        if (!empty($response[Constants\Rtb::RES_DATA]['AuctionIds'])) {
                            $terminatedAuctionIds = ArrayCast::castInt($response[Constants\Rtb::RES_DATA]['AuctionIds'], Constants\Type::F_INT_POSITIVE);
                            $terminatedAuctionIds = array_unique($terminatedAuctionIds);
                            $this->terminatedAuctionIds = array_merge($this->terminatedAuctionIds, $terminatedAuctionIds);
                        }
                        $rtbdSocket->close();
                    }
                );

                $rtbdSocket->on(
                    'close',
                    function ($code = null, $reason = null) {
                        log_debug(
                            $this->decorateMessage(
                                "Rtbd connection closed with reason "
                                . composeLogData([$code => $reason])
                            )
                        );
                    }
                );

                $this->sendTerminateConnectionRequest($rtbdSocket);
            },
            function (Exception $exception) {
                log_error($this->decorateMessage("Failed connection to rtbd. " . $exception->getMessage()));
                $this->loop->stop();
            }
        );
    }

    /**
     * @return array
     */
    protected function detectRtbdUris(): array
    {
        if ($this->getRtbdPoolFeatureAvailabilityValidator()->isAvailable()) {
            $auctionIds = [];
            $rtbSessions = $this->createRtbSessionManager()->loadEntities($this->getUserId());
            foreach ($rtbSessions as $rtbSession) {
                $auctionIds[] = $rtbSession->AuctionId;
            }
            $auctionIds = array_unique($auctionIds);

            $uris = [];
            foreach ($auctionIds as $auctionId) {
                $uris[] = $this->getRtbGeneralHelper()->getRtbdUri(Constants\Rtb::UT_CLIENT, $auctionId);
            }
            $uris = array_unique(array_filter($uris));
        } else {
            $uris = [$this->getRtbGeneralHelper()->getRtbdUri(Constants\Rtb::UT_CLIENT)];
        }
        return $uris;
    }

    /**
     * @return int[] list of disconnected auction ids.
     */
    public function getTerminatedAuctionIds(): array
    {
        return $this->terminatedAuctionIds;
    }

    /**
     * Send connection termination command to RTBD
     * @param WebSocket $rtbdSocket
     */
    protected function sendTerminateConnectionRequest(WebSocket $rtbdSocket): void
    {
        $data = [
            Constants\Rtb::REQ_USER_ID => $this->getUserId(),
            Constants\Rtb::REQ_IP => $this->getIpAddress(),
        ];
        $request = [
            Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_TERMINATE_CONNECTION_Q,
            Constants\Rtb::REQ_DATA => $data,
        ];
        $this->send($request, $rtbdSocket);
    }

    /**
     * Add prefix and postfix with additional info to logged message
     * @param string $message
     * @return string
     */
    protected function decorateMessage(string $message): string
    {
        $message = "Rtbd request \"Terminate Connection\": {$message} {$this->getInfo()}";
        return $message;
    }

    /**
     * Additional info for logged message
     * @return string
     */
    protected function getInfo(): string
    {
        $info = composeSuffix(['u' => $this->getUserId()]);
        return $info;
    }

    /**
     * Send request to RTBD server
     * @param array $request
     * @param WebSocket $rtbdSocket
     */
    protected function send(array $request, WebSocket $rtbdSocket): void
    {
        $requestJson = json_encode($request);
        log_debug(composeLogData(["Send to rtbd{$this->getInfo()}" => $requestJson]));
        $eol = "\r\n";  // PHP_EOL
        $rtbdSocket->send($requestJson . $eol . $eol);
    }

    /**
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     * @return static
     */
    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = trim($ipAddress);
        return $this;
    }
}
