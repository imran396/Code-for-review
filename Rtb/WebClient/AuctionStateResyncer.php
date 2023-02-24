<?php
/**
 * Send command for reloading rtb_current record in rtb daemon process scope from web side.
 *
 * SAM-5011: Reset auction's RTB state in daemon initiated from web side
 * SAM-5020: RtbCurrent record change outside of rtb daemon process
 *
 * When auction rtb state is changed at web side, eg:
 * - when live/hybrid auction reset is called from Auction List page,
 * - when we drop asking bid and increments at Auction Edit page saving \Sam\View\Admin\Form\AuctionEditForm::save()
 * then system sends auction rtb state resync request to rtb daemon, because we need to reload RtbCurrent object in rtbd scope.
 * We use this web side command handler \Sam\Rtb\WebClient\AuctionStateResyncer.
 * It sends rtbd command \Sam\Rtb\Command\Concrete\Base\Resync, that sends re-sync responses to all consoles.
 * When console receives re-sync response it initiates SyncQ for itself.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\WebClient;

use Exception;
use Ratchet\Client\WebSocket;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\ViewerRepeater\ResponseManager;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

use function Ratchet\Client\connect;

/**
 * Class AuctionStateResyncer
 * @package Sam\Rtb\WebClient
 */
class AuctionStateResyncer extends CustomizableClass
{
    use AuctionAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use LotItemAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use SystemAccountAwareTrait;

    protected ?string $rtbdUri = null;
    protected ?WebSocket $rtbd = null;
    protected ?string $sessionId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function notifyRtbd(): void
    {
        if (!$this->cfg()->get('core->rtb->client->password')) {
            log_error($this->decorateMessage("Error: core->rtb->client->password not defined"));
            return;
        }

        $rtbdUri = $this->getRtbdUri();
        log_debug("Trying to connect {$rtbdUri} for notifying rtb daemon about auction rtb state resync");
        connect($rtbdUri)->then(
            function (WebSocket $rtbd) {
                log_debug($this->decorateMessage("Successfully connected to Rtbd {$this->getRtbdUri()}"));
                $this->rtbd = $rtbd;
                $this->rtbd->on(
                    'message',
                    function ($responseJson) {
                        $responseJson = ResponseManager::new()->sanitize($responseJson);
                        log_debug($this->decorateMessage("We got response from Rtbd {$responseJson}"));
                        $response = json_decode($responseJson, true);
                        if (isset($response[Constants\Rtb::RES_COMMAND])) {
                            if ($response[Constants\Rtb::RES_COMMAND] === Constants\Rtb::CMD_AUTH_S) {
                                $this->handleAuthResponse($response);
                            } elseif ($response[Constants\Rtb::RES_COMMAND] === Constants\Rtb::CMD_RESYNC_S) {
                                $this->handleResyncResponse();
                            }
                        }
                    }
                );
                $this->rtbd->on(
                    'close',
                    function ($code = null, $reason = null) {
                        log_debug(
                            $this->decorateMessage(
                                "Rtbd connection closed with reason"
                                . composeSuffix([$code => $reason])
                            )
                        );
                    }
                );

                $this->sendAuthRequest();
            },
            function (Exception $exception) {
                log_error($this->decorateMessage("Failed connection to rtbd: {$exception->getMessage()}"));
            }
        );
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

        $this->sendResetAuctionRequest();
    }

    /**
     * Handle response to buy now notifying request
     */
    protected function handleResyncResponse(): void
    {
        $this->rtbd->close();
        log_debug($this->decorateMessage("Auction rtb state resync request completed successfully"));
    }

    /**
     * Send user authentication command to RTBD
     */
    protected function sendAuthRequest(): void
    {
        $keys = [
            $this->getEditorUserId(),
            Constants\Rtb::UT_CLIENT,
            $this->getSessionId(),
            $this->cfg()->get('core->rtb->client->password'),
        ];
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
    protected function sendResetAuctionRequest(): void
    {
        $data = [
            Constants\Rtb::REQ_AUCTION_ID => $this->getAuctionId(),
        ];
        $request = [
            Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_RESYNC_Q,
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
        $message = "Rtbd request \"Resync\": {$message}{$this->getLogData()}";
        return $message;
    }

    /**
     * Additional info for logged message
     * @return string
     */
    protected function getLogData(): string
    {
        $logData = composeSuffix(
            [
                'a' => $this->getAuctionId(),
                'u' => $this->getEditorUserId(),
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
        log_debug("Send to " . composeLogData(["rtbd{$this->getLogData()}" => $requestJson]));
        $eol = "\r\n";  // PHP_EOL
        $this->rtbd->send($requestJson . $eol . $eol);
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
    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = trim($sessionId);
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
    public function setRtbdUri(string $rtbdUri): static
    {
        $this->rtbdUri = trim($rtbdUri);
        return $this;
    }
}
