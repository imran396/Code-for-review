<?php
/**
 * Manages connections to rtbd server and sends data
 *
 * SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers
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

use Exception;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use React\Promise\Promise;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Url\UrlParser;

/**
 * Class RtbdConnectionPool
 * @package Sam\Rtb\ViewerRepeater
 */
class RtbdConnectionPool
{
    protected Repeater $repeater;
    /** @var WebSocket[] */
    protected array $rtbdConnections = [];
    protected string $rtbdUri;

    /**
     * Repeater constructor.
     * @param Repeater $repeater
     * @param string $rtbdUri
     */
    public function __construct(Repeater $repeater, string $rtbdUri)
    {
        $this->repeater = $repeater;
        $scheme = UrlParser::new()->extractScheme($rtbdUri);
        if ($scheme === '') {
            $rtbdUri = 'ws://' . $rtbdUri;
        } elseif ($scheme !== 'ws') {
            throw new \InvalidArgumentException("Wrong scheme in rtbd connection uri. Must be ws://");
        }
        $this->rtbdUri = $rtbdUri;
    }

    /**
     * Send command to RTBD server to get fresh response for clients
     * @param string $message
     * @param int $auctionId
     */
    public function write(string $message, int $auctionId): void
    {
        // log_trace('Forwarding to rtbd a: ' . $auctionId . ', message: ' . $message);
        $message = $this->repeater->getResponseManager()->prepareResponseToRtbd($message);
        $rtbdConnectionValueOrPromise = $this->getRtbdConnectionValueOrPromise($auctionId);
        if ($rtbdConnectionValueOrPromise instanceof WebSocket) {
            $rtbdConnectionValueOrPromise->send($message);
        } else {
            $rtbdConnectionValueOrPromise->then(
                static function (WebSocket $rtbd) use ($message) {
                    $rtbd->send($message);
                }
            );
        }
    }

    /**
     * @param int $auctionId
     * @return bool
     */
    protected function hasRtbdConnection(int $auctionId): bool
    {
        $has = !empty($this->rtbdConnections[$auctionId]);
        return $has;
    }

    /**
     * @param int $auctionId
     * @return WebSocket|Promise
     */
    protected function getRtbdConnectionValueOrPromise(int $auctionId): WebSocket|Promise
    {
        if (!$this->hasRtbdConnection($auctionId)) {
            $this->rtbdConnections[$auctionId] = $this->getRtbdConnectionPromise($auctionId);
        }
        return $this->rtbdConnections[$auctionId];
    }

    /**
     * @param int $auctionId
     * @return Promise
     */
    protected function getRtbdConnectionPromise(int $auctionId): Promise
    {
        $connector = new Connector($this->repeater->getLoop());
        /** @var Promise $promise */
        $promise = $connector($this->rtbdUri);
        $message = "Rtbd connection requested ({$this->rtbdUri} for auction: {$auctionId})";
        $this->repeater->log($message);
        $promise->then(
            function (WebSocket $rtbd) use ($auctionId) {
                $this->rtbdConnections[$auctionId] = $rtbd;
                $this->registerRtbdResponseHandler($rtbd, $auctionId);
                $message = "Rtbd connection opened ({$this->rtbdUri} for auction: {$auctionId})";
                $this->repeater->log($message);
            },
            function (Exception $exception) use ($auctionId) {
                $this->rtbdConnections[$auctionId] = false;
                $message = "Rtbd connection failed ({$this->rtbdUri} for auction: {$auctionId}). Error: " . $exception->getMessage();
                $this->repeater->log($message, Constants\Debug::ERROR);
            }
        );
        return $promise;
    }

    /**
     * @param WebSocket $rtbd
     * @param int $auctionId
     */
    protected function registerRtbdResponseHandler(WebSocket $rtbd, int $auctionId): void
    {
        $rtbd->on(
            'message',
            function ($responseJson) use ($auctionId) {
                $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
                log_trace("We got data from rtbd" . composeSuffix(['a' => $auctionId, 'response' => $responseJson]));
                $responseJson = ResponseManager::new()->sanitize($responseJson);
                $this->repeater->getResponseManager()->cacheResponse($responseJson, $auctionId);
                foreach ($this->repeater->getClientConnections() as $client) {
                    $handler = $this->repeater->getOnMessageHandler($client);
                    if ($handler->auctionId === $auctionId) {
                        $handler->handleResponse($responseJson);
                    }
                }
            }
        );

        $rtbd->on(
            'error',
            function (Exception $e) use ($rtbd, $auctionId) {
                $rtbd->close();
                $this->rtbdConnections = [];
                $message = "Rtbd connection lost ({$this->rtbdUri} for auction: {$auctionId}). " . $e->getMessage();
                $this->repeater->log($message, Constants\Debug::ERROR);
            }
        );
        $rtbd->on(
            'close',
            function () use ($auctionId) {
                $this->rtbdConnections = [];
                $this->repeater->closeAllClientConnections();
                $message = "Rtbd connection closed ({$this->rtbdUri} for auction: {$auctionId})";
                $this->repeater->log($message);
            }
        );
    }
}
