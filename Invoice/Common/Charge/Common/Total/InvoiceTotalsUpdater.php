<?php
/**
 * SAM-9938: Invoice paid via frontend not reflecting proper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Common\Total;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;

/**
 * This class is responsible for calculating and assigning invoice totals without saving.
 * Should be used after every processed and added payment.
 *
 * Class InvoiceTotalsUpdater
 * @package Sam\Invoice\Common\Charge\Common\Total
 */
class InvoiceTotalsUpdater extends CustomizableClass
{
    use LegacyInvoiceCalculatorAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Recalculate the totals for sales tax, additional charges, payments and assign these values to the passed invoice entity
     *
     * @param Invoice $invoice
     * @param bool $isReadOnlyDb
     * @return Invoice
     */
    public function calcAndAssign(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        if ($invoice->isLegacyTaxDesignation()) {
            return $this->calcAndAssignForLegacyTaxDesignation($invoice, $isReadOnlyDb);
        }

        if ($invoice->isStackedTaxDesignation()) {
            return $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice, $isReadOnlyDb);
        }

        return $invoice;
    }

    protected function calcAndAssignForLegacyTaxDesignation(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        $invoiceCalculator = $this->getLegacyInvoiceCalculator();
        $invoice->Tax = $this->createLegacyInvoiceTaxCalculator()->calcTotalSalesTaxApplied($invoice->Id, $isReadOnlyDb);
        $invoice->ExtraCharges = $invoiceCalculator->calcTotalAdditionalCharges($invoice->Id, $isReadOnlyDb);
        $invoice->TotalPayment = $invoiceCalculator->calcTotalPayments($invoice->Id, $isReadOnlyDb);
        return $invoice;
    }
}
