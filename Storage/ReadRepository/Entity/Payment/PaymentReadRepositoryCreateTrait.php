<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Payment;

trait PaymentReadRepositoryCreateTrait
{
    protected ?PaymentReadRepository $paymentReadRepository = null;

    protected function createPaymentReadRepository(): PaymentReadRepository
    {
        return $this->paymentReadRepository ?: PaymentReadRepository::new();
    }

    /**
     * @param PaymentReadRepository $paymentReadRepository
     * @return static
     * @internal
     */
    public function setPaymentReadRepository(PaymentReadRepository $paymentReadRepository): static
    {
        $this->paymentReadRepository = $paymentReadRepository;
        return $this;
    }
}
