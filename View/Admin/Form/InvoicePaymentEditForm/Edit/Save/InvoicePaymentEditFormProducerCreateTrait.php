<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Save;

/**
 * Trait InvoicePaymentEditFormProducerCreateTrait
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Save
 */
trait InvoicePaymentEditFormProducerCreateTrait
{
    protected ?InvoicePaymentEditFormProducer $invoicePaymentEditFormProducer = null;

    /**
     * @return InvoicePaymentEditFormProducer
     */
    protected function createInvoicePaymentEditFormProducer(): InvoicePaymentEditFormProducer
    {
        return $this->invoicePaymentEditFormProducer ?: InvoicePaymentEditFormProducer::new();
    }

    /**
     * @param InvoicePaymentEditFormProducer $invoicePaymentEditFormProducer
     * @return static
     * @internal
     */
    public function setInvoicePaymentEditFormProducer(InvoicePaymentEditFormProducer $invoicePaymentEditFormProducer): static
    {
        $this->invoicePaymentEditFormProducer = $invoicePaymentEditFormProducer;
        return $this;
    }
}
