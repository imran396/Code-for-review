<?php
/**
 * Help methods for Auction Lot Cache loading
 *
 * SAM-4021: Lot Loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load;

use AuctionLotItemCache;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\EntityLoader\AuctionLotAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class AuctionLotCacheLoader
 * @package Sam\AuctionLot\Load
 */
class AuctionLotCacheLoader extends EntityLoaderBase
{
    use AuctionLotAllFilterTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;

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
        $this->initFilter();
        return $this;
    }

    /**
     * Load AuctionLotItemCache by lot_item.id and auction.id, filtered by available statuses
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function load(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        $lotItemId = Cast::toInt($lotItemId, Constants\Type::F_INT_POSITIVE);
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return null;
        }

        $repo = $this->prepareCacheRepository($isReadOnlyDb);
        $auctionLotCache = $repo
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotItemId($lotItemId)
            ->loadEntity();
        return $auctionLotCache;
    }

    /**
     * Load AuctionLotItemCache by lot_item.id and auction.id if exists, or create, calculate and save.
     * It may return `null`, when AuctionLotItem record not found according filtering conditions
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function loadOrCreate(int $lotItemId, int $auctionId, int $editorUserId, bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        $auctionLotCache = $this->load($lotItemId, $auctionId, $isReadOnlyDb);
        if (!$auctionLotCache) {
            $auctionLot = $this->prepareAuctionLotLoader()->load($lotItemId, $auctionId, $isReadOnlyDb);
            if (!$auctionLot) {
                log_error(
                    "Available auction lot item not found, when want to load its cache"
                    . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
                );
                return null;
            }
            $auctionLotCache = $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
        }
        return $auctionLotCache;
    }

    /**
     * Load AuctionLotItemCache by ali.id
     * @param int|null $auctionLotId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function loadById(?int $auctionLotId, bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        if (!$auctionLotId) {
            return null;
        }

        $fn = function () use ($auctionLotId, $isReadOnlyDb) {
            $auctionLotCache = $this->prepareCacheRepository($isReadOnlyDb)
                ->filterAuctionLotItemId($auctionLotId)
                ->loadEntity();
            return $auctionLotCache;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::AUCTION_LOT_ITEM_CACHE_AUCTION_LOT_ITEM_ID,
            $auctionLotId
        );
        $filterDescriptors = $this->collectFilterDescriptors();
        $auctionLotCache = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auctionLotCache;
    }

    /**
     * Load AuctionLotItemCache by ali.id or create and init new record
     * @param int|null $auctionLotId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function loadByIdOrCreatePersisted(?int $auctionLotId, int $editorUserId, bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        $auctionLotCache = $this->loadById($auctionLotId, $isReadOnlyDb);
        if (!$auctionLotCache) {
            $auctionLot = $this->prepareAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
            if (!$auctionLot) {
                log_error(
                    "Available auction lot item not found by id, when want to load its cache"
                    . composeSuffix(['ali' => $auctionLotId])
                );
                return null;
            }
            $auctionLotCache = $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
        }
        return $auctionLotCache;
    }

    /**
     * Return AuctionLotLoader instance with the same applied filters
     * @return AuctionLotLoader
     */
    protected function prepareAuctionLotLoader(): AuctionLotLoader
    {
        return AuctionLotLoader::new()
            ->filterAccountActive($this->getFilterAccountActive())
            ->filterAuctionStatusId($this->getFilterAuctionStatusId())
            ->filterLotItemActive($this->getFilterLotItemActive())
            ->filterLotStatusId($this->getFilterLotStatusId());
    }
}
