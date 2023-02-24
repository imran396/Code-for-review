<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup;

trait LotCategoryBuyerGroupReadRepositoryCreateTrait
{
    protected ?LotCategoryBuyerGroupReadRepository $lotCategoryBuyerGroupReadRepository = null;

    protected function createLotCategoryBuyerGroupReadRepository(): LotCategoryBuyerGroupReadRepository
    {
        return $this->lotCategoryBuyerGroupReadRepository ?: LotCategoryBuyerGroupReadRepository::new();
    }

    /**
     * @param LotCategoryBuyerGroupReadRepository $lotCategoryBuyerGroupReadRepository
     * @return static
     * @internal
     */
    public function setLotCategoryBuyerGroupReadRepository(LotCategoryBuyerGroupReadRepository $lotCategoryBuyerGroupReadRepository): static
    {
        $this->lotCategoryBuyerGroupReadRepository = $lotCategoryBuyerGroupReadRepository;
        return $this;
    }
}
