<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Currency;

trait CurrencyWriteRepositoryAwareTrait
{
    protected ?CurrencyWriteRepository $currencyWriteRepository = null;

    protected function getCurrencyWriteRepository(): CurrencyWriteRepository
    {
        if ($this->currencyWriteRepository === null) {
            $this->currencyWriteRepository = CurrencyWriteRepository::new();
        }
        return $this->currencyWriteRepository;
    }

    /**
     * @param CurrencyWriteRepository $currencyWriteRepository
     * @return static
     * @internal
     */
    public function setCurrencyWriteRepository(CurrencyWriteRepository $currencyWriteRepository): static
    {
        $this->currencyWriteRepository = $currencyWriteRepository;
        return $this;
    }
}
