<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\TaxRate;

use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class TaxRateApplier
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\TaxRate
 */
class TaxRateApplier extends CustomizableClass
{
    use DbConnectionTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Set the tax service of a newly created invoice.
     * We take "Buyer's sales tax" from levels: User -> Consignor -> Account, when the "Tax application" is set "On Services".
     * @param int $invoiceId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return float
     * @throws QMySqliDatabaseException
     */
    public function applyTaxService(int $invoiceId, int $editorUserId, bool $isReadOnlyDb = false): float
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error(
                "Available invoice not found, when applying service tax"
                . composeSuffix(['i' => $invoiceId])
            );
            return 0.;
        }

        $db = $this->getDb();
        $taServices = Constants\User::TAX_SERVICES;
        // @formatter:off
        $query =
            'SELECT ui.sales_tax AS tax_rate ' .
            'FROM invoice i ' .
            'INNER JOIN user_info ui ON ui.user_id = i.bidder_id ' .
            'WHERE i.id = ' . $db->SqlVariable($invoiceId) . ' ' .
                "AND ui.tax_application = {$taServices} " .
            'LIMIT 1';
        // @formatter:on
        $dbResult = $db->Query($query, $isReadOnlyDb);
        $taxAppRow = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $taxRate = (float)($taxAppRow['tax_rate'] ?? 0.);
        if (Floating::gt($taxRate, 0.)) {
            log_debug('Buyer has sales tax and tax service is enabled' . composeSuffix(['i' => $invoiceId, 'tax' => $taxRate]));
        }

        if (Floating::eq($taxRate, 0.)) {
            // @formatter:off
            $query =
                'SELECT cons.sales_tax AS tax_rate ' .
                'FROM invoice_item AS ii ' .
                'LEFT JOIN lot_item li ON li.id = ii.lot_item_id ' .
                'LEFT JOIN consignor cons ON cons.user_id = li.consignor_id ' .
                'WHERE cons.buyer_tax_services ' .
                    'AND cons.sales_tax > 0 ' .
                    'AND ii.invoice_id = ' . $db->SqlVariable($invoiceId) . ' ' .
                    'AND ii.active ' .
                'ORDER BY cons.sales_tax DESC ' .
                'LIMIT 1';
            // @formatter:on
            $dbResult = $db->Query($query, $isReadOnlyDb);
            $taxAppRow = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $taxRate = (float)($taxAppRow['tax_rate'] ?? 0.);
            if (Floating::gt($taxRate, 0.)) {
                log_debug('Consignor has buyer sales tax and tax service is enabled' . composeSuffix(['i' => $invoiceId, 'tax' => $taxRate]));
            }
        }

        if (Floating::eq($taxRate, 0.)) {
            // @formatter:off
            $query =
                'SELECT setinv.sales_tax AS tax_rate ' .
                'FROM setting_invoice AS setinv ' .
                'INNER JOIN invoice i ON setinv.account_id = i.account_id ' .
                'WHERE i.id = ' . $db->SqlVariable($invoiceId) . ' ' .
                    'AND setinv.sales_tax_services ' .
                'LIMIT 1';
            // @formatter:on
            $dbResult = $db->Query($query, $isReadOnlyDb);
            $taxAppRow = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $taxRate = (float)($taxAppRow['tax_rate'] ?? 0.);
            if (Floating::gt($taxRate, 0.)) {
                log_debug('System parameters has sales tax and tax service is enabled' . composeSuffix(['i' => $invoiceId, 'tax' => $taxRate]));
            }
        }

        $invoice->TaxChargesRate = $taxRate;
        $invoice->TaxFeesRate = $taxRate;
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        return $taxRate;
    }

}
