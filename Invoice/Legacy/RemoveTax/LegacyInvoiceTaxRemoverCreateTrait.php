<?php
/**
 * SAM-10901: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the "Remove Taxes" button action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\RemoveTax;

/**
 * Trait InvoiceTaxRemoverCreateTrait
 * @package Sam\Invoice\Legacy\RemoveTax
 */
trait LegacyInvoiceTaxRemoverCreateTrait
{
    protected ?LegacyInvoiceTaxRemover $legacyInvoiceTaxRemover = null;

    /**
     * @return LegacyInvoiceTaxRemover
     */
    protected function createLegacyInvoiceTaxRemover(): LegacyInvoiceTaxRemover
    {
        return $this->legacyInvoiceTaxRemover ?: LegacyInvoiceTaxRemover::new();
    }

    /**
     * @param LegacyInvoiceTaxRemover $legacyInvoiceTaxRemover
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceTaxRemover(LegacyInvoiceTaxRemover $legacyInvoiceTaxRemover): static
    {
        $this->legacyInvoiceTaxRemover = $legacyInvoiceTaxRemover;
        return $this;
    }
}
