<?php
/**
 * SAM-8007: Invoice and settlement layout adjustments for custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\CustomField\Lot;

/**
 * Trait InvoiceLotCustomFieldRendererCreateTrait
 * @package Sam\Invoice\Common\CustomField\Lot
 */
trait InvoiceLotCustomFieldRendererCreateTrait
{
    protected ?InvoiceLotCustomFieldRenderer $invoiceLotCustomFieldRenderer = null;

    /**
     * @return InvoiceLotCustomFieldRenderer
     */
    protected function createInvoiceLotCustomFieldRenderer(): InvoiceLotCustomFieldRenderer
    {
        return $this->invoiceLotCustomFieldRenderer ?: InvoiceLotCustomFieldRenderer::new();
    }

    /**
     * @param InvoiceLotCustomFieldRenderer $invoiceLotCustomFieldRenderer
     * @return $this
     * @internal
     */
    public function setInvoiceLotCustomFieldRenderer(InvoiceLotCustomFieldRenderer $invoiceLotCustomFieldRenderer): static
    {
        $this->invoiceLotCustomFieldRenderer = $invoiceLotCustomFieldRenderer;
        return $this;
    }
}
