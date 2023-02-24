<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge;

/**
 * Trait AdditionalChargeProducerCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge
 */
trait InvoiceLineItemChargeProducerCreateTrait
{
    protected ?InvoiceLineItemChargeProducer $invoiceLineItemChargeProducer = null;

    /**
     * @return InvoiceLineItemChargeProducer
     */
    protected function createInvoiceLineItemChargeProducer(): InvoiceLineItemChargeProducer
    {
        return $this->invoiceLineItemChargeProducer ?: InvoiceLineItemChargeProducer::new();
    }

    /**
     * @param InvoiceLineItemChargeProducer $invoiceLineItemChargeProducer
     * @return $this
     * @internal
     */
    public function setInvoiceLineItemChargeProducer(InvoiceLineItemChargeProducer $invoiceLineItemChargeProducer): static
    {
        $this->invoiceLineItemChargeProducer = $invoiceLineItemChargeProducer;
        return $this;
    }
}
