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

namespace Sam\Reminder\Concrete\Payment\Internal\Load;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Reminder\Concrete\Payment\Internal\Load
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

    public function load(): array
    {
        $this->createTemporaryTable();
        $invoiceIds = [];
        // Query all not paid invoices
        // order by invoice.id
        $query = "SELECT * FROM payment_reminders_tmp WHERE balance > 0";
        log_debug($query);
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
                "CREATE TEMPORARY TABLE payment_reminders_tmp ( " . $n .
                "  `invoice_id` INT(10) NOT NULL PRIMARY KEY, " . $n .
                "  `bid_total` DECIMAL(12, 2), " . $n .
                "  `premium` DECIMAL(12, 2), " . $n .
                "  `tax` DECIMAL(12, 2) ," . $n .
                "  `shipping_fees` DECIMAL(12, 2), " . $n .
                "  `extra_charges` DECIMAL(12, 2), " . $n .
                "  `total_payment` DECIMAL(12, 2), " . $n .
                "  `cash_discount` DECIMAL(12, 2), " . $n .
                "  `total` DECIMAL(12, 2), " . $n .
                "  `balance` DECIMAL(12, 2) " . $n .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8 " ,

                "REPLACE payment_reminders_tmp (`invoice_id`, `bid_total`, `premium`, `tax`, `shipping_fees`, `extra_charges`, `total_payment`, `cash_discount`, `total`, `balance`) " . $n .
                "SELECT i.id AS invoice_id, " . $n .
                    "@bid_total := ROUND(i.bid_total, 2) AS bid_total, " . $n .
                    "@premium := ROUND(i.buyers_premium, 2) AS premium, " . $n .
                    "@tax := ROUND(i.tax, 2) AS tax, " . $n .
                    "@shipping_fees := IFNULL(ROUND(i.shipping_fees, 2), 0) AS shipping_fees, " . $n .
                    "@extra_charges := IFNULL(ROUND(i.extra_charges, 2), 0) AS extra_charges, " . $n .
                    "@total_payment := IFNULL(ROUND(i.total_payment, 2), 0) AS total_payment, " . $n .
                    "@cash_discount := IF(i.cash_discount = true, (@bid_total + @premium) * (select IFNULL(cash_discount,0) from setting_invoice where account_id = i.account_id) / 100, 0) AS cash_discount, " . $n .
                    "@total := CAST((@bid_total + @premium + @tax + @shipping_fees + @extra_charges) - @cash_discount AS DECIMAL(12,2)) AS total, " . $n .
                    "@balance := CAST((@total  - @total_payment) AS DECIMAL(12,2)) AS balance " . $n .

                "FROM invoice as i " . $n .
                "JOIN `user` u ON u.id = i.bidder_id AND u.user_status_id = " . Constants\User::US_ACTIVE . " " . $n .
                "INNER JOIN account acc ON acc.id = i.account_id AND acc.active " . $n .
                "WHERE i.invoice_status_id = " . Constants\Invoice::IS_PENDING
            ];
            // @formatter:on

        foreach ($queryParts as $query) {
            log_debug($query);
            $this->nonQuery($query);
        }
    }
}
