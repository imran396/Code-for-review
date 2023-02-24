<?php
/**
 * Register total/active lot count changes and save changes finally
 *
 * This class follows Singleton pattern, because we need to share its state ($changings, $isEnabled) among different callers (AuctionLotItemObserver, LotItemObserver).
 * Otherwise we would repeatedly decrease deleted lot count.
 * Instead of Singleton pattern, we could make static properties or store state in shared memory with help of MemoryCacheManager. All these solutions are not good too.
 * TODO: in further we could mark counted lot items in auction with help of events and handle flushing by event handler, but this needs to be implemented.
 *
 * SAM-6042: Extract lot count updating logic for auction cache to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\LotCount;

use Sam\Auction\Cache\LotCount\Internal\Changing;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\Singleton;
use Sam\Storage\WriteRepository\Entity\AuctionCache\AuctionCacheWriteRepositoryAwareTrait;

/**
 * Class AuctionLotCountCacher
 * @package Sam\Auction\Cache
 */
class AuctionLotCountCacher extends Singleton
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionCacheWriteRepositoryAwareTrait;

    // properties
    private const PR_TOTAL_LOTS = 'TotalLots';
    private const PR_TOTAL_ACTIVE_LOTS = 'TotalActiveLots';

    /**
     * Store ids of already counted total lots to prevent double-counting for AuctionCache->TotalLots and AuctionCache->TotalActiveLots
     * We assume this service should be called once per lot per request.
     * @var int[][][]
     */
    protected array $changings = [];

    /**
     * @var bool
     */
    protected bool $isEnabled = true;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * Allows to disable counting lots by this service
     * @param bool $enabled
     * @return $this
     */
    public function enable(bool $enabled): static
    {
        $this->isEnabled = $enabled;
        return $this;
    }

    /**
     * Increase count of total lots in AuctionCache record and queue entity for further save.
     * This method used to increase/decrease lot count. It is called from observers, when related properties are changed
     *
     * @param int[] $auctionIds Change lot count for these auctions
     * @param int $count Value to add, it may be negative
     * @param int $lotItemId Lot item, which is counted
     * @return static
     */
    public function addTotalCount(array $auctionIds, int $count, int $lotItemId): static
    {
        return $this->addCount($auctionIds, $count, $lotItemId, self::PR_TOTAL_LOTS);
    }

    /**
     * Increase count of total active lots in AuctionCache record and queue entity for further save.
     * This method used to increase/decrease number of active lot. It is called from observers,
     * when related properties are changed
     *
     * @param int[] $auctionIds Change lot count for these auctions
     * @param int $count Value to add, it may be negative
     * @param int $lotItemId Lot item, which is counted
     * @return static
     */
    public function addTotalActiveCount(array $auctionIds, int $count, int $lotItemId): static
    {
        return $this->addCount($auctionIds, $count, $lotItemId, self::PR_TOTAL_ACTIVE_LOTS);
    }

    /**
     * Increase count of total (all or active) lots in AuctionCache record and queue entity for further save.
     * @param int[] $auctionIds Change lot count for these auctions
     * @param int $count Value to add, it may be negative
     * @param int $lotItemId Lot item, which is counted
     * @param string $property
     * @return static
     */
    protected function addCount(array $auctionIds, int $count, int $lotItemId, string $property): static
    {
        if (!$this->isEnabled) {
            return $this;
        }

        if (!$auctionIds) {
            return $this;
        }

        $auctionIds = ArrayCast::castInt($auctionIds);
        foreach ($auctionIds as $auctionId) {
            $this->changings[$auctionId][$lotItemId][$property] ??= new Changing($count, Changing::ST_REGISTERED);
        }
        return $this;
    }

    /**
     * Persist modified AuctionCache records
     * @param int $editorUserId
     */
    public function flush(int $editorUserId): void
    {
        if (!$this->isEnabled) {
            return;
        }

        foreach ($this->changings as $auctionId => $perLotItemIds) {
            $auctionCache = $this->getAuctionCacheLoader()->load($auctionId);
            if (!$auctionCache) {
                log_error('Available AuctionCache not found, when flushing lot total count changes' . composeSuffix(['a' => $auctionId]));
                continue;
            }
            foreach ($perLotItemIds as $lotItemId => $perProperties) {
                foreach ([self::PR_TOTAL_LOTS, self::PR_TOTAL_ACTIVE_LOTS] as $property) {
                    /** @var Changing|null $changing */
                    $changing = $perProperties[$property] ?? null;
                    if (
                        $changing
                        && $changing->status === Changing::ST_REGISTERED
                    ) {
                        $auctionCache->{$property} += $changing->delta;
                        $this->changings[$auctionId][$lotItemId][$property] = $changing->withStatus(Changing::ST_PERSISTED);
                    }
                }
            }
            $this->getAuctionCacheWriteRepository()->saveWithModifier($auctionCache, $editorUserId);
        }
    }
}
