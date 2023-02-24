<?php
/**
 * Viewer-repeater serve client web-socket connections from Viewer/Projector consoles
 * and connects to RTBD server via tcp connection for forwarding requests from client.
 * We keep one connection per auction.
 * See, OnMessageHandler::handleRequest() for request handling logic. It also caches responses.
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

use Exception;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;
use React\EventLoop\LoopInterface;
use Sam\Core\Constants;
use Sam\Log\Support\SupportLoggerAwareTrait;
use SplObjectStorage;

/**
 * Class Repeater
 * @package Sam\Rtb\ViewerRepeater
 */
class Repeater implements MessageComponentInterface
{
    use SupportLoggerAwareTrait;

    protected LoopInterface $loop;
    /**
     * Store connected client connections
     * @var SplObjectStorage<WsConnection>
     */
    protected SplObjectStorage $clientConnections;
    /**
     * Store incoming message handlers for connected clients
     * @var OnMessageHandler[]
     */
    protected array $onMessageHandlers = [];
    protected ResponseManager $responseManager;
    protected RtbdConnectionPool $rtbdConnectionPool;

    /**
     * Repeater constructor.
     * @param LoopInterface $loop
     * @param string $rtbdUri
     */
    public function __construct(LoopInterface $loop, string $rtbdUri)
    {
        $this->loop = $loop;
        $this->clientConnections = new SplObjectStorage();
        $this->responseManager = ResponseManager::new();
        $this->rtbdConnectionPool = new RtbdConnectionPool($this, $rtbdUri);
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onOpen(\Ratchet\ConnectionInterface $conn): void
    {
        $this->clientConnections->attach($conn);
        $message = "Client connection opened" . composeSuffix($this->getClientLogInfo($conn));
        $this->log($message);
    }

    /**
     * @param \Ratchet\ConnectionInterface $from
     * @param string $message
     */
    public function onMessage(\Ratchet\ConnectionInterface $from, $message): void
    {
        $this->getOnMessageHandler($from)
            ->setMessage($message)
            ->handleRequest();
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onClose(\Ratchet\ConnectionInterface $conn): void
    {
        $this->removeOnMessageHandler($conn);
        $this->clientConnections->detach($conn);
        $message = "Client connection has disconnected" . composeSuffix($this->getClientLogInfo($conn));
        $this->log($message);
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param Exception $e
     */
    public function onError(\Ratchet\ConnectionInterface $conn, Exception $e): void
    {
        $conn->close();
        $message = "Client connection error has occurred"
            . composeSuffix(['error' => $e->getMessage()] + $this->getClientLogInfo($conn));
        $this->log($message);
    }

    /**
     * Forward message to rtbd server via defined auction connection
     * @param string $message
     * @param int $auctionId
     */
    public function writeToRtbd(string $message, int $auctionId): void
    {
        $this->rtbdConnectionPool->write($message, $auctionId);
    }

    /**
     * @return ResponseManager
     */
    public function getResponseManager(): ResponseManager
    {
        return $this->responseManager;
    }

    /**
     * @return WsConnection[]|SplObjectStorage
     */
    public function getClientConnections(): iterable
    {
        return $this->clientConnections;
    }

    public function closeAllClientConnections(): void
    {
        foreach ($this->clientConnections as $clientConnection) {
            $this->clientConnections->detach($clientConnection);
            $clientConnection->close();
        }
    }

    /**
     * @return LoopInterface
     */
    public function getLoop(): LoopInterface
    {
        return $this->loop;
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @return OnMessageHandler
     */
    public function getOnMessageHandler(\Ratchet\ConnectionInterface $conn): OnMessageHandler
    {
        if (empty($this->onMessageHandlers[$conn->resourceId])) {
            $this->onMessageHandlers[$conn->resourceId] = OnMessageHandler::new()
                ->setRepeater($this)
                ->setClientConnection($conn);
        }
        return $this->onMessageHandlers[$conn->resourceId];
    }

    /**
     * @param string $message
     * @param int $logLevel
     */
    public function log($message, int $logLevel = Constants\Debug::INFO): void
    {
        $this->getSupportLogger()->log($logLevel, $message, 2);
        echo $message . PHP_EOL;
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     */
    protected function removeOnMessageHandler(\Ratchet\ConnectionInterface $conn): void
    {
        unset($this->onMessageHandlers[$conn->resourceId]);
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @return array
     */
    protected function getClientLogInfo(\Ratchet\ConnectionInterface $conn): array
    {
        $logData = [
            'Resource Id' => $conn->resourceId,
            'Remote Address' => $conn->remoteAddress,
            'Total' => $this->clientConnections->count(),
        ];

        if ($conn->httpRequest instanceof \GuzzleHttp\Psr7\Request) {
            $realIp = $conn->httpRequest->getHeader("X-Real-IP")
                ?: $conn->httpRequest->getHeader("X-Forwarded-For")
                    ?: null;
            if ($realIp) {
                $logData['Remote Address'] = $realIp;
            }
        }

        return $logData;
    }
}
