<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Concrete\Registration\Internal\Load;

use Sam\Core\Constants;
use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Reminder\Concrete\Registration\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param string $lastRunIso
     * @param string $dateUtcIso
     * @param int $scriptInterval
     * @return array
     */
    public function load(string $lastRunIso, string $dateUtcIso, int $scriptInterval): array
    {
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $n = "\n";
        // @formatter:off
            $query =
                // create temporary table for auctions
                "CREATE TEMPORARY TABLE auction_reminders_tmp ( " .
                    "auc_id INT(10) NOT NULL PRIMARY KEY, " .
                    "reg_reminder INT(10) " .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8;" . $n .

                // live auctions where reminder needs to be sent between last run and now
                "REPLACE auction_reminders_tmp (auc_id, reg_reminder) " .
                "SELECT " .
                    "a.id AS auc_id, " .
                    "setsys.reg_reminder_email " .
                "FROM auction a " .
                "INNER JOIN setting_system setsys ON setsys.account_id = a.account_id " .
                "INNER JOIN `account` acc ON acc.id = a.account_id AND acc.active " .
                "WHERE " .
                    "a.auction_status_id IN (" . implode(',', Constants\Auction::$openAuctionStatuses) . ") " .
                    "AND a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "')" .
                    "AND IF(setsys.reg_reminder_email > " . $scriptInterval . ", " .
                        "a.start_closing_date BETWEEN DATE_ADD(" . $this->escape($lastRunIso) .", INTERVAL setsys.reg_reminder_email HOUR) " .
                            "AND DATE_ADD(" . $this->escape($dateUtcIso) . ", INTERVAL setsys.reg_reminder_email HOUR), " .
                        "a.start_closing_date BETWEEN " . $this->escape($dateUtcIso) . " " .
                            "AND DATE_ADD(" . $this->escape($dateUtcIso) . ", INTERVAL setsys.reg_reminder_email HOUR)); " . $n .

                // timed auctions where reminder needs to be sent between last run and now
                "REPLACE auction_reminders_tmp (auc_id, reg_reminder) " .
                "SELECT " .
                    "a.id AS auc_id, " .
                    "setsys.reg_reminder_email " .
                "FROM auction a " .
                "LEFT JOIN auction_dynamic adyn ON adyn.auction_id = a.id " .
                "INNER JOIN auction_lot_item ali ON ali.auction_id=a.id AND ali.lot_status_id IN (".implode(',',Constants\Lot::$inAuctionStatuses).") " .
                "INNER JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id=ali.id " .
                "INNER JOIN setting_system setsys ON setsys.account_id = a.account_id " .
                "INNER JOIN `account` acc ON acc.id = a.account_id AND acc.active " .
                "WHERE " .
                    "a.auction_status_id IN (" . implode(',', Constants\Auction::$openAuctionStatuses) . ") " .
                    "AND a.auction_type='" . Constants\Auction::TIMED . "' " .
                "GROUP BY a.id " .
                "HAVING " .
                    "IF(setsys.reg_reminder_email > " . $scriptInterval . ", " .
                        "MIN(" . $queryBuilderHelper->getTimedLotEndDateExpr() . ") " .
                            "BETWEEN DATE_ADD(" . $this->escape($lastRunIso) . ", INTERVAL setsys.reg_reminder_email HOUR) " .
                            "AND DATE_ADD(" . $this->escape($dateUtcIso) . ", INTERVAL setsys.reg_reminder_email HOUR), " .
                        "MIN(" . $queryBuilderHelper->getTimedLotEndDateExpr() . ") " .
                            "BETWEEN " . $this->escape($dateUtcIso) . " " .
                            "AND DATE_ADD(" . $this->escape($dateUtcIso) . ", INTERVAL setsys.reg_reminder_email HOUR)); " . $n .


                "SELECT a.auc_id, ab.user_id AS user_id " .
                "FROM auction_reminders_tmp a " .
                "INNER JOIN auction_bidder ab ON ab.auction_id=a.auc_id " .
                "INNER JOIN `user` u ON u.id = ab.user_id " .
                "WHERE u.user_status_id = " . Constants\User::US_ACTIVE . " " .
                    "AND u.flag NOT IN (" . $this->escape(Constants\User::FLAG_NOAUCTIONAPPROVAL) . ", " . $this->escape(Constants\User::FLAG_BLOCK) . ") " .
                "ORDER BY a.auc_id;";
            // @formatter:on

        // Query all registered users for these auctions, exclude flagged users
        // order by auction.id for improved Auction object caching
        log_debug($query);

        $dbResults = $this->multiQuery($query);
        $dbResult = current($dbResults);
        if (!$dbResult) {
            return [];
        }

        $rows = [];
        $i = 0;
        while ($row = $dbResult->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $rows[$i]['auc_id'] = (int)$row['auc_id'];
            $rows[$i]['user_id'] = (int)$row['user_id'];
            $i++;
        }
        return $rows;
    }
}
