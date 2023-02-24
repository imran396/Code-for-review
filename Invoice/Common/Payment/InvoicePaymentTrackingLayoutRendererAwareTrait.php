<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Payment;

/**
 * Trait InvoicePaymentTrackingLayoutRendererAwareTrait
 * @package Sam\Invoice\Common\Payment
 */
trait InvoicePaymentTrackingLayoutRendererAwareTrait
{
    /**
     * @var InvoicePaymentTrackingLayoutRenderer|null
     */
    protected ?InvoicePaymentTrackingLayoutRenderer $invoicePaymentTrackingLayoutRenderer = null;

    /**
     * @return InvoicePaymentTrackingLayoutRenderer
     */
    protected function getInvoicePaymentTrackingLayoutRenderer(): InvoicePaymentTrackingLayoutRenderer
    {
        if ($this->invoicePaymentTrackingLayoutRenderer === null) {
            $this->invoicePaymentTrackingLayoutRenderer = InvoicePaymentTrackingLayoutRenderer::new();
        }
        return $this->invoicePaymentTrackingLayoutRenderer;
    }

    /**
     * @param InvoicePaymentTrackingLayoutRenderer $invoicePaymentTrackingLayoutRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoicePaymentTrackingLayoutRenderer(InvoicePaymentTrackingLayoutRenderer $invoicePaymentTrackingLayoutRenderer): static
    {
        $this->invoicePaymentTrackingLayoutRenderer = $invoicePaymentTrackingLayoutRenderer;
        return $this;
    }
}
