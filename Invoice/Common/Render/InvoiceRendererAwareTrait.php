<?php
/**
 * Trait for Invoice Renderer
 *
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render;

/**
 * Trait InvoiceRendererAwareTrait
 * @package Sam\Invoice\Common\Render
 */
trait InvoiceRendererAwareTrait
{
    /**
     * @var InvoiceRenderer|null
     */
    protected ?InvoiceRenderer $invoiceRenderer = null;

    /**
     * @return InvoiceRenderer
     */
    protected function getInvoiceRenderer(): InvoiceRenderer
    {
        if ($this->invoiceRenderer === null) {
            $this->invoiceRenderer = InvoiceRenderer::new();
        }
        return $this->invoiceRenderer;
    }

    /**
     * @param InvoiceRenderer $invoiceRenderer
     * @return static
     * @internal
     */
    public function setInvoiceRenderer(InvoiceRenderer $invoiceRenderer): static
    {
        $this->invoiceRenderer = $invoiceRenderer;
        return $this;
    }
}
