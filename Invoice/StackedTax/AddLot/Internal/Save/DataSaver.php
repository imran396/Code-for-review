<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\AddLot\Internal\Save;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculator;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProducer;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionResult;

/**
 * Class DataSaver
 * @package Sam\Invoice\StackedTax\AddLot\Internal\Save
 */
class DataSaver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function recalculateSummary(Invoice $invoice, int $editorUserId, bool $isReadOnlyDb = false): Invoice
    {
        return StackedTaxInvoiceSummaryCalculator::new()->recalculateInvoiceAndSave($invoice, $editorUserId, $isReadOnlyDb);
    }

    public function produceSingleInvoice(StackedTaxSingleInvoiceItemProductionInput $invoiceItemProductionInput): StackedTaxSingleInvoiceItemProductionResult
    {
        return StackedTaxSingleInvoiceItemProducer::new()->produce($invoiceItemProductionInput);
    }
}
