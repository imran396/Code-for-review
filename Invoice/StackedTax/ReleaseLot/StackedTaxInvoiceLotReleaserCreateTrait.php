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

namespace Sam\Invoice\StackedTax\ReleaseLot;

trait StackedTaxInvoiceLotReleaserCreateTrait
{
    protected ?StackedTaxInvoiceLotReleaser $stackedTaxInvoiceLotReleaser = null;

    /**
     * @return StackedTaxInvoiceLotReleaser
     */
    protected function createStackedTaxInvoiceLotReleaser(): StackedTaxInvoiceLotReleaser
    {
        return $this->stackedTaxInvoiceLotReleaser ?: StackedTaxInvoiceLotReleaser::new();
    }

    /**
     * @param StackedTaxInvoiceLotReleaser $legacyInvoiceLotReleaser
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceLotReleaser(StackedTaxInvoiceLotReleaser $legacyInvoiceLotReleaser): static
    {
        $this->stackedTaxInvoiceLotReleaser = $legacyInvoiceLotReleaser;
        return $this;
    }
}
