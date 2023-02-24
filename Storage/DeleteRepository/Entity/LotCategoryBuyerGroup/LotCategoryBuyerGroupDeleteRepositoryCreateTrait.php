<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategoryBuyerGroup;

trait LotCategoryBuyerGroupDeleteRepositoryCreateTrait
{
    protected ?LotCategoryBuyerGroupDeleteRepository $lotCategoryBuyerGroupDeleteRepository = null;

    protected function createLotCategoryBuyerGroupDeleteRepository(): LotCategoryBuyerGroupDeleteRepository
    {
        return $this->lotCategoryBuyerGroupDeleteRepository ?: LotCategoryBuyerGroupDeleteRepository::new();
    }

    /**
     * @param LotCategoryBuyerGroupDeleteRepository $lotCategoryBuyerGroupDeleteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryBuyerGroupDeleteRepository(LotCategoryBuyerGroupDeleteRepository $lotCategoryBuyerGroupDeleteRepository): static
    {
        $this->lotCategoryBuyerGroupDeleteRepository = $lotCategoryBuyerGroupDeleteRepository;
        return $this;
    }
}
