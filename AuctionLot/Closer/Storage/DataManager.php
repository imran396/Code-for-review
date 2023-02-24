<?php
/**
 * Db data manager for auction lot item closer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 10, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer\Storage;

use AuctionLotItem;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;

/**
 * Class DataManager
 * @package Sam\AuctionLot\Closer\Storage
 */
class DataManager extends CustomizableClass
{
    use BidDateAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return unclosed timed online lots for auction
     *
     * @param int[]|null $auctionIds auction ids, null means all
     * @param int|null $limit null means no limit
     * @return AuctionLotItem[]
     */
    public function getUnclosedTimedLots(?array $auctionIds = null, ?int $limit = null): array
    {
        $auctionIds = array_filter((array)$auctionIds);
        $auctionCond = '';
        if (!empty($auctionIds)) {
            $auctionIds = $this->escapeArray($auctionIds);
            $auctionCond = " AND ali.auction_id IN (" . implode(",", $auctionIds) . ")";
        }
        $limitQuery = '';
        if ($limit) {
            $limitQuery = "limit $limit";
        }
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $n = "\n";
        $bidDateIsoEscaped = $this->escape($this->getBidDateUtcIso());
        // @formatter:off
        $query =
            "SELECT ali.*" . $n
            . " FROM auction_lot_item AS ali" . $n
            . " INNER JOIN auction a ON" . $n
                . " a.id = ali.auction_id" . $n
                . " AND a.auction_type='" . Constants\Auction::TIMED . "'" . $n
                . " AND a.auction_status_id IN (" . implode(',', [Constants\Auction::AS_ACTIVE , Constants\Auction::AS_STARTED ]) . ")" . $n
            . " INNER JOIN auction_lot_item_cache AS alic ON alic.auction_lot_item_id = ali.id" . $n
            . " LEFT JOIN auction_dynamic adyn ON adyn.auction_id = ali.auction_id" . $n
            . " INNER JOIN account AS acc ON acc.id = a.account_id AND acc.active" . $n
            . " WHERE" . $n
                . " ali.lot_status_id = '" . Constants\Lot::LS_ACTIVE . "'" . $n // where the lot is active
                . " AND ali.bulk_master_id IS NULL" . $n // do not include piecemeal lot
                . " AND (a.start_bidding_date <= " . $bidDateIsoEscaped . $n
                    . " OR a.event_type = " . Constants\Auction::ET_ONGOING . ")" . $n // the auction is started or ongoing
                . " AND " . $queryBuilderHelper->getTimedLotStartDateExpr() . $n
                    . " <= " . $bidDateIsoEscaped . $n    // lot is started
                . " AND " . $queryBuilderHelper->getTimedLotEndDateExpr() . $n
                    . " < " . $bidDateIsoEscaped . $n    // lot end time is in the past (excluding current second)
                . $auctionCond . $n
                . " ORDER BY alic.end_date, ali.is_bulk_master " . $n
                 .$limitQuery;
        // @formatter:on
        log_debug($query);
        $dbResult = $this->query($query);
        $rows = AuctionLotItem::InstantiateDbResult($dbResult);
        $rows = array_filter($rows);
        return $rows;
    }
}
