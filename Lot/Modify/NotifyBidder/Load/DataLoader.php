<?php
/**
 * SAM-5631: Refactor lot modification bidder notifier
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Modify\NotifyBidder\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * This class contains methods for loading data for Notify bidder function
 *
 * Class DataLoader
 * @package Sam\Lot\Modify\NotifyBidder\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
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
     * Get user ids which bid for the lot
     * @param int $lotItemId
     * @param int $auctionId
     * @param string $auctionType
     * @return int[]
     */
    public function loadBidsUserId(int $lotItemId, int $auctionId, string $auctionType): array
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            $repository = $this->prepareRepositoryForTimedAuction($lotItemId, $auctionId);
        } else {
            $repository = $this->prepareRepositoryForLiveAndHybridAuction($lotItemId, $auctionId);
        }
        return ArrayCast::arrayColumnInt($repository->loadRows(), 'user_id');
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @return BidTransactionReadRepository
     */
    private function prepareRepositoryForTimedAuction(int $lotItemId, int $auctionId): BidTransactionReadRepository
    {
        return $this->createBidTransactionReadRepository()
            ->enableDistinct(true)
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterBidGreater(0)
            ->filterDeleted(false)
            ->filterLotItemId($lotItemId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->select(['bt.user_id']);
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @return AbsenteeBidReadRepository
     */
    private function prepareRepositoryForLiveAndHybridAuction(int $lotItemId, int $auctionId): AbsenteeBidReadRepository
    {
        return $this->createAbsenteeBidReadRepository()
            ->enableDistinct(true)
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterMaxBidGreater(0)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionBidderFilterApproved(true)
            ->joinAuctionBidderSkipBidderNum(null)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->select(['ab.user_id']);
    }
}
