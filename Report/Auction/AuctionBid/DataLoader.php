<?php
/**
 * Data loading for AuctionBidReporter
 *
 * SAM-4617: Refactor Auction Bids report
 *
 * @author        Igors Kotlevskis
 * @since         Dec 12, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\AuctionBid;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AuctionBidReportFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Auction\AuctionBid
 */
class DataLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CommonAwareTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $query = $this->buildResultQuery();
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return string
     */
    protected function buildResultQuery(): string
    {
        $n = "\n";
        // @formatter:off
        $lotQuery =
            "SELECT"
                . " li.item_num AS item_num,"
                . " li.item_num_ext AS item_num_ext,"
                . " li.name AS lot_name,"
                . " li.low_estimate AS low_estimate,"
                . " li.high_estimate AS high_estimate,"
                . " li.hammer_price AS hammer_price,"
                . " li.auction_id AS winning_auction_id,"
                . " ali.start_date as lot_start_date,"
                . " ali.end_date as lot_end_date,"
                . " a.name AS auction_name,"
                . " a.test_auction AS test_auction,"
                . " a.sale_num AS sale_num,"
                . " a.sale_num_ext AS sale_num_ext,"
                . " a.auction_type AS auction_type,"
                . " a.account_id AS account_id,"
                . " a.event_type AS event_type,"
                . " u.username AS username,"
                . " u.email AS email,"
                . " u.customer_no AS customer_no,"
                . " ui.first_name AS first_name,"
                . " ui.last_name AS last_name,"
                . " ui.sales_tax AS sales_tax,"
                . " ui.tax_application AS tax_application,"
                . " ui.note AS note,"
                . " ui.phone AS phone,"
        ;

        $bitTransactionCond =
                " " . $this->escape(AuctionBidReporter::BT_BID_TRANSACTION) . " AS bid_type,"
                . " bt.auction_id AS auction_id,"
                . " bt.lot_item_id AS lot_item_id,"
                . " bt.bid AS bid,"
                . " bt.max_bid AS max_bid,"
                . " IF (bt.floor_bidder, true, false) AS flr_bid,"
                . " IF (bt.absentee_bid, true, false) AS abs_bid,"
                . " IF (bt.timed_online_bid, true, false) AS timed_bid,"
                . " bt.created_on AS bid_date,"
                . " bt.user_id AS user_id" . $n
            . " FROM bid_transaction AS bt" . $n
            . " INNER JOIN auction_lot_item AS ali"
                . " ON bt.auction_id = ali.auction_id"
                . " AND bt.lot_item_id = ali.lot_item_id"
                . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")" . $n
            . " INNER JOIN lot_item AS li"
                . " ON li.id = bt.lot_item_id"
                . " AND li.active" . $n
            . " INNER JOIN auction AS a"
                . " ON a.id = bt.auction_id"
                . " AND a.auction_status_id != '" . Constants\Auction::AS_DELETED . "'" . $n
            . " LEFT JOIN `user` u ON bt.user_id = u.id" . $n
            . " LEFT JOIN `user_info` ui ON bt.user_id = ui.user_id" . $n
            . " WHERE bt.deleted = false"
                . " AND bt.id NOT IN ("
                    . " SELECT `parent_bid_id` FROM `bid_transaction`"
                    . " WHERE "
                        . " auction_id = bt.auction_id"
                        . " AND lot_item_id = bt.lot_item_id"
                        . " AND `out_bid_id` IS NULL"
                        . " AND `parent_bid_id` IS NOT NULL) " . $n;

        $absenteeBidCond =
                " " . $this->escape(AuctionBidReporter::BT_ABSENTEE_BID) . " AS bid_type,"
                . " ab.auction_id AS auction_id,"
                . " ab.lot_item_id AS lot_item_id,"
                . " null AS bid,"
                . " ab.max_bid AS max_bid,"
                . " null AS flr_bid,"
                . " null AS abs_bid,"
                . " null AS timed_bid,"
                . " ab.created_on AS bid_date,"
                . " ab.user_id AS user_id"
            . " FROM absentee_bid AS ab" . $n
            . " INNER JOIN auction_lot_item AS ali"
                . " ON ab.auction_id = ali.auction_id"
                . " AND ab.lot_item_id = ali.lot_item_id"
                . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")" . $n
            . " INNER JOIN lot_item AS li"
                . " ON ab.lot_item_id = li.id"
                . " AND li.active" . $n
            . " INNER JOIN auction AS a"
                . " ON a.id = ab.auction_id"
                . " AND a.auction_status_id != '" . Constants\Auction::AS_DELETED . "'" . $n
            . " LEFT JOIN `user` u ON ab.user_id = u.id" . $n
            . " LEFT JOIN `user_info` ui ON ab.user_id = ui.user_id" . $n
            . " WHERE ab.max_bid > 0" . $n;

        if ($this->isDateFiltering()) {
            $startDateSysIso = $this->getFilterStartDateSysIso();
            $endDateSysIso = $this->getFilterEndDateSysIso();
            $startDateTimeSysIso = date('Y-m-d', strtotime($startDateSysIso)) . ' 00:00:00';
            $endDateTimeSysIso = date('Y-m-d', strtotime($endDateSysIso)) . ' 23:59:59';
            $startDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($startDateTimeSysIso);
            $startDateUtcIso = $startDateUtc->format(Constants\Date::ISO);
            $endDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($endDateTimeSysIso);
            $endDateUtcIso = $endDateUtc->format(Constants\Date::ISO);

            if ($this->getDateRangeType() === AuctionBidReportFormConstants::DR_BID) {
                $bitTransactionCond .= " AND (bt.created_on >= " . $this->escape($startDateUtcIso)
                    . " AND bt.created_on <= " . $this->escape($endDateUtcIso) . ")";
                $absenteeBidCond .= " AND (ab.created_on >= " . $this->escape($startDateUtcIso)
                    . " AND ab.created_on <= " . $this->escape($endDateUtcIso) . ")" . $n;
            } else {
                $bitTransactionCond .=
                    " AND (("
                        . "IF(a.auction_type='" . Constants\Auction::TIMED . "',"
                            . "IF(a.event_type = " . Constants\Auction::ET_ONGOING . ", a.created_on, a.end_date),"
                            . "a.start_closing_date)"
                        . ") >= " . $this->escape($startDateUtcIso)
                    . " AND " . $n
                        . "(IF(a.auction_type='" . Constants\Auction::TIMED . "',"
                            . "IF(a.event_type = " . Constants\Auction::ET_ONGOING . ", a.created_on, a.end_date),"
                            . "a.start_closing_date)"
                        . ") <= " . $this->escape($endDateUtcIso) . ")" . $n;
                $absenteeBidCond .=
                    " AND (("
                        . "IF(a.auction_type='" . Constants\Auction::TIMED . "',"
                            . "IF(a.event_type = " . Constants\Auction::ET_ONGOING . ", a.created_on, a.end_date),"
                            . "a.start_closing_date)"
                        . ") >= " . $this->escape($startDateUtcIso)
                    . " AND " . $n
                        . "(IF(a.auction_type='" . Constants\Auction::TIMED . "',"
                            . "IF(a.event_type = " . Constants\Auction::ET_ONGOING . ", a.created_on, a.end_date),"
                            . "a.start_closing_date)"
                        . ") <= " . $this->escape($endDateUtcIso) . ")" . $n;
            }
        }
        // @formatter:on

        $accountId = $this->getFilterAccountId();
        if ($accountId) {
            $bitTransactionCond .= " AND a.account_id = " . $this->escape($accountId) . $n;
            $absenteeBidCond .= " AND a.account_id = " . $this->escape($accountId) . $n;
        }

        $auctionId = $this->getFilterAuctionId();
        if ($auctionId) {
            $bitTransactionCond .= " AND bt.auction_id = " . $this->escape($auctionId) . $n;
            $absenteeBidCond .= " AND ab.auction_id = " . $this->escape($auctionId) . $n;
        }

        $query = $lotQuery . $n . $bitTransactionCond;

        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (
            !$auction
            || $auction->isLiveOrHybrid()
        ) {
            $query .= " UNION " . $n . $lotQuery . $n . $absenteeBidCond . $n;
        }

        $query .= " ORDER BY bid_date";
        return $query;
    }
}
