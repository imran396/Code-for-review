<?php
/**
 * SAM-5095: High Absentee Bid Detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Detect;

use AbsenteeBid;
use Exception;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class HighAbsenteeBidDetector
 * @package Sam\Bidding\Detect
 */
class HighAbsenteeBidDetector extends CustomizableClass
{
    use AskingBidDetectorCreateTrait;
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use DbConnectionTrait;

    /**
     * @var AbsenteeBid[]
     */
    protected array $outstandingExceedAbsenteeBids = [];

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
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid|null
     */
    public function detectFirstHigh(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): ?AbsenteeBid
    {
        [$highAbsentee,] = $this->detectTwoHighest($lotItemId, $auctionId, $isReadOnlyDb);
        return $highAbsentee;
    }

    /**
     * Detect first and second highest absentee bids.
     * It returns as array [highBid1, highBid2]
     * Fixed for SAM-3110
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array{0: AbsenteeBid|null, 1: AbsenteeBid|null}
     */
    public function detectTwoHighest(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $lotItemId = Cast::toInt($lotItemId, Constants\Type::F_INT_POSITIVE);
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        $abtRegular = Constants\Bid::ABT_REGULAR;
        $usActive = Constants\User::US_ACTIVE;
        $flagNone = Constants\User::FLAG_NONE;
        // @formatter:off
        $query = <<<SQL
SELECT ab.*,
       @amount := (
           SELECT bi.amount
           FROM bid_increment bi
           WHERE bi.account_id = a.account_id
             AND (bi.lot_item_id = ab.lot_item_id
               OR bi.auction_id = ab.auction_id
               OR bi.auction_type = a.auction_type)
             AND bi.amount <= ab.max_bid
           ORDER BY bi.lot_item_id DESC,
                    bi.auction_id DESC,
                    bi.auction_type DESC,
                    bi.amount DESC
           LIMIT 1
       ) amount,
       @increment := (
           SELECT bi.increment
           FROM bid_increment bi
           WHERE bi.account_id = a.account_id
             AND (bi.lot_item_id = ab.lot_item_id
               OR bi.auction_id = ab.auction_id
               OR bi.auction_type = a.auction_type
               )
             AND bi.amount <= ab.max_bid
           ORDER BY bi.lot_item_id DESC,
                    bi.auction_id DESC,
                    bi.auction_type DESC,
                    bi.amount DESC
           LIMIT 1
       ) increment,
       @amount + FLOOR((ab.max_bid - @amount) / @increment) * @increment on_increment
FROM absentee_bid ab
    LEFT JOIN auction a ON a.id = ab.auction_id
    INNER JOIN `user` u
        ON u.id = ab.user_id AND u.user_status_id = {$usActive}
        AND u.flag = {$flagNone}
        AND (SELECT IF(IFNULL(ua.flag, {$flagNone}) = {$flagNone}, true, false) FROM user_account ua WHERE ua.user_id = u.id AND ua.account_id = a.account_id)
WHERE ab.bid_type = {$abtRegular}
  AND ab.auction_id = {$this->escape($auctionId)}
  AND ab.lot_item_id = {$this->escape($lotItemId)}
ORDER BY @amount + FLOOR((ab.max_bid - @amount) / @increment) * @increment DESC,
         ab.placed_on
LIMIT 2;
SQL;
        // @formatter:on

        $this->enableReadOnlyDb($isReadOnlyDb);
        $dbResult = $this->query($query);
        $absenteeBids = AbsenteeBid::InstantiateDbResult($dbResult);

        $tmp1 = $absenteeBids[0] ?? null;
        $tmp2 = $absenteeBids[1] ?? null;
        return [$tmp1, $tmp2];
    }

    /**
     * Return first high absentee bids for a lot, which is higher than passed current bid
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param float|null $askingBid
     * @param bool $isBidderBudgetLimit
     * @return AbsenteeBid|null
     */
    public function detectFirstHighestByCurrentBid(
        ?int $lotItemId,
        ?int $auctionId,
        ?float $askingBid,
        bool $isBidderBudgetLimit = false
    ): ?AbsenteeBid {
        [$highAbsentee] = $this->detectTwoHighestByCurrentBid($lotItemId, $auctionId, $askingBid, $isBidderBudgetLimit);
        return $highAbsentee;
    }

    /**
     * Return highest and second high absentee bids for a lot, which are higher than passed current bid
     * @param int|null $lotItemId lot_item.id
     * @param int|null $auctionId auction.id
     * @param float|null $currentBid Absentee bids should be higher or equal than current bid value.
     * @param bool $isBidderBudgetLimit Consider bidder budget limit. If it is reached (sum of placed current bids) don't return his absentee bid.
     * @param bool $isReadOnlyDb
     * @return array{0: AbsenteeBid|null, 1: AbsenteeBid|null} array(AbsenteeBid $high, AbsenteeBid $second)
     */
    public function detectTwoHighestByCurrentBid(
        ?int $lotItemId,
        ?int $auctionId,
        ?float $currentBid,
        bool $isBidderBudgetLimit = false,
        bool $isReadOnlyDb = false
    ): array {
        $lotItemId = Cast::toInt($lotItemId, Constants\Type::F_INT_POSITIVE);
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        $currentBid = (float)$currentBid;
        $approvedBidderWhereClause = $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause();
        $n = "\n";
        $flagNone = Constants\User::FLAG_NONE;
        $abtRegular = Constants\Bid::ABT_REGULAR;
        // @formatter:off
        if (!$isBidderBudgetLimit) {
            $query =
                "SELECT * , " . $n .
                    "(IFNULL(aub.spent, 0) - IFNULL(aub.`collected`, 0) + {$currentBid}) as __current_outstanding, " . $n .
                    "IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`) as __max_outstanding ". $n .
                "FROM absentee_bid AS ab " . $n .
                "INNER JOIN auction_bidder aub ON aub.user_id = ab.user_id AND aub.auction_id = ab.auction_id " . $n .
                "LEFT JOIN auction a ON a.id = aub.auction_id " . $n .
                "INNER JOIN `user` u"
                    . " ON u.id = ab.user_id AND u.user_status_id = " . Constants\User::US_ACTIVE
                    . " AND u.flag = {$flagNone}" . $n
                    . " AND (SELECT IF(IFNULL(ua.flag, {$flagNone}) = {$flagNone}, true, false) FROM user_account ua WHERE ua.user_id = u.id AND ua.account_id = a.account_id) " .
                "LEFT JOIN user_info ui ON ui.user_id = aub.user_id " . $n .
                "WHERE ab.lot_item_id = " . $this->escape($lotItemId) . " " . $n .
                    "AND ab.auction_id = " . $this->escape($auctionId) . " " . $n .
                    "AND ab.max_bid >= " . $this->escape($currentBid) . " " . $n .
                    "AND ab.bid_type = {$abtRegular} " . $n .
                    "AND IF(" . $n .
                        "IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`) IS NOT NULL," . $n .
                        "IFNULL(aub.spent, 0) - IFNULL(aub.`collected`, 0)" . $n .
                            "< IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`)," . $n .
                        "TRUE) " . $n .
                    "AND {$approvedBidderWhereClause} " . $n .
                "ORDER BY IF(__max_outstanding IS NULL, max_bid, LEAST(max_bid,  __max_outstanding - __current_outstanding)) DESC, placed_on ";

        } else {
            $query =
                "SELECT " .
                    "ab.*, " . $n .
                    "IF (aub.bid_budget > 0, " . $n .
                        "(CAST(GREATEST(0, LEAST(ab.max_bid, aub.bid_budget - (IFNULL((" .
                            "SELECT SUM(bt.bid) " . $n .
                            "FROM auction_lot_item ali " . $n .
                            "INNER JOIN bid_transaction bt ON ali.current_bid_id = bt.id " . $n .
                            "WHERE ali.auction_id = ab.auction_id " . $n .
                                "AND bt.user_id = ab.user_id " . $n .
                                "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ")), 0)))) AS DECIMAL(14,4))), " . $n .
                        "ab.max_bid) AS __rem_max_bid, " . $n .
                    "(IFNULL(aub.spent, 0) - IFNULL(aub.`collected`, 0) + {$currentBid}) as __current_outstanding, " . $n .
                    "IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`) as __max_outstanding ". $n .
                "FROM absentee_bid AS ab " . $n .
                "INNER JOIN auction_bidder aub ON aub.user_id = ab.user_id AND aub.auction_id = ab.auction_id " . $n .
                "LEFT JOIN auction a ON a.id = aub.auction_id " . $n .
                "INNER JOIN `user` u"
                    . " ON u.id = ab.user_id AND u.user_status_id = " . Constants\User::US_ACTIVE
                    . " AND u.flag = " . Constants\User::FLAG_NONE . $n
                    . " AND (SELECT IF(IFNULL(ua.flag, {$flagNone}) = {$flagNone}, true, false) FROM user_account ua WHERE ua.user_id = u.id AND ua.account_id = a.account_id) " .
                "LEFT JOIN user_info ui ON ui.user_id = aub.user_id " . $n .
                "WHERE ab.lot_item_id = " . $this->escape($lotItemId) . " " . $n .
                    "AND ab.auction_id = " . $this->escape($auctionId) . " " . $n .
                    "AND ab.max_bid >= " . $this->escape($currentBid) . " " . $n .
                    "AND ab.bid_type = {$abtRegular} " . $n .
                    "AND IF(ab.or_id > 0, " .
                        "(SELECT COUNT(1) FROM absentee_bid ab2 " . $n .
                        "INNER JOIN auction_lot_item ali " . $n .
                            "ON ab2.auction_id = ali.auction_id " . $n .
                            "AND ab2.lot_item_id = ali.lot_item_id " . $n .
                        "INNER JOIN bid_transaction bt " . $n .
                            "ON ali.current_bid_id = bt.id " . $n .
                        "WHERE " .
                            "ab2.auction_id = " . $this->escape($auctionId) . " " . $n .
                            "AND ab2.user_id = ab.user_id " . $n .
                            "AND bt.user_id = ab.user_id " . $n .
                            "AND ab2.or_id = ab.or_id " . $n .
                            "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ")" . $n .
                            "AND ab2.lot_item_id != ab.lot_item_id), " .
                        "0) = 0 " . $n .

                    "AND IF(aub.bid_budget > 0, " . $n .
                        "(CAST(aub.bid_budget - (IFNULL((" .
                            "SELECT SUM(bt.bid) " . $n .
                            "FROM auction_lot_item ali " . $n .
                            "INNER JOIN bid_transaction bt ON ali.current_bid_id = bt.id " . $n .
                            "WHERE ali.auction_id = ab.auction_id " . $n .
                                "AND bt.user_id = ab.user_id " . $n .
                                "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ")), 0))" .
                            " AS DECIMAL(14,4))), " . $n .
                        "ab.max_bid) > 0 " . $n .
                    "AND IF(" . $n .
                        "IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`) IS NOT NULL," . $n .
                        "IFNULL(aub.spent, 0) - IFNULL(aub.`collected`, 0) " . $n .
                            "< IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`)," . $n .
                        "TRUE) " . $n .
                    "AND {$approvedBidderWhereClause} " . $n .
                "ORDER BY IF(__max_outstanding IS NULL, __rem_max_bid, LEAST(__rem_max_bid,  __max_outstanding - __current_outstanding)) DESC, placed_on ";
        }
        // @formatter:on

        $this->enableReadOnlyDb($isReadOnlyDb);
        $dbResult = $this->query($query);
        $absenteeBids = AbsenteeBid::InstantiateDbResult($dbResult);
        $this->outstandingExceedAbsenteeBids = [];
        $topAbsenteeBids = [];
        foreach ($absenteeBids as $absenteeBid) {
            $outstandingLeft = $this->calcOutstandingLeft($absenteeBid);
            if (
                $outstandingLeft !== null
                && Floating::lt($outstandingLeft, 0)
            ) {
                $firstMaxBid = $topAbsenteeBids[0]->MaxBid ?? 0.;
                if (Floating::gteq($absenteeBid->MaxBid, $firstMaxBid)) {
                    $this->outstandingExceedAbsenteeBids[] = $absenteeBid;
                }
                continue;
            }

            if (count($topAbsenteeBids) < 2) {
                $topAbsenteeBids[] = $absenteeBid;
            }
        }
        $tmp1 = $topAbsenteeBids[0] ?? null;
        $tmp2 = $topAbsenteeBids[1] ?? null;
        $bidAmount1 = 0.;
        $bidAmount2 = 0.;
        try {
            if (!$isBidderBudgetLimit) {
                $maxBid1 = $tmp1->MaxBid ?? 0.;
                $maxBid2 = $tmp2->MaxBid ?? 0.;
            } else {
                $maxBid1 = $tmp1 ? (float)$tmp1->GetVirtualAttribute('rem_max_bid') : 0.;
                $maxBid2 = $tmp2 ? (float)$tmp2->GetVirtualAttribute('rem_max_bid') : 0.;
            }

            $maxBid1OutstandingLeft = $this->calcOutstandingLeft($tmp1);
            if ($maxBid1OutstandingLeft !== null) {
                $maxBid1 = min($maxBid1, $maxBid1OutstandingLeft);
            }
            $maxBid2OutstandingLeft = $this->calcOutstandingLeft($tmp2);
            if ($maxBid2OutstandingLeft !== null) {
                $maxBid2 = min($maxBid2, $maxBid2OutstandingLeft);
            }

            $bidAmount1 = $tmp1
                ? $this->createAskingBidDetector()
                    ->detectQuantizedBid($maxBid1, false, $lotItemId, $auctionId)
                : 0.;
            $bidAmount2 = $tmp2
                ? $this->createAskingBidDetector()
                    ->detectQuantizedBid($maxBid2, false, $lotItemId, $auctionId)
                : 0.;
        } catch (Exception $e) {
            log_error(composeLogData(['Caught exception' => $e->getMessage()]));
        }

        $pairAbsenteeBids = $this->composeArray($bidAmount1, $bidAmount2, $tmp1, $tmp2);
        return $pairAbsenteeBids;
    }

    /**
     * @param AbsenteeBid|null $absenteeBid
     * @return float|null
     */
    public function calcOutstandingLeft(?AbsenteeBid $absenteeBid): ?float
    {
        if ($absenteeBid === null) {
            return null;
        }
        $maxOutstanding = Cast::toFloat($absenteeBid->GetVirtualAttribute('max_outstanding'));
        if ($maxOutstanding === null) {
            return null;
        }
        $currentOutstanding = (float)$absenteeBid->GetVirtualAttribute('current_outstanding');
        return $maxOutstanding - $currentOutstanding;
    }

    /**
     * @return AbsenteeBid[]
     */
    public function getOutstandingExceedAbsenteeBids(): array
    {
        return $this->outstandingExceedAbsenteeBids;
    }

    /**
     * @param float $bidAmount1
     * @param float $bidAmount2
     * @param AbsenteeBid|null $tmp1
     * @param AbsenteeBid|null $tmp2
     * @return array{0: AbsenteeBid|null, 1: AbsenteeBid|null}
     */
    private function composeArray(float $bidAmount1, float $bidAmount2, ?AbsenteeBid $tmp1, ?AbsenteeBid $tmp2): array
    {
        if (Floating::gt($bidAmount1, $bidAmount2)) {
            $highAbsentee = $tmp1;
            $secondAbsentee = $tmp2;
        } elseif (Floating::lt($bidAmount1, $bidAmount2)) {
            $highAbsentee = $tmp2;
            $secondAbsentee = $tmp1;
        } elseif (
            $tmp1
            && $tmp2
            && ($tmp1->PlacedOn->getTimestamp() < $tmp2->PlacedOn->getTimestamp())
        ) {
            $highAbsentee = $tmp1;
            $secondAbsentee = $tmp2;
        } elseif (
            $tmp1
            && $tmp2
            && ($tmp1->PlacedOn->getTimestamp() > $tmp2->PlacedOn->getTimestamp())
        ) {
            $highAbsentee = $tmp2;
            $secondAbsentee = $tmp1;
        } else {
            $highAbsentee = $tmp1;
            $secondAbsentee = $tmp2;
        }
        return [$highAbsentee, $secondAbsentee];
    }
}
