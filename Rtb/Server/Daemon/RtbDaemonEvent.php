<?php

namespace Sam\Rtb\Server\Daemon;

use Event;
use EventBase;
use EventConfig;
use EventListener;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\File\FileSizeRenderer;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Installation\Config\Repository\Invalidate\ConfigStateInvalidatorCreateTrait;
use Sam\Rtb\BidderInterest\BidderInterestConsoleUpdaterAwareTrait;
use Sam\Rtb\BidderInterest\BidderInterestManagerAwareTrait;
use Sam\Rtb\Hybrid\Run\RtbdProcessor;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\Auth\AuthQueueManagerAwareTrait;
use Sam\Rtb\Server\ConnectionQuality\ConnectionQualityCheckerAwareTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Event\EventSocketClient;
use Sam\Rtb\Server\SocketBase\Event\EventSocketException;
use Sam\Rtb\Server\Statistic\StatisticManagerAwareTrait;
use Sam\Rtb\Session\RtbSessionManagerCreateTrait;
use Socket;
use Throwable;

/**
 * Class RtbDaemonEvent
 */
class RtbDaemonEvent extends CustomizableClass implements RtbDaemonInterface
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

    private const TICK_TIMEOUT = 1;
    private const AUTH_QUEUE_TIMEOUT = 1;
    private const PING_TIMEOUT = 5;
    private const WHAT = Event::TIMEOUT | Event::PERSIST;

    /** @var EventClient[] */
    public array $clientSockets = [];
    public EventBase $base;
    public EventListener $listener;
    /**
     * Rtbd instance name. Actual for rtbd pool feature only (SAM-3611).
     * Is used for filtering running hybrid auctions.
     */
    protected string $name = '';
    protected string $pidFileName = 'rtb.pid';
    protected array $info = [];
    /**
     * @var Event[] store events, otherwise they are collected by GC
     */
    protected array $events = [];

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
        $bindHost = $bindHost ?: '127.0.0.1';
        $bindPort = (int)$bindPort;
        $target = "{$bindHost}:{$bindPort}";
        $this->info = [
            'PID' => getmypid(),
            'event loop' => 'Epoll rtbd',
            'bind address' => $target,
        ];

        $this->base = new EventBase(null); // @phpstan-ignore-line

        $this->listener = new EventListener(
            $this->base,
            [$this, "acceptConnCallback"],
            [],
            EventListener::OPT_CLOSE_ON_FREE | EventListener::OPT_REUSEABLE,
            -1,
            $target
        );

        $features = $this->base->getFeatures();
        $logData = [
            'Listener FD' => $this->listener->fd,
            'Method' => $this->base->getMethod(),
            'Features' => $features,
            'ET' => $features & EventConfig::FEATURE_ET,
            '01' => $features & EventConfig::FEATURE_O1,
            'FDS' => $features & EventConfig::FEATURE_FDS
        ];
        log_debug('Server socket created' . composeLogData($logData));
        return $this;
    }

    /**
     * This callback is invoked when new connection event arises.
     * @param $listener
     * @param int $socketFd
     * @param array $address
     * @param array $callbackData
     */
    public function acceptConnCallback($listener, int $socketFd, array $address, array $callbackData): void
    {
        $client = EventClient::new()->constructEventClient($socketFd, $this, $address, $this->base);
        $this->clientSockets[$socketFd] = $client;
        $logData = [
            'listener' => $listener,
            'client socket fd' => $socketFd,
            'address' => $address,
            'client class' => get_class($client),
            'all client fd' => array_keys($this->clientSockets),
        ];
        log_debug('Client connection registered' . composeSuffix($logData));
    }

    /**
     * @param EventSocketClient $client
     * @param string $remoteAddress
     * @param int $remotePort
     * @return EventSocketClient
     * @throws EventSocketException
     */
    public function createClient(
        EventSocketClient $client,
        string $remoteAddress,
        int $remotePort
    ): EventSocketClient {
        $client->construct($this->base);
        $client->connect($remoteAddress, $remotePort);
        return $client;
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
     * Daemon main loop
     */
    public function process(): void
    {
        // TODO: do not cache with 1 sec ttl, but cache single command handling - one loop iteration of socket_select()
        $this->cfg()->set('core->cache->memory->adapter->options->ttl', 1);
        $this->savePid();
        $this->createRtbSessionManager()->dropAll();
        $this->getBidderInterestConsoleUpdater()->initTime();
        $this->getStatisticManager()->setRtbDaemon($this);
        $hybridProcessor = RtbdProcessor::new()->setRtbDaemon($this);

        $this->addToLoopTick();
        $this->addToLoopConnectionQualityCheck();
        $this->addToLoopStatisticSend();
        $this->addToLoopAuthQueue();
        $this->addToLoopBiddingInterest();
        $this->addToLoopConfigReload();
        $this->addToLoopGarbageCollection();

        do {
            try {
                $hybridProcessor
                    ->setClients($this->clientSockets)
                    ->process();
                $this->base->loop(EventBase::LOOP_ONCE);
            } catch (Throwable $e) {
                $logData = [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'call stack' => $e->getTraceAsString()
                ];
                $msg = "Error during event loop" . composeSuffix($logData);
                log_error($msg);
            }
        } while (true); // @phpstan-ignore-line
    }

    protected function addToLoopTick(): void
    {
        $tickEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->tick();
            }
        );
        $tickEvent->add(self::TICK_TIMEOUT);
        $this->events[] = $tickEvent;
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
//        $logData = [
//            "Client Sockets" => count($this->clientSockets),
//            "Client Resources" => array_keys($this->clientSockets),
//        ];
//        log_trace('Every sec timer stats' . composeSuffix($logData));
    }

    protected function addToLoopConnectionQualityCheck(): Event
    {
        $pingEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->getConnectionQualityChecker()->process($this->clientSockets);
            }
        );
        $pingEvent->add(self::PING_TIMEOUT);
        return $pingEvent;
    }

    protected function addToLoopStatisticSend(): void
    {
        if (!$this->cfg()->get('core->rtb->stats->enabled')) {
            return;
        }

        $sendStatsEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->getStatisticManager()->process($this->clientSockets);
            }
        );
        $sendStatsEvent->add($this->cfg()->get('core->rtb->stats->timeout'));
        $this->events[] = $sendStatsEvent;
    }

    protected function addToLoopAuthQueue(): void
    {
        if (!$this->cfg()->get('core->rtb->server->shouldAuth')) {
            return;
        }

        $authQueueEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->getAuthQueueManager()->process();
            }
        );
        $authQueueEvent->add(self::AUTH_QUEUE_TIMEOUT);
        $this->events[] = $authQueueEvent;
    }

    protected function addToLoopBiddingInterest(): void
    {
        if (!$this->cfg()->get('core->rtb->biddingInterest->enabled')) {
            return;
        }

        $biddingInterestEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->getBidderInterestConsoleUpdater()->refreshInterest($this);
            }
        );
        $biddingInterestEvent->add($this->cfg()->get('core->rtb->biddingInterest->gcTimeout'));
        $this->events[] = $biddingInterestEvent;
    }

    protected function addToLoopConfigReload(): void
    {
        $configRefreshTimeout = $this->cfg()->get('core->rtb->configuration->refreshTimeout');
        if (!$configRefreshTimeout) {
            return;
        }

        $refreshConfigEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->createConfigStateInvalidator()->invalidate();
            }
        );
        $refreshConfigEvent->add($configRefreshTimeout);
        $this->events[] = $refreshConfigEvent;
    }

    protected function addToLoopGarbageCollection(): void
    {
        if (!$this->cfg()->get('core->rtb->server->gcCyclesTimeout')) {
            return;
        }

        $collectGarbageEvent = new Event(
            $this->base, -1, self::WHAT,
            function () {
                $this->collectGarbage();
            }
        );
        $collectGarbageEvent->add($this->cfg()->get('core->rtb->server->gcCyclesTimeout'));
        $this->events[] = $collectGarbageEvent;
    }

    /**
     * Force collection of GC cycles
     */
    public function collectGarbage(): void
    {
        /**
         * Warm up existing client connection sockets before garbage collection,
         * and get info for log. Possibly it prevents unexpected destructing of socket object.
         */
        $consoleInfos = [];
        foreach ($this->clientSockets as $clientSocket) {
            $consoleInfos[] = $clientSocket->logInfo();
        }
        log_debug('Starting force garbage collection' . composeSuffix(['clients' => $consoleInfos]));

        // Collecting garbage
        $memOld = memory_get_usage(true);
        gc_collect_cycles();
        $memNow = memory_get_usage(true);
        $diff = $memOld - $memNow;
        if (!$diff) {
            return;
        }

        $fileSizeRenderer = FileSizeRenderer::new();
        $logData = [
            'freed' => $fileSizeRenderer->renderHumanReadableSize($diff),
            'before' => $fileSizeRenderer->renderHumanReadableSize($memOld),
            'now' => $fileSizeRenderer->renderHumanReadableSize($memNow),
        ];
        log_debug('Some memory has been freed' . composeSuffix($logData));
    }

    protected function savePid(): void
    {
        LocalFileManager::new()->createDirPath(path()->logRun() . '/');
        file_put_contents(path()->logRun() . '/' . $this->getPidFileName(), getmypid());
    }

    /**
     * This function results with Socket object on the base of socket-kind file descriptor.
     * Since it works in linux only, we don't call it now.
     * Keep it for possible usage in further.
     * @param $clientResourceFd
     * @return Socket
     */
    protected function produceSocketByFileDescriptor($clientResourceFd): Socket
    {
        $filename = sprintf("php://fd/%d", $clientResourceFd);
        $clientResource = fopen($filename, 'rb');
        $clientSocket = socket_import_stream($clientResource);
        return $clientSocket;
    }
}
