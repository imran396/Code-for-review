<?php
/**
 * SAM-5350: Bid existence checker
 *
 * This class searches for active user's bid on lot
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\Validate;

use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;

/**
 * Class BidExistenceChecker
 * @package
 */
class BidExistenceChecker extends CustomizableClass
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
     * Return whether user has placed a bid (absentee or timed online) on a lot
     * @param int $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @param string $auctionType
     * @return bool
     */
    public function exist(int $userId, int $lotItemId, int $auctionId, string $auctionType): bool
    {
        $isLiveOrHybrid = AuctionStatusPureChecker::new()->isLiveOrHybrid($auctionType);
        if ($isLiveOrHybrid) {
            $isFound = $this->createAbsenteeBidReadRepository()
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->filterUserId($userId)
                ->joinAccountFilterActive(true)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinLotItemFilterActive(true)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->exist();
        } else {
            $isFound = $this->createBidTransactionReadRepository()
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->filterDeleted(false)
                ->filterUserId($userId)
                ->joinAccountFilterActive(true)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinLotItemFilterActive(true)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->exist();
        }
        return $isFound;
    }
}
