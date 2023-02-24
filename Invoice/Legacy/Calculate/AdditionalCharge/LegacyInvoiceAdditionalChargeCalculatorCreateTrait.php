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

namespace Sam\Invoice\Legacy\Calculate\AdditionalCharge;

/**
 * Trait InvoiceAdditionalChargeCalculatorCreateTrait
 * @package Sam\Invoice\Legacy\Calculate\AdditionalCharge
 */
trait LegacyInvoiceAdditionalChargeCalculatorCreateTrait
{
    /**
     * @var LegacyInvoiceAdditionalChargeCalculator|null
     */
    protected ?LegacyInvoiceAdditionalChargeCalculator $legacyInvoiceAdditionalChargeCalculator = null;

    /**
     * @return LegacyInvoiceAdditionalChargeCalculator
     */
    protected function createInvoiceAdditionalChargeCalculator(): LegacyInvoiceAdditionalChargeCalculator
    {
        return $this->legacyInvoiceAdditionalChargeCalculator ?: LegacyInvoiceAdditionalChargeCalculator::new();
    }

    /**
     * @param LegacyInvoiceAdditionalChargeCalculator $legacyInvoiceAdditionalChargeCalculator
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceAdditionalChargeCalculator(LegacyInvoiceAdditionalChargeCalculator $legacyInvoiceAdditionalChargeCalculator): static
    {
        $this->legacyInvoiceAdditionalChargeCalculator = $legacyInvoiceAdditionalChargeCalculator;
        return $this;
    }
}
