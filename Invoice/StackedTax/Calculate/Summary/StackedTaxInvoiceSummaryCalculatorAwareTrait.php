<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Calculate\Summary;

/**
 * Trait InvoiceSummaryCalculatorAwareTrait
 * @package Sam\Invoice\Calculate
 */
trait StackedTaxInvoiceSummaryCalculatorAwareTrait
{
    /**
     * @var StackedTaxInvoiceSummaryCalculator|null
     */
    protected ?StackedTaxInvoiceSummaryCalculator $stackedTaxInvoiceSummaryCalculator = null;

    /**
     * @return StackedTaxInvoiceSummaryCalculator
     */
    protected function getStackedTaxInvoiceSummaryCalculator(): StackedTaxInvoiceSummaryCalculator
    {
        if ($this->stackedTaxInvoiceSummaryCalculator === null) {
            $this->stackedTaxInvoiceSummaryCalculator = StackedTaxInvoiceSummaryCalculator::new();
        }
        return $this->stackedTaxInvoiceSummaryCalculator;
    }

    /**
     * @param StackedTaxInvoiceSummaryCalculator $stackedTaxInvoiceSummaryCalculator
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceSummaryCalculator(StackedTaxInvoiceSummaryCalculator $stackedTaxInvoiceSummaryCalculator): static
    {
        $this->stackedTaxInvoiceSummaryCalculator = $stackedTaxInvoiceSummaryCalculator;
        return $this;
    }
}
