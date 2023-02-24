<?php
/**
 * SAM-4974: Move asking bid calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AskingBid;

use InvalidArgumentException;
use QMySqli5DatabaseResult;
use RuntimeException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class AskingBidDetector
 * @package Sam\Bidding\AskingBid
 */
class AskingBidDetector extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use BidIncrementExistenceCheckerAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use HighBidDetectorCreateTrait;
    use DbConnectionTrait;
    use LotItemLoaderAwareTrait;
    use NextBidCalculatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate current asking bid for a LotItem in an Auction.
     * Allows to pass a manually entered increment
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @param float|null $currentBidAmount optional. Will be queried based on $lotItemId and $auctionId if left out
     * @return float|null Asking bid. Null for any (current bid not exist) or for non-possible asking bid (current bid already is "0" for reverse auction)
     */
    public function detectAskingBid(int $lotItemId, int $auctionId, ?float $currentBidAmount = null): ?float
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$lotItem || !$auction) {
            log_error(
                "Cannot calculate asking bid, invalid arguments"
                . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
            return null;
        }

        $currentBidAmount = $currentBidAmount ?? $this->createHighBidDetector()->detectAmount($lotItemId, $auctionId);

        $askingBid = null;
        if ($currentBidAmount !== null) {
            $askingBid = $this->detectQuantizedBid($currentBidAmount, true, $lotItemId, $auctionId);
        } elseif (Floating::gt($lotItem->StartingBid, 0.)) {
            $askingBid = $lotItem->StartingBid;
        } elseif (!$auction->Reverse) {
            $bidIncrement = $this->getBidIncrementLoader()
                ->loadAvailable(0., $lotItem->AccountId, $auction->AuctionType, $auctionId, $lotItemId);
            $askingBid = $bidIncrement->Increment ?? null;
        }
        return $askingBid;
    }

    /**
     * Calculate next on-increment bid with consideration of auction direction (forward or reverse)
     * It is used for Timed and Live bid (Simple Clerking, no manual increment), Absentee Bid calculations
     * TODO: replace calculation by mysql query with calculation by formula NextBidCalculator::calcForward(), benchmark difference
     *
     * @param float|null $currentBid the value to be quantized
     * @param bool $isNextBid defaults false. True if calculate next (asking) bid
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param float|null $startingBid
     * @param bool $isReadOnlyDb
     * @return float next lower or equal on increment bid
     */
    public function detectQuantizedBid(
        ?float $currentBid,
        bool $isNextBid,
        ?int $lotItemId = null,
        ?int $auctionId = null,
        ?float $startingBid = null,
        bool $isReadOnlyDb = false
    ): float {
        $currentBid = (float)$currentBid;
        $startingBid = (float)$startingBid;

        // If first bid equals starting bid it can be out of increment
        // FYI: $startingBid argument currently is passed only in \Lot_Bidding::placeTimedBid()
        // and in \Sam\Bidding\CurrentAbsenteeBid\Calculator::init() at the moment
        if (
            Floating::eq($currentBid, $startingBid)
            && !$isNextBid
        ) {
            return $currentBid;
        }

        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        if (!$auction) {
            throw new InvalidArgumentException(
                "Available auction not found, when calculating quantized bid"
                . composeSuffix(['a' => $auctionId, 'amount' => $currentBid])
            );
        }
        $accountId = $auction->AccountId;
        $auctionType = $auction->AuctionType;
        $auctionReverse = $auction->Reverse;

        if ($lotItemId > 0
            && $this->getBidIncrementExistenceChecker()->existForLot($lotItemId, $isReadOnlyDb)
        ) {
            $tableLevel = Constants\Increment::LEVEL_LOT;
        } elseif ($auctionId > 0
            && $this->getBidIncrementExistenceChecker()->existForAuction($auctionId, $isReadOnlyDb)
        ) {
            $tableLevel = Constants\Increment::LEVEL_AUCTION;
        } else {
            $tableLevel = Constants\Increment::LEVEL_ACCOUNT;
        }

        $currentBidEsc = $this->escape($currentBid);
        $auctionTypeEsc = $this->escape($auctionType);
        $accountIdEsc = $this->escape($accountId);
        $auctionIdEsc = $this->escape($auctionId);
        $lotItemIdEsc = $this->escape($lotItemId);

        if ($tableLevel === Constants\Increment::LEVEL_LOT) {
            $condition = " AND lot_item_id = {$lotItemIdEsc}";
        } elseif ($tableLevel === Constants\Increment::LEVEL_AUCTION) {
            $condition = " AND auction_id = {$auctionIdEsc}";
        } else { // Constants\Increment::LEVEL_ACCOUNT
            $condition = " AND auction_type = {$auctionTypeEsc}";
        }
        $n = "\n";
        if (!$auctionReverse) {
            if ($isNextBid) {
                $query = "SELECT " . $n .
                    "@next := CAST({$currentBidEsc} + (SELECT increment FROM bid_increment WHERE account_id={$accountIdEsc} {$condition} AND {$currentBidEsc} >= amount ORDER BY amount DESC LIMIT 1) AS DECIMAL(20,4)) AS next, " . $n .
                    "@nextbid := (SELECT FLOOR((@next - amount)/increment)*increment + amount FROM bid_increment WHERE account_id={$accountIdEsc} $condition AND @next >= amount ORDER BY amount DESC LIMIT 1) AS nextbid, " . $n .
                    "@nextamount := (SELECT amount FROM bid_increment WHERE account_id={$accountIdEsc} {$condition} AND amount > {$currentBidEsc} ORDER BY amount ASC LIMIT 1) AS nextamount, " . $n .
                    "(IF (CAST(@nextbid AS DECIMAL(20,4)) > CAST(@nextamount AS DECIMAL(20,4)), CAST(@nextamount AS DECIMAL(20,4)), CAST(@nextbid AS DECIMAL(20,4)))) AS quantized; ";
            } else {
                $query = "SELECT " .
                    "FLOOR(CAST(({$currentBidEsc}-amount) AS DECIMAL(20,4))/increment)*increment + amount AS quantized " . $n .
                    "FROM bid_increment " . $n .
                    "WHERE account_id={$accountIdEsc} {$condition} AND {$currentBidEsc} >= amount " . $n .
                    "ORDER BY amount DESC " . $n .
                    "LIMIT 1";
            }
        } else {
            if ($isNextBid) {
                $query = "SELECT " . $n .
                    "@next := CAST({$currentBidEsc} - (SELECT increment FROM bid_increment WHERE account_id={$accountIdEsc} {$condition} AND {$currentBidEsc} > amount ORDER BY amount DESC LIMIT 1) AS DECIMAL(20,4)) AS next, " . $n .
                    "@nextbid := (SELECT CEIL((@next + amount)/increment)*increment - amount FROM bid_increment WHERE account_id={$accountIdEsc} $condition AND @next >= amount ORDER BY amount DESC LIMIT 1) AS nextbid, " . $n .
                    "@nextamount := (SELECT amount FROM bid_increment WHERE account_id={$accountIdEsc} {$condition} AND amount < {$currentBidEsc} ORDER BY amount DESC LIMIT 1) AS nextamount, " . $n .
                    "(IF (CAST(@nextbid AS DECIMAL(20,4)) < CAST(@nextamount AS DECIMAL(20,4)), CAST(@nextamount AS DECIMAL(20,4)), CAST(@nextbid AS DECIMAL(20,4)))) AS quantized; ";
            } else {
                $query = "SELECT " . $n .
                    "CEIL(CAST(({$currentBidEsc}+amount) AS DECIMAL(20,4))/increment)*increment - amount AS quantized " . $n .
                    "FROM bid_increment " . $n .
                    "WHERE account_id={$accountIdEsc} {$condition} AND {$currentBidEsc} >= amount " . $n .
                    "ORDER BY amount DESC " . $n .
                    "LIMIT 1";
            }
        }

        $this->query($query, $isReadOnlyDb);
        if ($row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $quantizedBid = (float)$row['quantized'];
            log_trace(static fn() => 'Quantized bid calculated by sql query by increment table'
                . composeSuffix(
                    [
                        'quantized' => $quantizedBid,
                        'current' => $currentBid,
                        'is next' => $isNextBid,
                        'table' => Constants\Increment::$levelNames[$tableLevel],
                        'li' => $lotItemId,
                        'a' => $auctionId,
                    ]
                )
            );
            return $quantizedBid;
        }

        throw new RuntimeException("Failed to calculate quantized bid value for $currentBid");
    }

    // /**
    //  * @param float $currentBid
    //  * @param float $runningIncrement
    //  * @param null $lotItemId
    //  * @param null $auctionId
    //  * @return float|int|null
    //  */
    // public function detectQuantizedAskingBidByFormula($currentBid, $runningIncrement = null, $lotItemId = null, $auctionId = null)
    // {
    //     $askingBid = null;
    //     $auction = $this->getAuctionLoader()->load($auctionId);
    //     if ($auction) {
    //         // Detect min range amount for current bid (increment value of this range may differ to $runningIncrement, but we don't care)
    //         $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
    //             $currentBid,
    //             $auction->AccountId,
    //             $auction->AuctionType,
    //             $auction->Id,
    //             $lotItemId
    //         );
    //         $minRange = $bidIncrement ? $bidIncrement->Amount : 0.;
    //         $increment = $runningIncrement ?: $bidIncrement->Increment;
    //         $nextBidIncrement = $this->getBidIncrementLoader()->loadNextAvailable(
    //             $currentBid,
    //             $auction->AccountId,
    //             $auction->AuctionType,
    //             $auction->Id,
    //             $lotItemId
    //         );
    //         $nextRangeStart = $nextBidIncrement ? $nextBidIncrement->Amount : null;
    //         $askingBid = $this->calcQuantizedAskingBidByForwardFormula($currentBid, $increment, $minRange, $nextRangeStart);
    //     }
    //     return $askingBid;
    // }

}
