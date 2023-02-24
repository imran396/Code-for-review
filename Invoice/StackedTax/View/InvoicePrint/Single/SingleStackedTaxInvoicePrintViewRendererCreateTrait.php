<?php
/**
 * SAM-11097: Stacked Tax. Multiple Invoice Printing view
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\InvoicePrint\Single;

/**
 * Trait SingleStackedTaxInvoicePrintViewRendererCreateTrait
 * @package Sam\Invoice\StackedTax\View\InvoicePrint\Single
 */
trait SingleStackedTaxInvoicePrintViewRendererCreateTrait
{
    protected ?SingleStackedTaxInvoicePrintViewRenderer $singleStackedTaxInvoicePrintViewRenderer = null;

    /**
     * @return SingleStackedTaxInvoicePrintViewRenderer
     */
    protected function createSingleStackedTaxInvoicePrintViewRenderer(): SingleStackedTaxInvoicePrintViewRenderer
    {
        return $this->singleStackedTaxInvoicePrintViewRenderer ?: SingleStackedTaxInvoicePrintViewRenderer::new();
    }

    /**
     * @param SingleStackedTaxInvoicePrintViewRenderer $singleStackedTaxInvoicePrintViewRenderer
     * @return static
     * @internal
     */
    public function setSingleStackedTaxInvoicePrintViewRenderer(SingleStackedTaxInvoicePrintViewRenderer $singleStackedTaxInvoicePrintViewRenderer): static
    {
        $this->singleStackedTaxInvoicePrintViewRenderer = $singleStackedTaxInvoicePrintViewRenderer;
        return $this;
    }
}
