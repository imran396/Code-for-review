<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Concrete\Pickup\Internal\Load;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Reminder\Concrete\Pickup\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
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
        $this->createTemporaryTable();
        $invoiceIds = [];
        $n = "\n";
        // Query all not paid invoices
        // order by invoice.id
        // @formatter:off
        $query =
            "SELECT prt.invoice_id as invoice_id, " .
            "ii.id as invoice_item_id, " .
            "li.id as lot_item_id, " .
            "ali.id as auc_lot_item_id " . $n .
            "FROM pickup_reminders_tmp prt " . $n .
            "JOIN invoice_item ii ON ii.invoice_id = prt.invoice_id AND ii.active = 1 " . $n .
            "JOIN lot_item li ON ii.lot_item_id = li.id AND li.active = 1 " . $n .
            "JOIN auction_lot_item ali ON li.id = ali.lot_item_id " . $n .
            "JOIN invoice i ON i.id = prt.invoice_id " . $n .
            "JOIN `user` u ON u.id = i.bidder_id AND u.user_status_id = " . Constants\User::US_ACTIVE . " " . $n .
            "INNER JOIN account acc ON acc.id = li.account_id AND acc.active " . $n .
            "WHERE ali.lot_status_id IN (" . implode(',', Constants\Lot::$inAuctionStatuses) .")" . $n .
            "GROUP BY invoice_id";
        // @formatter:on

        $this->query($query);
        while ($row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $invoiceIds[] = (int)$row['invoice_id'];
        }
        return $invoiceIds;
    }

    public function createTemporaryTable(): void
    {
        $n = "\n";
        // @formatter:off
        $queryParts = [
            "CREATE TEMPORARY TABLE pickup_reminders_tmp ( " . $n .
            "  `invoice_id` INT(10) NOT NULL PRIMARY KEY " . $n .
            ") ENGINE=InnoDB DEFAULT CHARSET=utf8 ",

            "REPLACE pickup_reminders_tmp (`invoice_id`) " . $n .
            "SELECT i.id AS invoice_id " . $n .
            "FROM invoice as i " .
            "WHERE i.invoice_status_id = " . Constants\Invoice::IS_PAID
        ];

        foreach ($queryParts as $query) {
            log_debug($query);
            $this->nonQuery($query);
        }
        // @formatter:off
    }

}
