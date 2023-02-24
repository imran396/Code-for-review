<?php
/**
 *
 * SAM-4554: Move Invoice_Bidder logic to InvoiceUserLoader, InvoiceUserProducer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/13/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Bidder\Save;

/**
 * Trait InvoiceUserProducerAwareTrait
 * @package Sam\Invoice\Common\Bidder\Save
 */
trait InvoiceUserProducerAwareTrait
{
    /**
     * @var InvoiceUserProducer|null
     */
    protected ?InvoiceUserProducer $invoiceUserProducer = null;

    /**
     * @return InvoiceUserProducer
     */
    protected function getInvoiceUserProducer(): InvoiceUserProducer
    {
        if ($this->invoiceUserProducer === null) {
            $this->invoiceUserProducer = InvoiceUserProducer::new();
        }
        return $this->invoiceUserProducer;
    }

    /**
     * @param InvoiceUserProducer $invoiceUserProducer
     * @return static
     * @internal
     */
    public function setInvoiceUserProducer(InvoiceUserProducer $invoiceUserProducer): static
    {
        $this->invoiceUserProducer = $invoiceUserProducer;
        return $this;
    }
}
