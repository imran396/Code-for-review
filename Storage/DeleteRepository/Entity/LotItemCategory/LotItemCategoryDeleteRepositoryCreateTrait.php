<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemCategory;

trait LotItemCategoryDeleteRepositoryCreateTrait
{
    protected ?LotItemCategoryDeleteRepository $lotItemCategoryDeleteRepository = null;

    protected function createLotItemCategoryDeleteRepository(): LotItemCategoryDeleteRepository
    {
        return $this->lotItemCategoryDeleteRepository ?: LotItemCategoryDeleteRepository::new();
    }

    /**
     * @param LotItemCategoryDeleteRepository $lotItemCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemCategoryDeleteRepository(LotItemCategoryDeleteRepository $lotItemCategoryDeleteRepository): static
    {
        $this->lotItemCategoryDeleteRepository = $lotItemCategoryDeleteRepository;
        return $this;
    }
}
