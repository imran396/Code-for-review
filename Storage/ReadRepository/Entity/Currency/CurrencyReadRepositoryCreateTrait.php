<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Currency;

trait CurrencyReadRepositoryCreateTrait
{
    protected ?CurrencyReadRepository $currencyReadRepository = null;

    protected function createCurrencyReadRepository(): CurrencyReadRepository
    {
        return $this->currencyReadRepository ?: CurrencyReadRepository::new();
    }

    /**
     * @param CurrencyReadRepository $currencyReadRepository
     * @return static
     * @internal
     */
    public function setCurrencyReadRepository(CurrencyReadRepository $currencyReadRepository): static
    {
        $this->currencyReadRepository = $currencyReadRepository;
        return $this;
    }
}
