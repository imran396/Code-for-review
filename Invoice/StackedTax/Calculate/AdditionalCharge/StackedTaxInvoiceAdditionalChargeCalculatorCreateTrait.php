<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Calculate\AdditionalCharge;

trait StackedTaxInvoiceAdditionalChargeCalculatorCreateTrait
{
    /**
     * @var StackedTaxInvoiceAdditionalChargeCalculator|null
     */
    protected ?StackedTaxInvoiceAdditionalChargeCalculator $stackedTaxInvoiceAdditionalChargeCalculator = null;

    /**
     * @return StackedTaxInvoiceAdditionalChargeCalculator
     */
    protected function createStackedTaxInvoiceAdditionalChargeCalculator(): StackedTaxInvoiceAdditionalChargeCalculator
    {
        return $this->stackedTaxInvoiceAdditionalChargeCalculator ?: StackedTaxInvoiceAdditionalChargeCalculator::new();
    }

    /**
     * @param StackedTaxInvoiceAdditionalChargeCalculator $stackedTaxInvoiceAdditionalChargeCalculator
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceAdditionalChargeCalculator(StackedTaxInvoiceAdditionalChargeCalculator $stackedTaxInvoiceAdditionalChargeCalculator): static
    {
        $this->stackedTaxInvoiceAdditionalChargeCalculator = $stackedTaxInvoiceAdditionalChargeCalculator;
        return $this;
    }
}
