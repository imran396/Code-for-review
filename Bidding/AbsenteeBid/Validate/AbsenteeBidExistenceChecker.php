<?php
/**
 * SAM-4153: Absentee bid loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 20, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;

/**
 * Class AbsenteeBidExistenceChecker
 * @package Sam\Bidding\AbsenteeBid\Validate
 */
class AbsenteeBidExistenceChecker extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $absenteeBidId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(?int $absenteeBidId, bool $isReadOnlyDb = false): bool
    {
        if (!$absenteeBidId) {
            return false;
        }

        $isFound = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($absenteeBidId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->exist();
        return $isFound;
    }

    /**
     * Check if absentee bids exist for lot in auction
     * @param int|null $lotItemId lot_item.id
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForLot(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return false;
        }

        $isFound = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterMaxBidGreater(0)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->exist();
        return $isFound;
    }

    public function existForLotAndUser(?int $lotItemId, ?int $auctionId, ?int $userId, bool $isReadOnlyDb = false): bool
    {
        if (
            !$lotItemId
            || !$auctionId
            || !$userId
        ) {
            return false;
        }

        $isFound = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterMaxBidGreater(0)
            ->filterUserId($userId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->exist();
        return $isFound;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @return int
     */
    public function countForLot(?int $lotItemId, ?int $auctionId): int
    {
        if (!$lotItemId || !$auctionId) {
            return 0;
        }

        $count = $this->createAbsenteeBidReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterMaxBidGreater(0)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->count();
        return $count;
    }
}
