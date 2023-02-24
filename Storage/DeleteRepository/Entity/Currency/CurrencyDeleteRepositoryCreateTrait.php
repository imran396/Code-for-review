<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Currency;

trait CurrencyDeleteRepositoryCreateTrait
{
    protected ?CurrencyDeleteRepository $currencyDeleteRepository = null;

    protected function createCurrencyDeleteRepository(): CurrencyDeleteRepository
    {
        return $this->currencyDeleteRepository ?: CurrencyDeleteRepository::new();
    }

    /**
     * @param CurrencyDeleteRepository $currencyDeleteRepository
     * @return static
     * @internal
     */
    public function setCurrencyDeleteRepository(CurrencyDeleteRepository $currencyDeleteRepository): static
    {
        $this->currencyDeleteRepository = $currencyDeleteRepository;
        return $this;
    }
}
