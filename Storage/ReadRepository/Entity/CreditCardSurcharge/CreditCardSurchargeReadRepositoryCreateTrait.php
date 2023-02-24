<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CreditCardSurcharge;

trait CreditCardSurchargeReadRepositoryCreateTrait
{
    protected ?CreditCardSurchargeReadRepository $creditCardSurchargeReadRepository = null;

    protected function createCreditCardSurchargeReadRepository(): CreditCardSurchargeReadRepository
    {
        return $this->creditCardSurchargeReadRepository ?: CreditCardSurchargeReadRepository::new();
    }

    /**
     * @param CreditCardSurchargeReadRepository $creditCardSurchargeReadRepository
     * @return static
     * @internal
     */
    public function setCreditCardSurchargeReadRepository(CreditCardSurchargeReadRepository $creditCardSurchargeReadRepository): static
    {
        $this->creditCardSurchargeReadRepository = $creditCardSurchargeReadRepository;
        return $this;
    }
}
