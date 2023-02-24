<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CreditCard;

trait CreditCardDeleteRepositoryCreateTrait
{
    protected ?CreditCardDeleteRepository $creditCardDeleteRepository = null;

    protected function createCreditCardDeleteRepository(): CreditCardDeleteRepository
    {
        return $this->creditCardDeleteRepository ?: CreditCardDeleteRepository::new();
    }

    /**
     * @param CreditCardDeleteRepository $creditCardDeleteRepository
     * @return static
     * @internal
     */
    public function setCreditCardDeleteRepository(CreditCardDeleteRepository $creditCardDeleteRepository): static
    {
        $this->creditCardDeleteRepository = $creditCardDeleteRepository;
        return $this;
    }
}
