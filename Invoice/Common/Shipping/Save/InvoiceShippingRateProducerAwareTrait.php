<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\Save;

/**
 * Trait InvoiceShippingRateProducerAwareTrait
 * @package Sam\Invoice\Common\Shipping\Save
 */
trait InvoiceShippingRateProducerAwareTrait
{
    /**
     * @var InvoiceShippingRateProducer|null
     */
    protected ?InvoiceShippingRateProducer $invoiceShippingRateProducer = null;

    /**
     * @return InvoiceShippingRateProducer
     */
    protected function getInvoiceShippingRateProducer(): InvoiceShippingRateProducer
    {
        if ($this->invoiceShippingRateProducer === null) {
            $this->invoiceShippingRateProducer = InvoiceShippingRateProducer::new();
        }
        return $this->invoiceShippingRateProducer;
    }

    /**
     * @param InvoiceShippingRateProducer $invoiceShippingRateProducer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceShippingRateProducer(InvoiceShippingRateProducer $invoiceShippingRateProducer): static
    {
        $this->invoiceShippingRateProducer = $invoiceShippingRateProducer;
        return $this;
    }
}
