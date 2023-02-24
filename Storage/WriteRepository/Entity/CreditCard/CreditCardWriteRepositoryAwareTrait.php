<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CreditCard;

trait CreditCardWriteRepositoryAwareTrait
{
    protected ?CreditCardWriteRepository $creditCardWriteRepository = null;

    protected function getCreditCardWriteRepository(): CreditCardWriteRepository
    {
        if ($this->creditCardWriteRepository === null) {
            $this->creditCardWriteRepository = CreditCardWriteRepository::new();
        }
        return $this->creditCardWriteRepository;
    }

    /**
     * @param CreditCardWriteRepository $creditCardWriteRepository
     * @return static
     * @internal
     */
    public function setCreditCardWriteRepository(CreditCardWriteRepository $creditCardWriteRepository): static
    {
        $this->creditCardWriteRepository = $creditCardWriteRepository;
        return $this;
    }
}
