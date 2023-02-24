<?php
/**
 * Class implements functionality for calculating and updating multiple auction caches, that is run by cron.
 * SAM-5993: Extract multiple auction cache updating for cron to separate class
 *
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @since           Nov 17, 2012
 * @copyright       Copyright 2018 by Bidpath, Inc. All rights reserved.
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;

use AuctionCache;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCache\AuctionCacheReadRepositoryCreateTrait;

/**
 * Class AuctionDbCacheManager
 * @package Sam\Auction\Cache
 */
class MultipleAuctionCacheUpdater extends CustomizableClass
{
    use AuctionCacheReadRepositoryCreateTrait;
    use AuctionDbCacheManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;

    /**
     * Timeout defined by dbCacheTimeout in core.php
     * @var int|null
     */
    protected ?int $timeout = null;
    /**
     * Consider only caches with dropped timeout (calculated_on = null)
     * @var bool
     */
    protected bool $isOnlyDroppedTimeout = false;
    /**
     * Execution time limitations in seconds
     * @var int|null
     */
    protected ?int $maxExecTime = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * Refresh auction caches where timeout is expired or dropped
     * TODO: Current query is based on `auction_cache` table and doesn't join to `auction`,
     * thus it processes records of deleted auctions and don't create absent records.
     * @param int $editorUserId
     * @return int $count
     */
    public function update(int $editorUserId): int
    {
        $execStartTime = time();
        $count = 0;
        $timeout = $this->getTimeout();
        $maxExecTime = $this->getMaxExecTime();
        $repo = $this->createAuctionCacheReadRepository();

        //Processing auction cache items with dropped timeout
        if (!$this->isOnlyDroppedTimeout()) {
            $repo->setChunkSize(200);
            $timeoutExpireTimestamp = $this->getCurrentDateUtc()->getTimestamp() - $timeout;
            $timeoutExpireDate = $this->getCurrentDateUtc()
                ->setTimestamp($timeoutExpireTimestamp)
                ->format(Constants\Date::ISO);
            $repo->filterCalculatedOnLess($timeoutExpireDate);

            while ($auctionCacheItems = $repo->loadEntities()) {
                $this->refreshArray($auctionCacheItems, $editorUserId, 10);
                $count += count($auctionCacheItems);

                //If we run from cron script, then we have execution time limitation
                if ($maxExecTime && time() > ($execStartTime + $maxExecTime)) {
                    return $count;
                }
            }
        }

        $repo = $this->createAuctionCacheReadRepository();
        //Processing auction cache items with dropped calculated_on column
        $repo->filterCalculatedOn(null)
            ->setChunkSize(200);

        while ($auctionCacheItems = $repo->loadEntities()) {
            $this->refreshArray($auctionCacheItems, $editorUserId, 10);
            $count += count($auctionCacheItems);

            //If we run from cron script, then we have execution time limitation
            if (
                $maxExecTime
                && time() > ($execStartTime + $maxExecTime)
            ) {
                return $count;
            }
        }

        $refreshBy = $this->isOnlyDroppedTimeout ? "for dropped timeout" : "by timeout ({$timeout}s)";
        log_debug("Refreshed auction cache $refreshBy of $count auctions");

        return $count;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        if ($this->timeout === null) {
            $this->timeout = $this->cfg()->get('core->auction->dbCacheTimeout');
        }
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyDroppedTimeout(): bool
    {
        return $this->isOnlyDroppedTimeout;
    }

    /**
     * @param bool $onlyDroppedTimeout
     * @return $this
     */
    public function enableOnlyDroppedTimeout(bool $onlyDroppedTimeout): static
    {
        $this->isOnlyDroppedTimeout = $onlyDroppedTimeout;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxExecTime(): ?int
    {
        if ($this->maxExecTime === null) {
            $this->maxExecTime = $this->cfg()->get('core->auction->dbCacheUpdateMaxExecutionTime');
        }
        return $this->maxExecTime;
    }

    /**
     * @param int $maxExecTime
     * @return $this
     */
    public function setMaxExecTime(int $maxExecTime): static
    {
        $this->maxExecTime = $maxExecTime;
        return $this;
    }

    /**
     * Refresh cached values
     *
     * @param AuctionCache[] $auctionCaches
     * @param int $editorUserId
     * @param int|null $chunkSize - process array by portions with the size, null - whole array at once
     * @return void
     */
    protected function refreshArray(array $auctionCaches, int $editorUserId, ?int $chunkSize = null): void
    {
        if (empty($auctionCaches)) {
            return;
        }
        if ($chunkSize === null) {
            $chunkSize = count($auctionCaches);
        }
        $auctionCacheManager = $this->getAuctionDbCacheManager();
        for ($i = 0, $count = count($auctionCaches); $i < $count; $i += $chunkSize) {
            $chunkedAuctionCaches = array_slice($auctionCaches, $i, $chunkSize);
            $auctionIds = [];
            foreach ($chunkedAuctionCaches as $auctionCache) {
                $auctionIds[] = $auctionCache->AuctionId;
            }
            $values = $auctionCacheManager->detectValues($auctionIds);
            $auctionCacheManager->update($chunkedAuctionCaches, $editorUserId, $values);
        }
    }
}
