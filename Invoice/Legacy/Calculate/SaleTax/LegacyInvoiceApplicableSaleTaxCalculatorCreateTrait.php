<?php
/**
 * SAM-6769: Refactor invoice applicable sale tax calculation logic and cover it with unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\SaleTax;

/**
 * Trait InvoiceApplicableSaleTaxCalculatorCreateTrait
 * @package Sam\Invoice\Legacy\Calculate\SaleTax
 */
trait LegacyInvoiceApplicableSaleTaxCalculatorCreateTrait
{
    /**
     * @var LegacyInvoiceApplicableSaleTaxCalculator|null
     */
    protected ?LegacyInvoiceApplicableSaleTaxCalculator $legacyInvoiceApplicableSaleTaxCalculator = null;

    /**
     * @return LegacyInvoiceApplicableSaleTaxCalculator
     */
    protected function createLegacyInvoiceApplicableSaleTaxCalculator(): LegacyInvoiceApplicableSaleTaxCalculator
    {
        return $this->legacyInvoiceApplicableSaleTaxCalculator ?: LegacyInvoiceApplicableSaleTaxCalculator::new();
    }

    /**
     * @param LegacyInvoiceApplicableSaleTaxCalculator $legacyInvoiceApplicableSaleTaxCalculator
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceApplicableSaleTaxCalculator(LegacyInvoiceApplicableSaleTaxCalculator $legacyInvoiceApplicableSaleTaxCalculator): static
    {
        $this->legacyInvoiceApplicableSaleTaxCalculator = $legacyInvoiceApplicableSaleTaxCalculator;
        return $this;
    }
}
