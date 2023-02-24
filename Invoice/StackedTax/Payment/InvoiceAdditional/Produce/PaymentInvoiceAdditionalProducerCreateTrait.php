<?php
/**
 * SAM-11338: Stacked Tax. Public page Invoice with CC surcharge and Service tax on surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce;

/**
 * Trait PaymentInvoiceAdditionalProducerCreateTrait
 * @package Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce
 */
trait PaymentInvoiceAdditionalProducerCreateTrait
{
    protected ?PaymentInvoiceAdditionalProducer $paymentInvoiceAdditionalProducer = null;

    /**
     * @return PaymentInvoiceAdditionalProducer
     */
    protected function createPaymentInvoiceAdditionalProducer(): PaymentInvoiceAdditionalProducer
    {
        return $this->paymentInvoiceAdditionalProducer ?: PaymentInvoiceAdditionalProducer::new();
    }

    /**
     * @param PaymentInvoiceAdditionalProducer $paymentInvoiceAdditionalProducer
     * @return static
     * @internal
     */
    public function setPaymentInvoiceAdditionalProducer(PaymentInvoiceAdditionalProducer $paymentInvoiceAdditionalProducer): static
    {
        $this->paymentInvoiceAdditionalProducer = $paymentInvoiceAdditionalProducer;
        return $this;
    }
}
