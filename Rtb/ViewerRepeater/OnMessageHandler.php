<?php
/**
 * Handle incoming requests from viewers. We either forward request to RTBD server, or we return cached response.
 *
 * SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers
 * SAM-10639: RTBD Viewer-repeater - Avoid repeated authorization requests to rtbd origin
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 13, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\ViewerRepeater;

use Ratchet\WebSocket\WsConnection;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class OnMessageHandler
 * @package Sam\Rtb\ViewerRepeater
 */
class OnMessageHandler extends CustomizableClass
{
    use UserLoaderAwareTrait;

    protected ?Repeater $repeater = null;
    protected ?WsConnection $client = null;
    protected ?string $remoteAddress = null;
    public ?int $auctionId = null;
    protected string $message = '';

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Repeater $repeater
     * @return static
     */
    public function setRepeater(Repeater $repeater): static
    {
        $this->repeater = $repeater;
        return $this;
    }

    /**
     * @param WsConnection $client
     * @return static
     */
    public function setClientConnection(WsConnection $client): static
    {
        $this->client = $client;
        $this->remoteAddress = $client->remoteAddress;
        return $this;
    }

    /**
     * Define request data, sent from client
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $message = trim($message);
        $this->message = $this->repeater->getResponseManager()->sanitize($message);
        return $this;
    }

    /**
     * Process request received from client
     */
    public function handleRequest(): void
    {
        $resourceId = Cast::toInt($this->client->resourceId ?? null);
        log_debug("REQUEST << {$this->remoteAddress}; Resource Id: {$resourceId}, Request: {$this->message};");

        $messageArray = json_decode($this->message, true);

        if (empty($messageArray[Constants\Rtb::REQ_COMMAND])) {
            $this->handleInvalid('Command not defined');
            return;
        }
        $requestCmd = $messageArray[Constants\Rtb::REQ_COMMAND];

        if ($requestCmd === Constants\Rtb::CMD_AUTH_Q) {
            $this->auctionId = Cast::toInt($messageArray[Constants\Rtb::RES_DATA]['AuId'], Constants\Type::F_INT_POSITIVE);
        }
        if (!$this->auctionId) {
            $this->handleInvalid('Connection was not authenticated');
            return;
        }

        if ($this->repeater->getResponseManager()->isIgnoredCommand($requestCmd)) {
            log_trace("Command ignored" . composeSuffix([Constants\Rtb::REQ_COMMAND => $requestCmd]));
            return;
        }

        $responseJson = $this->repeater->getResponseManager()->getCachedResponse($requestCmd, $this->auctionId);
        if ($responseJson) {
            log_trace(
                "We already have cached response for \"{$requestCmd}\" command"
                . composeSuffix(['a' => $this->auctionId, 'response' => $responseJson, 'Resource Id' => $resourceId])
            );
            $this->handleResponse($responseJson);
        } else {
            log_trace(
                "Forward request to rtbd: {$this->message}, we want to get data from master server "
                . "for \"{$requestCmd}\" command" . composeSuffix(['a' => $this->auctionId, 'Resource Id' => $resourceId])
            );

            if ($requestCmd === Constants\Rtb::CMD_SYNC_Q) {
                $this->message = $this->addViewerResourceId($this->message, $resourceId);
            }

            $this->repeater->writeToRtbd($this->message, $this->auctionId);
        }
    }

    /**
     * Send response data to client
     * @param string $response
     */
    public function handleResponse(string $response): void
    {
        log_info(
            "RESPONSE >> {$this->client->remoteAddress}"
            . composeSuffix(['Resource Id' => $this->client->resourceId]) . ": {$response};"
        );
        $response = $this->repeater->getResponseManager()->sanitize($response);

        if ($this->isClientResponseReceiver($response, $this->client->resourceId)) {
            $this->client->send($response);
        }
    }

    /**
     * @param string $errorMessage
     */
    protected function handleInvalid(string $errorMessage): void
    {
        log_warning("!!{$errorMessage} FROM << ({$this->remoteAddress}):[{$this->message}]");
        $this->client->close();
    }

    protected function addViewerResourceId(string $message, int $resourceId): string
    {
        $messageArray = json_decode($message, true);
        $messageArray[Constants\Rtb::REQ_DATA][Constants\Rtb::REQ_VIEWER_RESOURCE_ID] = $resourceId;
        $message = json_encode($messageArray);
        return $message;
    }

    /**
     * Check if response is appropriable for the current viewer client.
     * For Sync command we want to send response to the viewer who required it. Then we identify viewer by resource id, that is passed through rtbd.
     * If there is no resource id, then it is broadcast SyncS response for all viewers.
     * @param string $response
     * @param int $resourceId
     * @return bool
     */
    protected function isClientResponseReceiver(string $response, int $resourceId): bool
    {
        $messageArray = json_decode($response, true);
        $responseCmd = $messageArray[Constants\Rtb::RES_COMMAND] ?? null;
        if ($responseCmd === Constants\Rtb::CMD_SYNC_S) {
            $viewerResourceId = Cast::toInt($messageArray[Constants\Rtb::REQ_DATA][Constants\Rtb::RES_VIEWER_RESOURCE_ID] ?? null);
            if ($viewerResourceId === null) {
                log_trace("Forward response, because viewer resource id not defined in response" . composeSuffix(['current resource id' => $resourceId]));
                return true;
            }

            if ($viewerResourceId === $resourceId) {
                log_trace("Forward response, because specified viewer is found" . composeSuffix(['resource id' => $resourceId]));
                return true;
            }

            log_trace("Do not forward" . composeSuffix(['current resource id' => $resourceId, 'response resource id' => $viewerResourceId]));
            return false;
        }

        return true;
    }
}
