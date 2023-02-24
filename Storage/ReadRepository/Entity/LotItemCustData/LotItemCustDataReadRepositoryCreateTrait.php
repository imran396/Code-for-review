<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustData;

trait LotItemCustDataReadRepositoryCreateTrait
{
    protected ?LotItemCustDataReadRepository $lotItemCustDataReadRepository = null;

    protected function createLotItemCustDataReadRepository(): LotItemCustDataReadRepository
    {
        return $this->lotItemCustDataReadRepository ?: LotItemCustDataReadRepository::new();
    }

    /**
     * @param LotItemCustDataReadRepository $lotItemCustDataReadRepository
     * @return static
     * @internal
     */
    public function setLotItemCustDataReadRepository(LotItemCustDataReadRepository $lotItemCustDataReadRepository): static
    {
        $this->lotItemCustDataReadRepository = $lotItemCustDataReadRepository;
        return $this;
    }
}
