<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustField;

trait LotItemCustFieldReadRepositoryCreateTrait
{
    protected ?LotItemCustFieldReadRepository $lotItemCustFieldReadRepository = null;

    protected function createLotItemCustFieldReadRepository(): LotItemCustFieldReadRepository
    {
        return $this->lotItemCustFieldReadRepository ?: LotItemCustFieldReadRepository::new();
    }

    /**
     * @param LotItemCustFieldReadRepository $lotItemCustFieldReadRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldReadRepository(LotItemCustFieldReadRepository $lotItemCustFieldReadRepository): static
    {
        $this->lotItemCustFieldReadRepository = $lotItemCustFieldReadRepository;
        return $this;
    }
}
