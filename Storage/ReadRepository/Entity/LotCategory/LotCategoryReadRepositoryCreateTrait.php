<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategory;

trait LotCategoryReadRepositoryCreateTrait
{
    protected ?LotCategoryReadRepository $lotCategoryReadRepository = null;

    protected function createLotCategoryReadRepository(): LotCategoryReadRepository
    {
        return $this->lotCategoryReadRepository ?: LotCategoryReadRepository::new();
    }

    /**
     * @param LotCategoryReadRepository $lotCategoryReadRepository
     * @return static
     * @internal
     */
    public function setLotCategoryReadRepository(LotCategoryReadRepository $lotCategoryReadRepository): static
    {
        $this->lotCategoryReadRepository = $lotCategoryReadRepository;
        return $this;
    }
}
