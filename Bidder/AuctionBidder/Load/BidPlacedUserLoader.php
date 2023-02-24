<?php
/**
 * SAM-3153: Fix links at Bidder Overview page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/1/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Class BidPlacedUserLoader
 * @package Sam\Bidder\AuctionBidder\Load
 */
class BidPlacedUserLoader extends CustomizableClass
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
     * Helping method for loading user data of active bidders in auction, who already placed bid
     * @param int $auctionId
     * @param string $auctionType
     * @return array
     */
    public function loadRows(
        int $auctionId,
        string $auctionType
    ): array {
        $rows = [];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            $select = [
                'ab.user_id',
                'aub.bidder_num',
                'u.username',
            ];
            $repository = $this->createAbsenteeBidReadRepository()
                ->filterMaxBidGreaterOrEqual(0);
            $repository = $this->initRepositoryForBidPlacedUser($repository, $auctionId, $select);
            $rows = $repository->loadRows();

            $select = [
                'bt.user_id',
                'aub.bidder_num',
                'u.username',
            ];
            $repository = $this->createBidTransactionReadRepository()
                ->filterDeleted(false);
            $repository = $this->initRepositoryForBidPlacedUser($repository, $auctionId, $select);
            $rows = array_merge($rows, $repository->loadRows());
            $rows = array_unique($rows, SORT_REGULAR);
            $bidderNums = array_column($rows, 'bidder_num');
            $usernames = array_column($rows, 'username');
            array_multisort($bidderNums, SORT_ASC, $usernames, SORT_ASC, $rows);
        } elseif ($auctionStatusPureChecker->isTimed($auctionType)) {
            $select = [
                'bt.user_id',
                'aub.bidder_num',
                'u.username',
            ];
            $repository = $this->createBidTransactionReadRepository()
                ->filterDeleted(false);
            $repository = $this->initRepositoryForBidPlacedUser($repository, $auctionId, $select);
            $rows = $repository->loadRows();
        }
        return $rows;
    }

    /**
     * @param AbsenteeBidReadRepository|BidTransactionReadRepository $repository
     * @param int $auctionId
     * @param string[] $select
     * @return AbsenteeBidReadRepository|BidTransactionReadRepository
     */
    protected function initRepositoryForBidPlacedUser(
        AbsenteeBidReadRepository|BidTransactionReadRepository $repository,
        int $auctionId,
        array $select
    ): AbsenteeBidReadRepository|BidTransactionReadRepository {
        $repository
            ->enableDistinct(true)
            ->filterAuctionId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinAuctionBidderFilterApproved(true)
            ->joinAuctionBidderSkipBidderNum(null)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->order('aub.bidder_num')
            ->order('u.username')
            ->select($select);
        return $repository;
    }
}
