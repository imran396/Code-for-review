<?php
/**
 * SAM-5658: Multiple Auction Reorderer for lots
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction\Load;

use Auction;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class LotItemAuctionsLoader
 * @package Sam\AuctionLot\Order\Reorder\Auction\Load
 */
class DataLoader extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * @var array
     */
    private array $lotItemAuctionsIds = [];
    /**
     * @var array
     */
    private array $lotItemAuctions = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId
     * @return array
     */
    public function loadAuctionIdsByLotItemId(int $lotItemId): array
    {
        if ($this->lotItemAuctionsIds) {
            return $this->lotItemAuctionsIds;
        }

        foreach ($this->loadAuctions($lotItemId) as $auction) {
            $this->lotItemAuctionsIds[] = $auction->Id;
        }
        return $this->lotItemAuctionsIds;
    }

    /**
     * Load auctions where lot item presents
     * @param int $lotItemId
     * @return Auction[]|array
     */
    public function loadAuctionsByLotItemId(int $lotItemId): array
    {
        if (!array_key_exists($lotItemId, $this->lotItemAuctions)) {
            $this->lotItemAuctions[$lotItemId] = $this->loadAuctions($lotItemId);
        }
        return $this->lotItemAuctions[$lotItemId];
    }

    /**
     * @param int $lotItemId
     * @return Auction[]
     */
    private function loadAuctions(int $lotItemId): array
    {
        $fn = function () use ($lotItemId) {
            return $this->createAuctionReadRepository()
                ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinAuctionLotItemFilterLotItemId($lotItemId)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->loadEntities();
        };

        $cacheKey = sprintf(Constants\MemoryCache::AUCTIONS_LOT_ITEM_ID, $lotItemId);
        return $this->getMemoryCacheManager()->load($cacheKey, $fn);
    }
}
