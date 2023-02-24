<?php

namespace Sam\Rtb;

use Auction;
use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Html\HtmlEntityTransformer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdUpdaterAwareTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class GeneralHelper
 * @package Sam\Rtb
 */
class GeneralHelper extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionRtbdUpdaterAwareTrait;
    use ConfigRepositoryAwareTrait;
    use GroupingHelperAwareTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Public uri to rtbd server
     * @param int $userType
     * @param int|null $auctionId
     * @return string
     */
    public function getRtbdUri(int $userType, ?int $auctionId = null): string
    {
        $scheme = $this->cfg()->get('core->rtb->server->wss') ? 'wss' : 'ws';
        $uri = $scheme . "://" . $this->getRtbUriWithoutScheme($userType, $auctionId);
        return $uri;
    }

    /**
     * @param int $userType
     * @param int|null $auctionId
     * @return string
     */
    protected function getRtbUriWithoutScheme(int $userType, ?int $auctionId = null): string
    {
        if ($this->getRtbdPoolFeatureAvailabilityValidator()->isAvailable()) {
            $updater = $this->getAuctionRtbdUpdater()
                ->setAuctionId($auctionId)
                ->updateBySuggestedAndPersist();
            $rtbdName = $updater->getRtbdName();
            $descriptor = $this->getRtbdPoolConfigManager()
                ->setUserType($userType)
                ->getDescriptorByName($rtbdName);
            if ($descriptor) {
                $publicHost = $descriptor->getPublicHost();
                $publicPort = $descriptor->getPublicPort();
                $publicPath = $descriptor->getPublicPath();
            } else {
                log_error('Rtbd instance descriptor cannot be found' . composeSuffix(['a' => $auctionId]));
                return '';
            }
        } else {
            $publicHost = $this->getPublicHost();
            $publicPort = $this->getPublicPort();
            $publicPath = $this->getPublicPath($userType);
        }
        return $publicHost . ":" . $publicPort . $publicPath;
    }

    /**
     * @param int $userType
     * @return string
     */
    public function getPublicPath(int $userType): string
    {
        $path = $this->makePublicPath($this->cfg()->get('core->rtb->server->publicPath'), $userType);
        return $path;
    }

    /**
     * @param string $path
     * @param int $userType
     * @return string
     */
    public function makePublicPath(string $path, int $userType): string
    {
        $path = trim($path);
        $path = '/' . $path;
        if (
            $userType === Constants\Rtb::UT_VIEWER
            && $this->cfg()->get('core->rtb->rtbdViewerRepeater->enabled')
        ) {
            $path .= '/viewer';
        }
        $path = preg_replace('|//|', '/', $path);
        return $path;
    }

    /**
     * Return rtbd bind address
     * @return string
     */
    public function getBindHost(): string
    {
        $bindHost = $this->cfg()->get('core->rtb->server->bindHost') ?: $this->getPublicHost();
        return $bindHost;
    }

    /**
     * Return rtbd bind port
     * @return int
     */
    public function getBindPort(): int
    {
        $bindPort = (int)$this->cfg()->get('core->rtb->server->bindPort') ?: $this->getPublicPort();
        return $bindPort;
    }

    /**
     * Return rtbd public address
     * @return string
     */
    public function getPublicHost(): string
    {
        $publicHost = $this->cfg()->get('core->rtb->server->publicHost') ?: $this->cfg()->get('core->app->httpHost');
        return $publicHost;
    }

    /**
     * Return rtbd public port
     * @return int
     */
    public function getPublicPort(): int
    {
        $publicHost = (int)$this->cfg()->get('core->rtb->server->publicPort') ?: 80;
        return $publicHost;
    }

    /**
     * Check, if lot is running now or is assigned to running group
     * @param int $auctionId
     * @param int $lotItemId
     * @return bool
     */
    public function isRunningLot(int $auctionId, int $lotItemId): bool
    {
        $isRunning = $isRunningGroup = false;
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when detecting running lot"
                . composeSuffix(['a' => $auctionId, 'li' => $lotItemId])
            );
            return false;
        }

        if ($auction->isLiveOrHybrid()) {
            if ($auction->isStartedOrPaused()) {
                $rtbCurrent = $this->loadRtbCurrentOrCreate($auction);
                if ($rtbCurrent->LotGroup) {
                    $rtbCurrentGroupRecords = $this->getGroupingHelper()->loadGroups($auction->Id);
                    foreach ($rtbCurrentGroupRecords as $rtbCurrentGroup) {
                        if ($lotItemId === $rtbCurrentGroup->LotItemId) {
                            $isRunningGroup = true;
                            break;
                        }
                    }
                } else {
                    $isRunning = $lotItemId === $rtbCurrent->LotItemId;
                }
                $isRunning = $isRunning || $isRunningGroup;
                $inRunningGroup = $isRunningGroup ? ' in group' : '';
                log_debug(
                    "Check lot started in rtb{$inRunningGroup}"
                    . composeSuffix(
                        [
                            'li' => $lotItemId,
                            'a' => $auctionId,
                            'rtbc.li' => $rtbCurrent->LotItemId,
                            'is running' => $isRunning ? 'yes' : 'no',
                        ]
                    )
                );
            }
        }
        return $isRunning;
    }

    /**
     * Check UTF-8 encoding and encode entities
     *
     * @param string $input string to check and encode
     * @return string encoded or #ERR#
     */
    public function clean(string $input): string
    {
        if (mb_check_encoding($input, 'UTF-8') === false) {
            // give it one chance and cut off the last character
            // because it seems that sometimes we have half multibyte
            // characters at the end of a string
            // NOTE: this uses substr and not mb_substr to only
            // remove a 1 byte character at the end of the string
            $input = substr($input, 0, -1);
            if (mb_check_encoding($input, 'UTF-8') === false) {
                return Constants\TextTransform::CHARACTER_ENCODING_ERROR_MARKER;
            }
        }

        $output = HtmlEntityTransformer::new()->toHtmlEntity($input);
        return $output;
    }

    /**
     * Load RtbCurrent for auction. Create and init, if it doesn't exist, but don't persist.
     * @param Auction $auction
     * @return RtbCurrent
     */
    public function loadRtbCurrentOrCreate(Auction $auction): RtbCurrent
    {
        $commandHelper = RtbFactory::new()->createCommandHelper($auction->AuctionType);
        $rtbCurrent = $commandHelper->loadRtbCurrentOrCreate($auction);
        return $rtbCurrent;
    }

    /**
     * Check if rtbd instance running
     * @param string $host
     * @param int|null $port
     * @return bool
     */
    public function isRtbdRunning(string $host, ?int $port): bool
    {
        $filePointer = @fsockopen($host, $port, $errno, $errstr, 10);
        return (bool)$filePointer;
    }

    /**
     * @param mixed $client
     * @return bool
     */
    public function checkSocketClient(mixed $client): bool
    {
        return $client instanceof EventClient
            || $client instanceof LegacyClient;
    }

    /**
     * Ping uri to rtbd server
     * @param int $userType
     * @param int|null $auctionId
     * @return string
     */
    public function getRtbPingUri(int $userType, ?int $auctionId = null): string
    {
        $scheme = $this->cfg()->get('core->rtb->server->wss') ? 'https' : 'http';
        return $scheme . "://" . $this->getRtbUriWithoutScheme($userType, $auctionId) . 'ping';
    }
}
