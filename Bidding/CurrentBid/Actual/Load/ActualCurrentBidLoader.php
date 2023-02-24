<?php
/**
 * SAM-5620: Refactoring and unit tests for Actual Current Bid Detector module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid\Actual\Load;

use AuctionLotItem;
use BidTransaction;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Contains methods for loading bid transactions
 *
 * Class ActualCurrentBidLoader
 * @package Sam\Bidding\CurrentBid\Actual\Load
 */
class ActualCurrentBidLoader extends CustomizableClass
{
    use BidTransactionReadRepositoryCreateTrait;

    /**
     * @var BidTransaction[]
     */
    private array $currentBids = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns bid transaction from local cache or loads from repository
     *
     * @param int $currentBidId
     * @return BidTransaction|null
     */
    public function loadCurrentBid(int $currentBidId): ?BidTransaction
    {
        if (!array_key_exists($currentBidId, $this->currentBids)) {
            $this->currentBids[$currentBidId] = $this->loadBidTransactionById($currentBidId);
        }
        return $this->currentBids[$currentBidId];
    }

    /**
     * Load last active bid transaction from the active user
     *
     * @param AuctionLotItem $auctionLot
     * @return BidTransaction|null
     */
    public function loadActualBidForTimed(AuctionLotItem $auctionLot): ?BidTransaction
    {
        return $this
            ->prepareActualBidForTimedRepository(
                $auctionLot->AuctionId,
                $auctionLot->LotItemId
            )
            ->loadEntity();
    }

    /**
     * @param int $id
     * @return BidTransaction|null
     */
    private function loadBidTransactionById(int $id): ?BidTransaction
    {
        return $this->createBidTransactionReadRepository()
            ->filterId($id)
            ->loadEntity();
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return BidTransactionReadRepository
     */
    private function prepareActualBidForTimedRepository(int $auctionId, int $lotItemId): BidTransactionReadRepository
    {
        $repository = $this->createBidTransactionReadRepository();
        $repository
            ->filterAuctionId($auctionId)
            ->filterDeleted(false)
            ->filterFailed(false)
            ->filterLotItemId($lotItemId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            // We still should consider BLK, NAA users (SAM-6751)
            // ->joinUserSkipFlag([Constants\User::FLAG_NOAUCTIONAPPROVAL, Constants\User::FLAG_BLOCK])
            ->orderByCreatedOn(false);
        return $repository;
    }
}
