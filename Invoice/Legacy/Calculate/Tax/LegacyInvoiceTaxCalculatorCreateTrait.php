<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\Tax;

/**
 * Trait InvoiceTaxCalculatorCreateTrait
 * @package Sam\Invoice\Legacy\Calculate\Tax
 */
trait LegacyInvoiceTaxCalculatorCreateTrait
{
    protected ?LegacyInvoiceTaxCalculator $legacyInvoiceTaxCalculator = null;

    /**
     * @return LegacyInvoiceTaxCalculator
     */
    protected function createLegacyInvoiceTaxCalculator(): LegacyInvoiceTaxCalculator
    {
        return $this->legacyInvoiceTaxCalculator ?: LegacyInvoiceTaxCalculator::new();
    }

    /**
     * @param LegacyInvoiceTaxCalculator $legacyInvoiceTaxCalculator
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceTaxCalculator(LegacyInvoiceTaxCalculator $legacyInvoiceTaxCalculator): static
    {
        $this->legacyInvoiceTaxCalculator = $legacyInvoiceTaxCalculator;
        return $this;
    }
}
