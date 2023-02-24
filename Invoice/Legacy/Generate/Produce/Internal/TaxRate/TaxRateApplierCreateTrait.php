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

/**
 * Trait TaxRateApplierCreateTrait
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\TaxRate
 */
trait TaxRateApplierCreateTrait
{
    protected ?TaxRateApplier $taxRateApplier = null;

    /**
     * @return TaxRateApplier
     */
    protected function createTaxRateApplier(): TaxRateApplier
    {
        return $this->taxRateApplier ?: TaxRateApplier::new();
    }

    /**
     * @param TaxRateApplier $taxRateApplier
     * @return $this
     * @internal
     */
    public function setTaxRateApplier(TaxRateApplier $taxRateApplier): static
    {
        $this->taxRateApplier = $taxRateApplier;
        return $this;
    }
}
