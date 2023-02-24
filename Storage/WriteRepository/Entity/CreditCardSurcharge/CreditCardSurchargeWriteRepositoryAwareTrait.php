<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CreditCardSurcharge;

trait CreditCardSurchargeWriteRepositoryAwareTrait
{
    protected ?CreditCardSurchargeWriteRepository $creditCardSurchargeWriteRepository = null;

    protected function getCreditCardSurchargeWriteRepository(): CreditCardSurchargeWriteRepository
    {
        if ($this->creditCardSurchargeWriteRepository === null) {
            $this->creditCardSurchargeWriteRepository = CreditCardSurchargeWriteRepository::new();
        }
        return $this->creditCardSurchargeWriteRepository;
    }

    /**
     * @param CreditCardSurchargeWriteRepository $creditCardSurchargeWriteRepository
     * @return static
     * @internal
     */
    public function setCreditCardSurchargeWriteRepository(CreditCardSurchargeWriteRepository $creditCardSurchargeWriteRepository): static
    {
        $this->creditCardSurchargeWriteRepository = $creditCardSurchargeWriteRepository;
        return $this;
    }
}
