<?php
/**
 * SAM-4378: BidTransaction loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 9, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Class BidTransactionExistenceChecker
 * @package Sam\Bidding\BidTransaction\Validate
 */
class BidTransactionExistenceChecker extends CustomizableClass
{
    use BidTransactionReadRepositoryCreateTrait;

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
     * @param int $auctionId
     * @param bool $excludeFailedBids
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function count(
        int $lotItemId,
        int $auctionId,
        bool $excludeFailedBids = false,
        bool $isReadOnlyDb = false
    ): int {
        $parentBidIdRows = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterOutBidId(null)
            ->skipParentBidId(null)
            ->select(['parent_bid_id'])
            ->loadRows();
        $skipIds = ArrayCast::arrayColumnInt($parentBidIdRows, 'parent_bid_id');
        // exclude all bids, whose maximum bid has been increased
        // we find those bids in the "parent_bid_id", if the "out_bid_id" IS NULL (because
        // they did not outbid someone), and the "parent_bid_id" IS NOT NULL (if it's NULL
        // it's the first bid of an auction (because no out bid and not parent bid = first bid)
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->skipId($skipIds);
        if ($excludeFailedBids) {
            $repo->filterFailed(false);
        }
        $count = $repo->count();
        return $count;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countAll(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): int
    {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterDeleted(false);
        $count = $repo->count();
        return $count;
    }

    /**
     * General existence checking function
     * @param int|null $userId
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(?int $userId = null, ?int $lotItemId = null, ?int $auctionId = null, bool $isReadOnlyDb = false): bool
    {
        $repo = $this->prepareRepository($isReadOnlyDb);
        if ($userId !== null) {
            $repo->filterUserId($userId);
        }
        if ($lotItemId !== null) {
            $repo->filterLotItemId($lotItemId);
        }
        if ($auctionId !== null) {
            $repo->filterAuctionId($auctionId);
        }
        $isFound = $repo->exist();
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return BidTransactionReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): BidTransactionReadRepository
    {
        $repo = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterDeleted(false)
            ->filterFailed(false)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            // we want to consider floor bids, hence we add filtering by NULL
            // SAM-4371 TODO: there should be expr: (u.user_status_id = ... OR (u.user_status_id IS NULL and bt.floor_bidder)
            ->joinUserFilterUserStatusId([Constants\User::US_ACTIVE, null]);
        return $repo;
    }
}
