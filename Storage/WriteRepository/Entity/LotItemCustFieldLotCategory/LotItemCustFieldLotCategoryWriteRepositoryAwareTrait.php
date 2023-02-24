<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustFieldLotCategory;

trait LotItemCustFieldLotCategoryWriteRepositoryAwareTrait
{
    protected ?LotItemCustFieldLotCategoryWriteRepository $lotItemCustFieldLotCategoryWriteRepository = null;

    protected function getLotItemCustFieldLotCategoryWriteRepository(): LotItemCustFieldLotCategoryWriteRepository
    {
        if ($this->lotItemCustFieldLotCategoryWriteRepository === null) {
            $this->lotItemCustFieldLotCategoryWriteRepository = LotItemCustFieldLotCategoryWriteRepository::new();
        }
        return $this->lotItemCustFieldLotCategoryWriteRepository;
    }

    /**
     * @param LotItemCustFieldLotCategoryWriteRepository $lotItemCustFieldLotCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldLotCategoryWriteRepository(LotItemCustFieldLotCategoryWriteRepository $lotItemCustFieldLotCategoryWriteRepository): static
    {
        $this->lotItemCustFieldLotCategoryWriteRepository = $lotItemCustFieldLotCategoryWriteRepository;
        return $this;
    }
}
