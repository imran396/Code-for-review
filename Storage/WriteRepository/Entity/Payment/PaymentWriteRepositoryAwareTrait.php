<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Payment;

trait PaymentWriteRepositoryAwareTrait
{
    protected ?PaymentWriteRepository $paymentWriteRepository = null;

    protected function getPaymentWriteRepository(): PaymentWriteRepository
    {
        if ($this->paymentWriteRepository === null) {
            $this->paymentWriteRepository = PaymentWriteRepository::new();
        }
        return $this->paymentWriteRepository;
    }

    /**
     * @param PaymentWriteRepository $paymentWriteRepository
     * @return static
     * @internal
     */
    public function setPaymentWriteRepository(PaymentWriteRepository $paymentWriteRepository): static
    {
        $this->paymentWriteRepository = $paymentWriteRepository;
        return $this;
    }
}
