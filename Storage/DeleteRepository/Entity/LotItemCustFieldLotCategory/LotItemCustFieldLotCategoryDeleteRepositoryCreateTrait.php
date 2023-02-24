<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemCustFieldLotCategory;

trait LotItemCustFieldLotCategoryDeleteRepositoryCreateTrait
{
    protected ?LotItemCustFieldLotCategoryDeleteRepository $lotItemCustFieldLotCategoryDeleteRepository = null;

    protected function createLotItemCustFieldLotCategoryDeleteRepository(): LotItemCustFieldLotCategoryDeleteRepository
    {
        return $this->lotItemCustFieldLotCategoryDeleteRepository ?: LotItemCustFieldLotCategoryDeleteRepository::new();
    }

    /**
     * @param LotItemCustFieldLotCategoryDeleteRepository $lotItemCustFieldLotCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldLotCategoryDeleteRepository(LotItemCustFieldLotCategoryDeleteRepository $lotItemCustFieldLotCategoryDeleteRepository): static
    {
        $this->lotItemCustFieldLotCategoryDeleteRepository = $lotItemCustFieldLotCategoryDeleteRepository;
        return $this;
    }
}
