<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CreditCard;

trait CreditCardReadRepositoryCreateTrait
{
    protected ?CreditCardReadRepository $creditCardReadRepository = null;

    protected function createCreditCardReadRepository(): CreditCardReadRepository
    {
        return $this->creditCardReadRepository ?: CreditCardReadRepository::new();
    }

    /**
     * @param CreditCardReadRepository $creditCardReadRepository
     * @return static
     * @internal
     */
    public function setCreditCardReadRepository(CreditCardReadRepository $creditCardReadRepository): static
    {
        $this->creditCardReadRepository = $creditCardReadRepository;
        return $this;
    }
}
