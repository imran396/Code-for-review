<?php
/**
 * Help methods for TimedOnlineItem loading
 *
 * SAM-4021: Lot Loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load;

use Exception;
use Sam\AuctionLot\Save\TimedItemProducerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\TimedOnlineItem\TimedOnlineItemReadRepository;
use Sam\Storage\ReadRepository\Entity\TimedOnlineItem\TimedOnlineItemReadRepositoryCreateTrait;
use TimedOnlineItem;

/**
 * Class TimedItemLoader
 * @package Sam\AuctionLot\Load
 */
class TimedItemLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use TimedItemProducerCreateTrait;
    use TimedOnlineItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load TimedItem
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItem|null
     */
    public function load(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?TimedOnlineItem
    {
        if (!$lotItemId || !$auctionId) {
            return null;
        }

        $fn = function () use ($lotItemId, $auctionId, $isReadOnlyDb) {
            $timedItem = $this->prepareRepository($isReadOnlyDb)
                ->filterLotItemId($lotItemId)
                ->filterAuctionId($auctionId)
                ->loadEntity();
            return $timedItem;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::TIMED_ONLINE_ITEM_LOT_ITEM_ID_AUCTION_ID,
            [$lotItemId, $auctionId]
        );
        $timedItem = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);
        return $timedItem;
    }

    /**
     * @param int|null $timedItemId
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItem|null
     */
    public function loadById(?int $timedItemId, bool $isReadOnlyDb = false): ?TimedOnlineItem
    {
        if (!$timedItemId) {
            return null;
        }

        $fn = function () use ($timedItemId, $isReadOnlyDb) {
            $timedItem = $this->prepareRepository($isReadOnlyDb)
                ->filterId($timedItemId)
                ->loadEntity();
            return $timedItem;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::TIMED_ONLINE_ITEM_ID, $timedItemId);
        $timedItem = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);
        return $timedItem;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItem
     * @throws Exception
     */
    public function loadOrCreate(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): TimedOnlineItem
    {
        $timedItem = $this->load($lotItemId, $auctionId, $isReadOnlyDb);
        if (!$timedItem) {
            try {
                $timedItem = $this->createTimedItemProducer()->createIfLotAvailable($lotItemId, $auctionId);
                log_debug(
                    'TimedOnlineItem record did not exist, new record created'
                    . composeSuffix(['li' => $lotItemId, 'a' => $auctionId, 'toi' => $timedItem->Id])
                );
            } catch (Exception $e) {
                log_error('Cannot create TimedOnlineItem. ' . $e->getCode() . ' - ' . $e->getMessage());
                throw $e;
            }
        }
        return $timedItem;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): TimedOnlineItemReadRepository
    {
        $repo = $this->createTimedOnlineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        return $repo;
    }
}
