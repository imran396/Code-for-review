<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCategory;

trait LotItemCategoryReadRepositoryCreateTrait
{
    protected ?LotItemCategoryReadRepository $lotItemCategoryReadRepository = null;

    protected function createLotItemCategoryReadRepository(): LotItemCategoryReadRepository
    {
        return $this->lotItemCategoryReadRepository ?: LotItemCategoryReadRepository::new();
    }

    /**
     * @param LotItemCategoryReadRepository $lotItemCategoryReadRepository
     * @return static
     * @internal
     */
    public function setLotItemCategoryReadRepository(LotItemCategoryReadRepository $lotItemCategoryReadRepository): static
    {
        $this->lotItemCategoryReadRepository = $lotItemCategoryReadRepository;
        return $this;
    }
}
