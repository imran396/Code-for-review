<?php
/**
 * SAM-11177: Stacked Tax. Invoice e-mail view - (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\IsolatedInvoiceView;

/**
 * Trait StackedTaxIsolatedInvoiceViewRendererCreateTrait
 * @package Sam\Invoice\StackedTax\View\IsolatedInvoiceView
 */
trait StackedTaxIsolatedInvoiceViewRendererCreateTrait
{
    protected ?StackedTaxIsolatedInvoiceViewRenderer $stackedTaxIsolatedInvoiceViewRenderer = null;

    /**
     * @return StackedTaxIsolatedInvoiceViewRenderer
     */
    protected function createStackedTaxIsolatedInvoiceViewRenderer(): StackedTaxIsolatedInvoiceViewRenderer
    {
        return $this->stackedTaxIsolatedInvoiceViewRenderer ?: StackedTaxIsolatedInvoiceViewRenderer::new();
    }

    /**
     * @param StackedTaxIsolatedInvoiceViewRenderer $stackedTaxIsolatedInvoiceViewRenderer
     * @return static
     * @internal
     */
    public function setStackedTaxIsolatedInvoiceViewRenderer(StackedTaxIsolatedInvoiceViewRenderer $stackedTaxIsolatedInvoiceViewRenderer): static
    {
        $this->stackedTaxIsolatedInvoiceViewRenderer = $stackedTaxIsolatedInvoiceViewRenderer;
        return $this;
    }
}
