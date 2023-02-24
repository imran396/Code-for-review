<?php
/**
 * SAM-5394: High bid detector
 *
 * TODO: Benchmark, if HighBidDetector::detectAmount() logic is faster, than BidTransactionLoader::loadLastActiveBid()
 * adjust detectAmount() or detectUserId() to optimal loading way
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid;

use Sam\Core\Service\CustomizableClass;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;

/**
 * Class CurrentBidDetector
 * @package Sa,
 */
class HighBidDetector extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidTransactionExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Find current bid amount for auction lot
     * @param int|null $lotItemId lot_item.id - null results to null
     * @param int|null $auctionId auction.id - null results to null
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function detectAmount(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?float
    {
        $amount = null;
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, $isReadOnlyDb);
        if (
            $auctionLot
            && $auctionLot->CurrentBidId > 0
            && $this->createBidTransactionExistenceChecker()->exist(null, $lotItemId, $auctionId, $isReadOnlyDb)
        ) {
            $auctionLotCurrentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId, $isReadOnlyDb);
            $amount = $auctionLotCurrentBid->Bid ?? null;
        }
        return $amount;
    }

    /**
     * Find user id of current bid owner for auction lot
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectUserId(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $bidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($lotItemId, $auctionId, $isReadOnlyDb);
        $userId = $bidTransaction && $bidTransaction->UserId > 0 ? $bidTransaction->UserId : null;
        return $userId;
    }
}
