<?php

namespace Sam\Rtb\Server\Daemon;

use Exception;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Installation\Config\Repository\Invalidate\ConfigStateInvalidatorCreateTrait;
use Sam\Rtb\BidderInterest\BidderInterestConsoleUpdaterAwareTrait;
use Sam\Rtb\BidderInterest\BidderInterestManagerAwareTrait;
use Sam\Rtb\Hybrid\Run\RtbdProcessor;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\Auth\AuthQueueManagerAwareTrait;
use Sam\Rtb\Server\ConnectionQuality\ConnectionQualityCheckerAwareTrait;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyServer;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketAbstract;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketDaemon;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketException;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketServerClient;
use Sam\Rtb\Server\Statistic\StatisticManagerAwareTrait;
use Sam\Rtb\Session\RtbSessionManagerCreateTrait;

/**
 * @property LegacyClient[] $clientSockets initialized by [] by default
 */
class RtbDaemonLegacy extends LegacySocketDaemon implements RtbDaemonInterface
{
    use AuthQueueManagerAwareTrait;
    use BidderInterestConsoleUpdaterAwareTrait;
    use BidderInterestManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ConfigStateInvalidatorCreateTrait;
    use ConnectionQualityCheckerAwareTrait;
    use MemoryCacheManagerAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbSessionManagerCreateTrait;
    use StatisticManagerAwareTrait;

    private const CLEAN_SOCKETS_TIMEOUT = 5;
    private const TICK_TIMEOUT = 1;
    private const AUTH_QUEUE_TIMEOUT = 1;
    private const PING_TIMEOUT = 5;

    protected array $loopCallbacks = [];
    /**
     * Rtbd instance name. Actual for rtbd pool feature only (SAM-3611).
     * Is used for filtering running hybrid auctions.
     */
    protected string $name = '';
    protected string $pidFileName = 'rtb.pid';
    protected array $info = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?string $bindHost = null, ?int $bindPort = null): static
    {
        $serverSocket = new LegacyServer(
            LegacyClient::class,
            $bindHost,
            $bindPort,
            AF_INET,
            SOCK_STREAM,
            SOL_TCP,
            $this
        );
        $serverFd = SocketHelper::new()->getSocketFd($serverSocket->socketResource);
        $this->serverSockets[$serverFd] = $serverSocket;
        $this->info = [
            'PID' => getmypid(),
            'event loop' => 'Socket-select rtbd',
            'bind address' => "{$bindHost}:{$bindPort}",
        ];
        return $this;
    }

    /**
     * Daemon main loop
     */
    public function process(): void
    {
        // TODO: do not cache with 1 sec ttl, but cache single command handling - one loop iteration of socket_select()
        $this->cfg()->set('core->cache->memory->adapter->options->ttl', 1);
        // $this->getMemoryCacheManager()
        //     ->enable(cfg()->core->rtb->memoryCache->enabled);
        $this->savePid();
        $this->createRtbSessionManager()->dropAll();
        // if socketClient is in write set, and $socket->connecting === true, set connecting to false and call on_connect
        $readSocketResources = $this->getReadSocketResources();
        $writeSocketResources = $this->getWriteSocketResources();
        $exceptionSocketResources = $this->getExceptionSocketResources();
        $this->getBidderInterestConsoleUpdater()->initTime();
        $this->getStatisticManager()->setRtbDaemon($this);
        $hybridProcessor = RtbdProcessor::new()->setRtbDaemon($this);

        $this->addToLoopTick();
        $this->addToLoopCleanSockets();
        $this->addToLoopConnectionQualityCheck();
        $this->addToLoopStatisticSend();
        $this->addToLoopAuthQueue();
        $this->addToLoopBiddingInterest();
        $this->addToLoopConfigReload();

        while (($events = @socket_select($readSocketResources, $writeSocketResources, $exceptionSocketResources, 1)) !== false) {
            if ($events > 0) {
                $this->processAllSockets($readSocketResources, $writeSocketResources, $exceptionSocketResources);
            }

            $hybridProcessor
                ->setClients($this->clientSockets)
                ->process();

            $this->loop();

            $readSocketResources = $this->getReadSocketResources();
            $writeSocketResources = $this->getWriteSocketResources();
            $exceptionSocketResources = $this->getExceptionSocketResources();
        }
    }

    /**
     * Return name of rtbd instance
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define name of rtbd instance
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPidFileName(): string
    {
        return $this->pidFileName;
    }

    /**
     * @param string $filename
     * @return static
     */
    public function setPidFileName(string $filename): static
    {
        $this->pidFileName = trim($filename);
        return $this;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * Dispatch pending callbacks
     */
    protected function loop(): void
    {
        foreach ($this->loopCallbacks as $i => $data) {
            if ($data['runTime'] <= time()) {
                $data['cb']();
                $this->loopCallbacks[$i]['runTime'] = time() + $data['timeout'];
            }
        }
    }

    /**
     * Register callback and timeout when to run it periodically by interval
     * @param int $timeout
     * @param callable $cb
     */
    protected function addToLoop(int $timeout, callable $cb): void
    {
        $this->loopCallbacks[] = [
            'timeout' => $timeout,
            'runTime' => time() + $timeout,
            'cb' => $cb,
        ];
    }

    protected function addToLoopTick(): void
    {
        $this->addToLoop(
            self::TICK_TIMEOUT,
            function () {
                $this->tick();
            }
        );
    }

    /**
     * Every second operations
     */
    protected function tick(): void
    {
        // Set a timer event for the maximum idle time if there are no other events
        foreach ($this->clientSockets as $client) {
            $client->onTimer();
        }

        // clean memory cache per one second interval
        $this->getMemoryCacheManager()->clearExpired();
        /*
        $logData = [
            "Client Sockets" => count($this->clientSockets),
            "Client Resources" => array_keys($this->clientSockets),
            "Server Sockets" => count($this->serverSockets),
            "Server Resources" => array_keys($this->serverSockets),
        ];
        log_trace('Every sec timer stats' . composeSuffix($logData));*/
    }

    protected function addToLoopCleanSockets(): void
    {
        $this->addToLoop(
            self::CLEAN_SOCKETS_TIMEOUT,
            function () {
                $this->cleanSockets();
            }
        );
    }

    protected function addToLoopConnectionQualityCheck(): void
    {
        $this->addToLoop(
            self::PING_TIMEOUT,
            function () {
                $this->getConnectionQualityChecker()->process($this->clientSockets);
            }
        );
    }

    protected function addToLoopStatisticSend(): void
    {
        if ($this->cfg()->get('core->rtb->stats->enabled')) {
            $this->addToLoop(
                $this->cfg()->get('core->rtb->stats->timeout'),
                function () {
                    $this->getStatisticManager()->process($this->clientSockets);
                }
            );
        }
    }

    protected function addToLoopAuthQueue(): void
    {
        if ($this->cfg()->get('core->rtb->server->shouldAuth')) {
            $this->addToLoop(
                self::AUTH_QUEUE_TIMEOUT,
                function () {
                    $this->getAuthQueueManager()->process();
                }
            );
        }
    }

    protected function addToLoopBiddingInterest(): void
    {
        if ($this->cfg()->get('core->rtb->biddingInterest->enabled')) {
            $this->addToLoop(
                $this->cfg()->get('core->rtb->biddingInterest->gcTimeout'),
                function () {
                    $this->getBidderInterestConsoleUpdater()->refreshInterest($this);
                }
            );
        }
    }

    protected function addToLoopConfigReload(): void
    {
        $configRefreshTimeout = $this->cfg()->get('core->rtb->configuration->refreshTimeout');
        if ($configRefreshTimeout > 0) {
            $this->addToLoop(
                $configRefreshTimeout,
                function () {
                    $this->createConfigStateInvalidator()->invalidate();
                }
            );
        }
    }

    /**
     * @param LegacySocketAbstract[] $readSocketResources
     * @param LegacySocketAbstract[] $writeSocketResources
     * @param LegacySocketAbstract[] $exceptionSocketResources
     * @throws LegacySocketException
     */
    protected function processAllSockets($readSocketResources, $writeSocketResources, $exceptionSocketResources): void
    {
        foreach ($readSocketResources as $readSocketResource) {
            $this->processReadSocket($readSocketResource);
        }
        foreach ($writeSocketResources as $writeSocketResource) {
            $this->processWriteSocket($writeSocketResource);
        }
        foreach ($exceptionSocketResources as $exceptionSocketResource) {
            $this->processExceptionSocket($exceptionSocketResource);
        }
    }

    /**
     * @param LegacySocketAbstract $readSocketResource
     * @throws LegacySocketException
     */
    protected function processReadSocket($readSocketResource): void
    {
        try {
            $readSocket = $this->findSocket($readSocketResource);
        } catch (Exception $e) {
            log_info('Failed to determine class for read socket: ' . $e->getMessage());
            $readSocket = null;
        }
        if ($readSocket instanceof LegacyServer) {
            try {
                /** @var LegacySocketServerClient $clientSocket */
                $clientSocket = $readSocket->accept();
                $this->clientSockets[SocketHelper::new()->getSocketFd($clientSocket->socketResource)] = $clientSocket;
            } catch (Exception $e) {
                log_error('Failed to accept connection: ' . $e->getMessage());
            }
        } elseif ($readSocket instanceof LegacySocketClient) {
            // regular "on read" event
            /** @var LegacyServer $readSocket */
            $readSocket->read();
        }
    }

    /**
     * @param LegacySocketAbstract $writeSocketResource
     */
    protected function processWriteSocket($writeSocketResource): void
    {
        try {
            /** @var LegacySocketClient $writeSocket */
            $writeSocket = $this->findSocket($writeSocketResource);
        } catch (Exception $e) {
            log_info('Failed to determine class for write socket: ' . $e->getMessage());
            $writeSocket = null;
        }
        if ($writeSocket instanceof LegacySocketClient) {
            if ($writeSocket->connecting) {
                $writeSocket->onConnect();
                $writeSocket->connecting = false;
            }
            $writeSocket->doWrite();
        }
    }

    /**
     * @param LegacySocketAbstract $exceptionSocketResource
     */
    protected function processExceptionSocket($exceptionSocketResource): void
    {
        try {
            /** @var LegacySocketClient $exceptionSocket */
            $exceptionSocket = $this->findSocket($exceptionSocketResource);
        } catch (Exception $e) {
            log_info('Failed to determine class for exception socket: ' . $e->getMessage());
            $exceptionSocket = null;
        }
        if ($exceptionSocket instanceof LegacySocketClient) {
            $exceptionSocket->onDisconnect();
            $socketFd = SocketHelper::new()->getSocketFd($exceptionSocket->socketResource);
            if (isset($this->clientSockets[$socketFd])) {
                unset($this->clientSockets[$socketFd]);
            }
        }
    }

    protected function savePid(): void
    {
        LocalFileManager::new()->createDirPath(path()->logRun() . '/');
        file_put_contents(path()->logRun() . '/' . $this->getPidFileName(), getmypid());
    }
}
