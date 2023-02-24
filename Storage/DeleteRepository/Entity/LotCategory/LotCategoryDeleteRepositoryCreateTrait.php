<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategory;

trait LotCategoryDeleteRepositoryCreateTrait
{
    protected ?LotCategoryDeleteRepository $lotCategoryDeleteRepository = null;

    protected function createLotCategoryDeleteRepository(): LotCategoryDeleteRepository
    {
        return $this->lotCategoryDeleteRepository ?: LotCategoryDeleteRepository::new();
    }

    /**
     * @param LotCategoryDeleteRepository $lotCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryDeleteRepository(LotCategoryDeleteRepository $lotCategoryDeleteRepository): static
    {
        $this->lotCategoryDeleteRepository = $lotCategoryDeleteRepository;
        return $this;
    }
}
