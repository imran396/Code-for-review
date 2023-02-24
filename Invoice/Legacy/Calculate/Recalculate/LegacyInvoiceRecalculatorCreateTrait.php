<?php
/**
 * SAM-10905: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract calculation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\Recalculate;

/**
 * Trait InvoiceRecalculatorCreateTrait
 * @package Sam\Invoice\Legacy\Calculate\Recalculate
 */
trait LegacyInvoiceRecalculatorCreateTrait
{
    protected ?LegacyInvoiceRecalculator $legacyInvoiceRecalculator = null;

    /**
     * @return LegacyInvoiceRecalculator
     */
    protected function createInvoiceRecalculator(): LegacyInvoiceRecalculator
    {
        return $this->legacyInvoiceRecalculator ?: LegacyInvoiceRecalculator::new();
    }

    /**
     * @param LegacyInvoiceRecalculator $legacyInvoiceRecalculator
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceRecalculator(LegacyInvoiceRecalculator $legacyInvoiceRecalculator): static
    {
        $this->legacyInvoiceRecalculator = $legacyInvoiceRecalculator;
        return $this;
    }
}
