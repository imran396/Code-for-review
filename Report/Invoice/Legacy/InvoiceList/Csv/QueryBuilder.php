<?php
/**
 * SAM-4623 : Refactor invoice list report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/18/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Csv;

use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Csv
 */
class QueryBuilder extends \Sam\Report\Invoice\Legacy\InvoiceList\Base\QueryBuilder
{
    use ConfigRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get SQL Select Clause
     * @return string
     */
    protected function getSelectClause(): string
    {
        $n = "\n";
        // @formatter:off
        $query = "SELECT " . $n .
            "i.id AS id, " . $n .
            "i.invoice_no AS invoice_no, " . $n .
            "i.created_on AS created_on, " . $n .
            "i.invoice_date AS invoice_date, " . $n .
            "i.first_sent_on AS first_sent_on, " . $n .
            "i.invoice_status_id AS invoice_status_id, " . $n .
            "i.shipping AS shipping, " . $n .
            "i.bidder_id AS bidder_id, " . $n .
            "i.internal_note AS internal_note, " . $n .

            // TODO: remove for stacked tax CSV export
            "u.username AS username, " . $n .
            "u.email AS email, " . $n .
            "u.customer_no AS customer_no, " . $n .
            "ui.first_name AS first_name, " . $n .
            "ui.last_name AS last_name, " . $n .
            "ui.sales_tax AS sales_tax, " . $n .
            "ui.phone AS iphone, " . $n .
            "ui.referrer AS referrer, " . $n .
            "ui.referrer_host AS referrer_host, " . $n .

            // TODO: use for stacked tax CSV export
            /*
            "iu.username AS username, " . $n .
            "iu.email AS email, " . $n .
            "iu.customer_no AS customer_no, " . $n .
            "iu.first_name AS first_name, " . $n .
            "iu.last_name AS last_name, " . $n .
            "iu.sales_tax AS sales_tax, " . $n .
            "iu.phone AS iphone, " . $n .
            "iu.referrer AS referrer, " . $n .
            "iu.referrer_host AS referrer_host, " . $n .
            */

            "iub.company_name AS bcompany_name, " . $n .
            "iub.first_name AS bfirst_name, " . $n .
            "iub.last_name AS blast_name, " . $n .
            "iub.phone AS bphone, " . $n .
            "iub.address AS baddress, " . $n .
            "iub.address2 AS baddress2, " . $n .
            "iub.address3 AS baddress3, " . $n .
            "iub.city AS bcity, " . $n .
            "iub.state AS bstate, " . $n .
            "iub.zip AS bzip, " . $n .
            "iub.country AS bcountry, " . $n .

            "ius.company_name AS scompany_name, " . $n .
            "ius.first_name AS sfirst_name, " . $n .
            "ius.last_name AS slast_name, " . $n .
            "ius.phone AS sphone, " . $n .
            "ius.address AS saddress, " . $n .
            "ius.address2 AS saddress2, " . $n .
            "ius.address3 AS saddress3, " . $n .
            "ius.city AS scity, " . $n .
            "ius.state AS sstate, " . $n .
            "ius.zip AS szip, " . $n .
            "ius.country AS scountry, " . $n .

            "inv_sum.taxable AS taxable, " . $n .
            "inv_sum.non_taxable AS non_taxable, " . $n .
            "inv_sum.bid_total AS bid_total, " . $n .
            "non_taxable_sum.sum AS non_taxable, " . $n .
            "export.sum AS export, " . $n .
            "taxable_sum.sum AS taxable, " . $n .
            "inv_sum.premium AS premium, " . $n .
            "inv_sum.tax AS tax, " . $n .
            "(IFNULL(inv_shipping.shipping_charge, 0)) AS shipping_fees, " . $n .
            "(IFNULL(inv_charge.total_charge, 0)) AS extra_charges_fees, " . $n .
            "IFNULL(inv_payment.total_payment, 0) AS total_payment, " . $n .
                "(inv_sum.bid_total + " . $n .
                "inv_sum.premium + " . $n .
                "inv_sum.tax + " . $n .
                    "(IFNULL(inv_shipping.shipping_charge, 0) + IFNULL(inv_charge.total_charge, 0)" .
                ")" .
            ") AS total, " . $n .
            "((inv_sum.bid_total + " . $n .
            "inv_sum.premium + " . $n .
            "inv_sum.tax + " . $n .
            "(IFNULL(inv_shipping.shipping_charge, 0) + IFNULL(inv_charge.total_charge, 0))) - IFNULL(inv_payment.total_payment, 0)) AS balance, " . $n .

            "(SELECT COUNT(1) FROM invoice_item WHERE invoice_id = i.id AND active = true) AS num_item, " . $n;
        // @formatter:on

        if (!$this->isMultipleSaleInvoice()) {
            // TODO: temporary solutions, we should extract logic to invoice list query builder
            $saleNoSeparator = $this->cfg()->get('core->auction->saleNo->extensionSeparator');
            $auctionTestPrefix = $this->cfg()->get('core->auction->test->prefix');
            // @formatter:off
            $query .=
                "(SELECT "
                . "IFNULL(" . $n
                    . "(SELECT "
                        . "CONCAT("
                            . "IFNULL(sale_num,''), "
                            . "IF(sale_num_ext IS NULL OR sale_num_ext = '', "
                                . "'', "
                                . "CONCAT('{$saleNoSeparator}', sale_num_ext)"
                            . ")"
                        . ") AS sale_no FROM auction WHERE id = " . $n
                            . "(SELECT auction_id FROM invoice_item WHERE " . $n
                            . "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)"
                        . "), "
                        . "'--'"
                    . ")"
                . ") AS sale_no, " . $n .

                "(SELECT IFNULL(" . $n .
                "(SELECT IF(test_auction,CONCAT('{$auctionTestPrefix}',name),name) FROM auction WHERE id = " . $n .
                "(SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)), '--')) AS sale_name, " . $n .

                "(SELECT IFNULL(" . $n .
                "(SELECT description FROM auction WHERE id = " . $n .
                "(SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)), '--')) AS sale_desc, " . $n .

                // Why do we want to know here actual bidder # instead of i.bidder_number
                "(SELECT IFNULL(" . $n .
                "(SELECT bidder_num FROM auction_bidder WHERE " . $n .
                "auction_id = (SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1) AND user_id = i.bidder_id LIMIT 1),'--')) AS bidder_num, " . $n;
            // @formatter:on
        }

        $query .= "(SELECT IFNULL(" . $n .
            "(SELECT id FROM auction WHERE id = " . $n .
            "(SELECT auction_id FROM invoice_item WHERE " . $n .
            "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)), '--')) AS sale_id, " . $n;

        $query .= "iub.state AS state, " . $n .
            "iub.zip AS zip " . $n;

        return $query;
    }
}
