<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce;

trait PaymentProducerCreateTrait
{
    /**
     * @var PaymentProducer|null
     */
    protected ?PaymentProducer $paymentProducer = null;

    /**
     * @return PaymentProducer
     */
    protected function createPaymentProducer(): PaymentProducer
    {
        return $this->paymentProducer ?: PaymentProducer::new();
    }

    /**
     * @param PaymentProducer $producer
     * @return static
     * @internal
     */
    public function setPaymentProducer(PaymentProducer $producer): static
    {
        $this->paymentProducer = $producer;
        return $this;
    }
}
