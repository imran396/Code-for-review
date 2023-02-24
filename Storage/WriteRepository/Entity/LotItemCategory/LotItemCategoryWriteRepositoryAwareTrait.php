<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCategory;

trait LotItemCategoryWriteRepositoryAwareTrait
{
    protected ?LotItemCategoryWriteRepository $lotItemCategoryWriteRepository = null;

    protected function getLotItemCategoryWriteRepository(): LotItemCategoryWriteRepository
    {
        if ($this->lotItemCategoryWriteRepository === null) {
            $this->lotItemCategoryWriteRepository = LotItemCategoryWriteRepository::new();
        }
        return $this->lotItemCategoryWriteRepository;
    }

    /**
     * @param LotItemCategoryWriteRepository $lotItemCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemCategoryWriteRepository(LotItemCategoryWriteRepository $lotItemCategoryWriteRepository): static
    {
        $this->lotItemCategoryWriteRepository = $lotItemCategoryWriteRepository;
        return $this;
    }
}
