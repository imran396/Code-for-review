<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Save;

/**
 * Trait InvoiceLineItemProducerCreateTrait
 * @package Sam\Invoice\Common\LineItem\Edit\Save
 */
trait InvoiceLineItemProducerCreateTrait
{
    /**
     * @var InvoiceLineItemProducer|null
     */
    protected ?InvoiceLineItemProducer $invoiceLineItemProducer = null;

    /**
     * @return InvoiceLineItemProducer
     */
    protected function createInvoiceLineItemProducer(): InvoiceLineItemProducer
    {
        return $this->invoiceLineItemProducer ?: InvoiceLineItemProducer::new();
    }

    /**
     * @param InvoiceLineItemProducer $invoiceLineItemProducer
     * @return $this
     * @internal
     */
    public function setInvoiceLineItemProducer(InvoiceLineItemProducer $invoiceLineItemProducer): static
    {
        $this->invoiceLineItemProducer = $invoiceLineItemProducer;
        return $this;
    }
}
