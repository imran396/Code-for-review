<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Statistic;

use ReflectionClass;
use ReflectionException;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Event\EventSocketClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketClient;
use Sam\Rtb\Server\Statistic\Internal\StatisticEventClient;
use Sam\Rtb\Server\Statistic\Internal\StatisticLegacyClient;

/**
 * Class StatisticManager
 * @package
 */
class StatisticManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use RtbDaemonAwareTrait;
    use RtbGeneralHelperAwareTrait;

    private LegacySocketClient|EventSocketClient|null $statsSocketClient = null;
    private ?int $startTime = null;
    /** @var string[] */
    private array $classes = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->startTime = time();
        $this->classes['bidderLive'] = BidderLiveController::class;
        $this->classes['bidderHybrid'] = BidderHybridController::class;
        $this->classes['viewerLive'] = ViewerLiveController::class;
        $this->classes['viewerHybrid'] = ViewerHybridController::class;
        $this->classes['adminLive'] = AdminLiveController::class;
        $this->classes['adminHybrid'] = AdminHybridController::class;
        $this->classes['auctioneer'] = AuctioneerController::class;
        return $this;
    }

    /**
     * @param LegacyClient[]|EventClient[] $clients
     */
    public function process(array $clients): void
    {
        if (
            $this->statsSocketClient instanceof StatisticLegacyClient
            && $this->statsSocketClient->disconnected
        ) {
            $this->statsSocketClient = null;
        }

        if ($this->statsSocketClient) {
            /**
             * When $this->statsClient exists, means previous connection sending has not completed yet.
             */
            return;
        }

        $ts = microtime(true);
        // only send stats every minute

        $statistics = [
            'Connections' => 0,
            'Bidders' => 0,
            'Viewers' => 0,
            'Clerks' => 0,
            'Auctioneers' => 0,
        ];
        foreach ($clients ?: [] as $client) {
            if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                // skip console independent sockets, i.e. TextMessage_SocketClient, Stats_SocketClient,
                continue;
            }
            if ($client->getRtbCommandController() === null) {
                log_trace('RtbCommandController dependency absent in httpd server client'); // it is ok, because Stats_SocketClient does not have RtbCommandController
                continue;
            }
            $className = get_class($client->getRtbCommandController());
            if (!$className) {
                log_error('Cannot determine class of RtbCommandController dependency in httpd server client');
                continue;
            }
            // log_trace('Console command name' . composeSuffix(['class' => $className]));
            try {
                $reflection = new ReflectionClass($className);
                if (
                    in_array($className, [$this->classes['bidderLive'], $this->classes['bidderHybrid']], true)
                    || $reflection->isSubclassOf($this->classes['bidderLive'])
                    || $reflection->isSubclassOf($this->classes['bidderHybrid'])
                ) {
                    $statistics['Bidders']++;
                } elseif (
                    in_array($className, [$this->classes['viewerLive'], $this->classes['viewerHybrid']], true)
                    || $reflection->isSubclassOf($this->classes['viewerLive'])
                    || $reflection->isSubclassOf($this->classes['viewerHybrid'])
                ) {
                    $statistics['Viewers']++;
                } elseif (
                    in_array($className, [$this->classes['adminLive'], $this->classes['adminHybrid']], true)
                    || $reflection->isSubclassOf($this->classes['adminLive'])
                    || $reflection->isSubclassOf($this->classes['adminHybrid'])
                ) {
                    $statistics['Clerks']++;
                } elseif (
                    $className === $this->classes['auctioneer']
                    || $reflection->isSubclassOf($this->classes['auctioneer'])
                ) {
                    $statistics['Auctioneers']++;
                } else {
                    log_trace('Unknown class: ' . $className);
                }
                $statistics['Connections']++;
            } catch (ReflectionException) {
                log_error("Reflection exception thrown" . composeSuffix(['class' => $className]));
            }
        }
        log_info(composeLogData($statistics));
        $upTime = time() - $this->startTime;
        $memoryUsage = memory_get_usage(true);
        $memoryMaxUsage = memory_get_peak_usage(true);
        $this->statsSocketClient = $this->sendStats($upTime, $statistics, $memoryUsage, $memoryMaxUsage);
        log_debug(composeLogData(['Send rtb stats' => (microtime(true) - $ts) * 1000 . 'ms']));
    }

    /**
     * Function to send rtb stat to stats server
     *
     * @param int $upTime in seconds
     * @param int[] $statistics
     * @param int $memUsage
     * @param int $memMaxUsage
     * @return StatisticLegacyClient|StatisticEventClient|null
     */
    protected function sendStats(
        int $upTime,
        array $statistics,
        int $memUsage,
        int $memMaxUsage
    ): StatisticLegacyClient|StatisticEventClient|null {
        if (!$this->cfg()->get('core->rtb->stats->enabled')) {
            return null;
        }

        $domain = $this->cfg()->get('core->app->httpHost');
        $data = [
            'd' => $domain,
            'm' => $memUsage,
            'mm' => $memMaxUsage,
            't' => $upTime,
            'c' => $statistics['Connections'],
            'v' => $statistics['Viewers'],
            'b' => $statistics['Bidders'],
            'cl' => $statistics['Clerks'],
            'ac' => $statistics['Auctioneers'],
        ];

        $queryString = http_build_query($data);

        $url = '/logRtb?' . $queryString;
        $host = $this->cfg()->get('core->statsServer->host');
        $port = $this->cfg()->get('core->statsServer->port');
        $getRequest = "GET {$url} HTTP/1.0\r\nHost:{$host}\r\n\r\n";
        log_trace("Connecting to stats server" . composeSuffix([$host => $port]));
        $daemon = $this->getRtbDaemon();
        if ($daemon instanceof RtbDaemonLegacy) {
            /** @var StatisticLegacyClient $client */
            $client = $daemon->createClient(StatisticLegacyClient::class, $host, $port);
        } elseif ($daemon instanceof RtbDaemonEvent) {
            /** @var StatisticEventClient $client */
            $client = $daemon->createClient(StatisticEventClient::new(), $host, $port);
        } else {
            return null;
        }
        log_trace('Writing to stats server' . composeSuffix(['url' => $url]));
        $client->write($getRequest);
        return $client;
    }
}
