<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Update;

/**
 * Trait SingleStackedTaxInvoicePaidMarkerCreateTrait
 * @package Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Update
 */
trait SingleStackedTaxInvoicePaidMarkerCreateTrait
{
    protected ?SingleStackedTaxInvoicePaidMarker $singleStackedTaxInvoicePaidMarker = null;

    /**
     * @return SingleStackedTaxInvoicePaidMarker
     */
    protected function createSingleStackedTaxInvoicePaidMarker(): SingleStackedTaxInvoicePaidMarker
    {
        return $this->singleStackedTaxInvoicePaidMarker ?: SingleStackedTaxInvoicePaidMarker::new();
    }

    /**
     * @param SingleStackedTaxInvoicePaidMarker $singleStackedTaxInvoicePaidMarker
     * @return $this
     * @internal
     */
    public function setSingleStackedTaxInvoicePaidMarker(SingleStackedTaxInvoicePaidMarker $singleStackedTaxInvoicePaidMarker): static
    {
        $this->singleStackedTaxInvoicePaidMarker = $singleStackedTaxInvoicePaidMarker;
        return $this;
    }
}
