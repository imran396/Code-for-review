<?php
/**
 * SAM-10903: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the Release Lot action
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

namespace Sam\Invoice\Legacy\ReleaseLot;

/**
 * Trait InvoiceLotReleaserCreateTrait
 * @package Sam\Invoice\Legacy\ReleaseLot
 */
trait LegacyInvoiceLotReleaserCreateTrait
{
    protected ?LegacyInvoiceLotReleaser $legacyInvoiceLotReleaser = null;

    /**
     * @return LegacyInvoiceLotReleaser
     */
    protected function createLegacyInvoiceLotReleaser(): LegacyInvoiceLotReleaser
    {
        return $this->legacyInvoiceLotReleaser ?: LegacyInvoiceLotReleaser::new();
    }

    /**
     * @param LegacyInvoiceLotReleaser $legacyInvoiceLotReleaser
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceLotReleaser(LegacyInvoiceLotReleaser $legacyInvoiceLotReleaser): static
    {
        $this->legacyInvoiceLotReleaser = $legacyInvoiceLotReleaser;
        return $this;
    }
}
