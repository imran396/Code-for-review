<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

/**
 * Initiates request to rtbd for calling LinkRtbd command
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Link;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AuctionRtbdLinker
 * @package
 */
class AuctionRtbdLinker extends CustomizableClass
{
    use AuctionAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;

    // TODO

    use RtbGeneralHelperAwareTrait;
    use SystemAccountAwareTrait;

    // TODO

    protected ?string $rtbdUri = null;
    protected ?\Ratchet\Client\WebSocket $rtbd = null;
    protected ?string $sessionId = null;
    protected string $newRtbdName = '';
    protected string $oldRtbdName = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function link(): void
    {
        if (!$this->cfg()->get('core->rtb->client->password')) {
            log_error($this->decorateMessage("Error: core->rtb->client->password not defined"));
            return;
        }

        $rtbdUri = $this->getRtbdUri();
        log_debug($this->decorateMessage("Trying to connect {$rtbdUri} for calling command ..."));

        $loop = \React\EventLoop\Factory::create();
        $connector = new \Ratchet\Client\Connector($loop, new \React\Socket\Connector($loop));

        $connector($rtbdUri)->then(
            function (\Ratchet\Client\WebSocket $rtbd) {
                log_debug($this->decorateMessage("Successfully connected to Rtbd {$this->getRtbdUri()}"));
                $this->rtbd = $rtbd;
                $this->rtbd->on(
                    'message',
                    function ($responseJson) {
                        $responseJson = \Sam\Rtb\ViewerRepeater\ResponseManager::new()->sanitize($responseJson);
                        log_debug($this->decorateMessage("We got response from Rtbd {$responseJson}"));
                        $response = json_decode($responseJson, true);
                        if (isset($response[Constants\Rtb::RES_COMMAND])) {
                            if ($response[Constants\Rtb::RES_COMMAND] === Constants\Rtb::CMD_AUTH_S) {
                                $this->handleAuthResponse($response);
                            } elseif ($response[Constants\Rtb::RES_COMMAND] === Constants\Rtb::CMD_LINK_RTBD_S) {
                                $this->handleLinkResponse();
                            }
                        }
                    }
                );
                $this->rtbd->on(
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
                $this->sendAuthRequest();
            },
            function (\Exception $exception) use ($loop) {
                log_error($this->decorateMessage("Failed connection to rtbd: {$exception->getMessage()}"));
                $loop->stop();
            }
        );

        $loop->run();
    }

    /**
     * @return string
     */
    public function getNewRtbdName(): string
    {
        return $this->newRtbdName;
    }

    /**
     * @param string $newRtbdName
     * @return static
     */
    public function setNewRtbdName(string $newRtbdName): AuctionRtbdLinker
    {
        $this->newRtbdName = trim($newRtbdName);
        return $this;
    }

    /**
     * @return string
     */
    public function getOldRtbdName(): string
    {
        return $this->oldRtbdName;
    }

    /**
     * @param string $oldRtbdName
     * @return static
     */
    public function setOldRtbdName(string $oldRtbdName): AuctionRtbdLinker
    {
        $this->oldRtbdName = trim($oldRtbdName);
        return $this;
    }

    /**
     * @return string
     */
    public function getRtbdUri(): string
    {
        if ($this->rtbdUri === null) {
            $this->rtbdUri = $this->getRtbGeneralHelper()
                ->getRtbdUri(Constants\Rtb::UT_CLIENT, $this->getAuctionId());
        }
        return $this->rtbdUri;
    }

    /**
     * @param string $rtbdUri
     * @return static
     */
    public function setRtbdUri(string $rtbdUri): AuctionRtbdLinker
    {
        $this->rtbdUri = trim($rtbdUri);
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        if ($this->sessionId === null) {
            $this->sessionId = (string)session_id();
        }
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return static
     */
    public function setSessionId(string $sessionId): AuctionRtbdLinker
    {
        $this->sessionId = trim($sessionId);
        return $this;
    }

    /**
     * Handle response to authentication request
     * @param string[][] $response
     */
    protected function handleAuthResponse(array $response): void
    {
        if (
            isset($response[Constants\Rtb::RES_DATA][Constants\Rtb::RES_CONFIRM])
            && (int)$response[Constants\Rtb::RES_DATA][Constants\Rtb::RES_CONFIRM] !== 1
        ) {
            log_error($this->decorateMessage("Authorization failed"));
            $this->rtbd->close();
            return;
        }

        $this->sendLinkRequest();
    }

    /**
     * Handle response to buy now notifying request
     */
    protected function handleLinkResponse(): void
    {
        $this->rtbd->close();
        log_debug($this->decorateMessage("Link request completed successfully"));
    }

    /**
     * Send user authentication command to RTBD
     */
    protected function sendAuthRequest(): void
    {
        $keys = [
            $this->getEditorUserId(),
            Constants\Rtb::UT_CLIENT,
            $this->getSessionId(),  // TODO: for cli
            $this->cfg()->get('core->rtb->client->password'),
        ];
        // TODO: move to general class method composeUkey($keys), adjust in code
        $keyList = implode('|', $keys);
        $data = [
            Constants\Rtb::REQ_UKEY => $keyList,
            Constants\Rtb::REQ_AUCTION_ID => $this->getAuctionId(),
        ];
        $request = [
            Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_AUTH_Q,
            Constants\Rtb::REQ_DATA => $data,
        ];
        $this->send($request);
    }

    /**
     * Send buy now notifying command
     */
    protected function sendLinkRequest(): void
    {
        // We connect to old rtbd instance for command executing,
        // where we link auction to new rtbd instance.
        $data = [
            Constants\Rtb::REQ_NEW_RTBD_NAME => $this->getNewRtbdName(),
            Constants\Rtb::REQ_OLD_RTBD_NAME => $this->getOldRtbdName(),
        ];
        $request = [
            Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_LINK_RTBD_Q,
            Constants\Rtb::REQ_DATA => $data,
        ];
        $this->send($request);
    }

    /**
     * Add prefix and postfix with additional info to logged message
     * @param string $message
     * @return string
     */
    protected function decorateMessage(string $message): string
    {
        $message = "Rtbd request \"Link in pool\": {$message} {$this->getInfo()}";
        return $message;
    }

    /**
     * Additional info for logged message
     * @return string
     */
    protected function getInfo(): string
    {
        $logData = composeSuffix(
            [
                'a' => $this->getAuctionId(),
                'new rtbd' => $this->getNewRtbdName(),
                'old rtbd' => $this->getOldRtbdName(),
            ]
        );
        return $logData;
    }

    /**
     * Send request to RTBD server
     * @param array $request
     */
    protected function send(array $request): void
    {
        $requestJson = json_encode($request);
        log_debug($this->decorateMessage("Send to rtbd: {$requestJson}"));
        $eol = "\r\n";  // PHP_EOL
        $this->rtbd->send($requestJson . $eol . $eol);
    }
}
