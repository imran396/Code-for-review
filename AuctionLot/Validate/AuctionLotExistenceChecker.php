<?php
/**
 * Auction lot existence checker
 *
 * SAM-4497: Lot existence checkers and validators
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\AuctionLotAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class AuctionLotExistenceChecker
 * @package Sam\AuctionLot\Validate
 */
class AuctionLotExistenceChecker extends CustomizableClass
{
    use AuctionLotAllFilterTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
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
     * Check whether lot_prefix, lot_number, lot_number_ext combination exists in auction
     * @param int $auctionId Auction id
     * @param int $lotNum Lot number
     * @param string $lotNumExt Lot number extension
     * @param string $lotNumPrefix Lot number prefix
     * @param int[] $skipIds auction_lot_item.id to exclude from check
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotNo(
        int $auctionId,
        int $lotNum,
        string $lotNumExt = '',
        string $lotNumPrefix = '',
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $lotNumExt = empty($lotNumExt) ? '' : $lotNumExt;
        $lotNumPrefix = empty($lotNumPrefix) ? '' : $lotNumPrefix;
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotNum($lotNum)
            ->filterLotNumExt($lotNumExt)
            ->filterLotNumPrefix($lotNumPrefix)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Check whether an AuctionLotItem exists for an auction.id and lot_item.id among available statuses
     *
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (!$lotItemId || !$auctionId) {
            return false;
        }

        $fn = function () use ($lotItemId, $auctionId, $isReadOnlyDb) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::AUCTION_LOT_ITEM_LOT_ITEM_ID_AUCTION_ID,
            [$lotItemId, $auctionId]
        );
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }
}
