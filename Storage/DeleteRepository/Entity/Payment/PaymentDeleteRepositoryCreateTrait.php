<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Payment;

trait PaymentDeleteRepositoryCreateTrait
{
    protected ?PaymentDeleteRepository $paymentDeleteRepository = null;

    protected function createPaymentDeleteRepository(): PaymentDeleteRepository
    {
        return $this->paymentDeleteRepository ?: PaymentDeleteRepository::new();
    }

    /**
     * @param PaymentDeleteRepository $paymentDeleteRepository
     * @return static
     * @internal
     */
    public function setPaymentDeleteRepository(PaymentDeleteRepository $paymentDeleteRepository): static
    {
        $this->paymentDeleteRepository = $paymentDeleteRepository;
        return $this;
    }
}
