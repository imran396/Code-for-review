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

namespace Sam\Bidding\BidTransaction\Load;

use BidTransaction;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Class BidTransactionLoader
 * @package Sam\Bidding\BidTransaction\Load
 */
class BidTransactionLoader extends EntityLoaderBase
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
     * Load bids, excluding increased by owner
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $excludeFailedBids
     * @param array $options
     * @param bool $isReadOnlyDb
     * @return BidTransaction[]
     */
    public function load(
        ?int $lotItemId,
        ?int $auctionId,
        bool $excludeFailedBids = false,
        array $options = [],
        bool $isReadOnlyDb = false
    ): array {
        if (!$lotItemId || !$auctionId) {
            return [];
        }

        $rows = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterOutBidId(null)
            ->skipParentBidId(null)
            ->select(['parent_bid_id'])
            ->loadRows();
        $parentBidIds = ArrayCast::arrayColumnInt($rows, 'parent_bid_id');
        // exclude all bids, whose maximum bid has been increased
        // we find those bids in the "parent_bid_id", if the "out_bid_id" IS NULL (because
        // they did not outbid someone), and the "parent_bid_id" IS NOT NULL (if it's NULL
        // it's the first bid of an auction (because no out bid and not parent bid = first bid)

        $createdOnOrder = $options['order']['CreatedOn'] ?? false;
        $bidOrder = $options['order']['Bid'] ?? false;
        $idOrder = $options['order']['Id'] ?? false;
        $offset = $options['offset'] ?? 0;

        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->skipId($parentBidIds)
            ->orderByCreatedOn($createdOnOrder)
            ->orderByBid($bidOrder)
            ->orderById($idOrder);

        if (isset($options['limit'])) {
            $repo
                ->limit($options['limit'])
                ->offset($offset);
        }

        if ($excludeFailedBids) {
            $repo->filterFailed(false);
        }
        return $repo->loadEntities();
    }

    /**
     * @param int|null $bidTransactionId
     * @param bool $isReadOnlyDb
     * @return BidTransaction|null
     */
    public function loadById(?int $bidTransactionId, bool $isReadOnlyDb = false): ?BidTransaction
    {
        if (!$bidTransactionId) {
            return null;
        }

        // Apply $this->prepareRepository($isReadOnlyDb),
        // when we will know what to do with deleted cases of AuctionLot->CurrentBid (eg. user deleted)
        // Currently, it is exact replacement of un-conditional BidTransaction::Load()
        $bidTransaction = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($bidTransactionId)
            ->filterDeleted(false)
            ->loadEntity();
        return $bidTransaction;
    }

    /**
     * Get last active bid transaction
     *
     * @param int|null $lotItemId lot_item.id
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return BidTransaction|null
     */
    public function loadLastActiveBid(
        ?int $lotItemId,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): ?BidTransaction {
        if (!$lotItemId || !$auctionId) {
            return null;
        }

        $parentBidIdRows = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterOutBidId(null)
            ->skipParentBidId(null)
            ->select(['parent_bid_id'])
            ->loadRows();
        $skipIds = ArrayCast::arrayColumnInt($parentBidIdRows, 'parent_bid_id');

        $bidTransaction = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterFailed(false)
            ->skipId($skipIds)
            ->orderByCreatedOn(false)
            ->orderById(false)
            ->loadEntity();
        return $bidTransaction;
    }

    /**
     * Get last active bid transaction for online user
     * @param int|null $userId user.id
     * @param int|null $lotItemId lot_item.id
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return BidTransaction|null
     */
    public function loadLastUserActiveBid(
        ?int $userId,
        ?int $lotItemId,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): ?BidTransaction {
        if (!$lotItemId || !$auctionId || !$userId) {
            return null;
        }

        $bidTransactions = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterUserId($userId)
            ->filterFailed(false)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->orderByCreatedOn(false)
            ->loadEntity();
        return $bidTransactions;
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
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId([Constants\User::US_ACTIVE, null]);  // SAM-4371
        return $repo;
    }
}
