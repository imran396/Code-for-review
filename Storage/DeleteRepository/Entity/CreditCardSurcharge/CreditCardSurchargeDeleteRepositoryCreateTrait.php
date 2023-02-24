<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CreditCardSurcharge;

trait CreditCardSurchargeDeleteRepositoryCreateTrait
{
    protected ?CreditCardSurchargeDeleteRepository $creditCardSurchargeDeleteRepository = null;

    protected function createCreditCardSurchargeDeleteRepository(): CreditCardSurchargeDeleteRepository
    {
        return $this->creditCardSurchargeDeleteRepository ?: CreditCardSurchargeDeleteRepository::new();
    }

    /**
     * @param CreditCardSurchargeDeleteRepository $creditCardSurchargeDeleteRepository
     * @return static
     * @internal
     */
    public function setCreditCardSurchargeDeleteRepository(CreditCardSurchargeDeleteRepository $creditCardSurchargeDeleteRepository): static
    {
        $this->creditCardSurchargeDeleteRepository = $creditCardSurchargeDeleteRepository;
        return $this;
    }
}
