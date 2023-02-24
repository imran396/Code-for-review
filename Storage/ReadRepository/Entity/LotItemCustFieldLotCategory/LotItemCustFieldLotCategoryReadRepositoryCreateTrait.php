<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustFieldLotCategory;

trait LotItemCustFieldLotCategoryReadRepositoryCreateTrait
{
    protected ?LotItemCustFieldLotCategoryReadRepository $lotItemCustFieldLotCategoryReadRepository = null;

    protected function createLotItemCustFieldLotCategoryReadRepository(): LotItemCustFieldLotCategoryReadRepository
    {
        return $this->lotItemCustFieldLotCategoryReadRepository ?: LotItemCustFieldLotCategoryReadRepository::new();
    }

    /**
     * @param LotItemCustFieldLotCategoryReadRepository $lotItemCustFieldLotCategoryReadRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldLotCategoryReadRepository(LotItemCustFieldLotCategoryReadRepository $lotItemCustFieldLotCategoryReadRepository): static
    {
        $this->lotItemCustFieldLotCategoryReadRepository = $lotItemCustFieldLotCategoryReadRepository;
        return $this;
    }
}
