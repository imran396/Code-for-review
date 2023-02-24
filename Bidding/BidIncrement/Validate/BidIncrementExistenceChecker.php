<?php
/**
 * Help methods for Bid Increment existence checking
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 11, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Validate;

use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class BidIncrementExistenceChecker
 * @package Sam\Bidding\BidIncrement\Validate
 */
class BidIncrementExistenceChecker extends CustomizableClass
{
    use BidIncrementReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Count bid increments
     * @param int $accountId
     * @param string|null $auctionType
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return int
     */
    public function count(int $accountId, ?string $auctionType = null, ?int $auctionId = null, ?int $lotItemId = null): int
    {
        $count = $this->createBidIncrementReadRepository()
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->count();
        return $count;
    }

    /**
     * Check if an Inventory item has LotItem Increments associated with it
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForLot(?int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        if (!$lotItemId) {
            return false;
        }

        return $this->getMemoryCacheManager()->load(
            sprintf(Constants\MemoryCache::BID_INCREMENT_COUNT_LOT_ITEM_ID, $lotItemId),
            function () use ($lotItemId, $isReadOnlyDb) {
                return $this->createBidIncrementReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->filterLotItemId($lotItemId)
                    ->exist();
            }
        );
    }

    /**
     * Check whether there are auction specific increments
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForAuction(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return $this->getMemoryCacheManager()->load(
            sprintf(Constants\MemoryCache::BID_INCREMENT_COUNT_AUCTION_ID, $auctionId),
            function () use ($auctionId, $isReadOnlyDb) {
                return $this->createBidIncrementReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->filterAuctionId($auctionId)
                    ->exist();
            }
        );
    }

    /**
     * Check if bid increment exist by amount
     * @param float $amount
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAmount(float $amount, ?int $auctionId = null, ?int $lotItemId = null, ?int $accountId = null, ?string $auctionType = null, bool $isReadOnlyDb = false): bool
    {
        if ($auctionId) {
            return $this->existByAmountForAuction($amount, $auctionId, $isReadOnlyDb);
        }
        if ($lotItemId) {
            return $this->existByAmountForLot($amount, $lotItemId, $isReadOnlyDb);
        }
        if ($accountId) {
            return $this->existByAmountForAccount($amount, $accountId, $auctionType, $isReadOnlyDb);
        }
        return false;
    }

    /**
     * Check if bid increment exist by amount for lot
     * @param float $amount
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAmountForLot(float $amount, int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmount($amount)
            ->filterAuctionId(null)
            ->filterAuctionType(null)
            ->filterLotItemId($lotItemId)
            ->exist();
        return $isFound;
    }

    /**
     * Check if bid increment exist by amount for auction
     * @param float $amount
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAmountForAuction(float $amount, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmount($amount)
            ->filterAuctionId($auctionId)
            ->filterAuctionType(null)
            ->filterLotItemId(null)
            ->exist();
        return $isFound;
    }

    /**
     * Check if bid increment exist by amount for account
     * @param float $amount
     * @param int $accountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAmountForAccount(float $amount, int $accountId, string $auctionType, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmount($amount)
            ->filterAccountId($accountId)
            ->filterAuctionId(null)
            ->filterAuctionType($auctionType)
            ->filterLotItemId(null)
            ->exist();
        return $isFound;
    }
}
