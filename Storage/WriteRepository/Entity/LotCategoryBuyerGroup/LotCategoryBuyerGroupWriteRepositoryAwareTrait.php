<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryBuyerGroup;

trait LotCategoryBuyerGroupWriteRepositoryAwareTrait
{
    protected ?LotCategoryBuyerGroupWriteRepository $lotCategoryBuyerGroupWriteRepository = null;

    protected function getLotCategoryBuyerGroupWriteRepository(): LotCategoryBuyerGroupWriteRepository
    {
        if ($this->lotCategoryBuyerGroupWriteRepository === null) {
            $this->lotCategoryBuyerGroupWriteRepository = LotCategoryBuyerGroupWriteRepository::new();
        }
        return $this->lotCategoryBuyerGroupWriteRepository;
    }

    /**
     * @param LotCategoryBuyerGroupWriteRepository $lotCategoryBuyerGroupWriteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryBuyerGroupWriteRepository(LotCategoryBuyerGroupWriteRepository $lotCategoryBuyerGroupWriteRepository): static
    {
        $this->lotCategoryBuyerGroupWriteRepository = $lotCategoryBuyerGroupWriteRepository;
        return $this;
    }
}
