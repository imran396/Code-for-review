<?php
/**
 * SAM-3224: Refactoring of auction_closer.php
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Close\Cron;

use Sam\Auction\Close\AuctionCloser;
use Sam\Auction\Close\Live\LiveAuctionCloser;
use Sam\Auction\Open\AuctionOpener;
use Sam\AuctionLot\Closer\TimedCloser;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class Processor
 * @package Sam\Auction\Close\Cron
 */
class Processor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use UserLoaderAwareTrait;

    protected ?int $maxExecTime = null;
    protected ?int $timedCloserDelay = null;
    protected float $startTs = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function process(): void
    {
        $this->startTs = microtime(true);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->closeAuctions($editorUserId);
        $this->openAuctions($editorUserId);
        $this->closeTimedLots();
    }

    /**
     * @return int
     */
    public function getMaxExecTime(): int
    {
        if ($this->maxExecTime === null) {
            $this->maxExecTime = $this->cfg()->get('core->auction->closer->executionTime');
        }
        return $this->maxExecTime;
    }

    /**
     * @param int $time
     * @return static
     */
    public function setMaxExecTime(int $time): static
    {
        $this->maxExecTime = Cast::toInt($time, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int
     */
    public function getTimedCloserDelay(): int
    {
        if ($this->timedCloserDelay === null) {
            $this->timedCloserDelay = $this->cfg()->get('core->auction->closer->timedCloserDelay');
        }
        return $this->timedCloserDelay;
    }

    /**
     * @param int $timedCloserDelay
     * @return static
     * @noinspection PhpUnused
     */
    public function setTimedCloserDelay(int $timedCloserDelay): static
    {
        $this->timedCloserDelay = $timedCloserDelay;
        return $this;
    }

    protected function closeAuctions(int $editorUserId): void
    {
        $closer = AuctionCloser::new();
        $closer->closeTimedAuctions($editorUserId);
        LiveAuctionCloser::new()->close($editorUserId);
    }

    protected function openAuctions(int $editorUserId): void
    {
        $opener = AuctionOpener::new();
        $opener->startTimedAuctions($editorUserId);
    }

    protected function closeTimedLots(): void
    {
        $sleepSeconds = $this->getTimedCloserDelay();
        if ($this->getTimedCloserDelay()) {
            // TB: sleep will allow other processes to run and not use CPU resources, and then close timed lots for up to x seconds
            log_debug("Sleep thread running for {$sleepSeconds} seconds...");
            sleep($this->getTimedCloserDelay());
        }
        $maxExecTime = $this->getMaxExecTime() - floor(microtime(true) - $this->startTs);
        $timedCloser = TimedCloser::new();
        // Don't define scheduled lot end time: ->setBidDateUtc($closeDateGmt)
        // TB: This will result in the cron closing lots on the minute after the lots closed and one minute longer “Loading…” on the screen.
        // $closeDateGmt = $this->getCurrentDateUtc()->sub(new DateInterval('PT10S'));
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $timedCloser->close($editorUserId, (int)$maxExecTime);
    }
}
