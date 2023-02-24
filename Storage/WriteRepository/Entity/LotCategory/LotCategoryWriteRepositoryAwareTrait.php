<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategory;

trait LotCategoryWriteRepositoryAwareTrait
{
    protected ?LotCategoryWriteRepository $lotCategoryWriteRepository = null;

    protected function getLotCategoryWriteRepository(): LotCategoryWriteRepository
    {
        if ($this->lotCategoryWriteRepository === null) {
            $this->lotCategoryWriteRepository = LotCategoryWriteRepository::new();
        }
        return $this->lotCategoryWriteRepository;
    }

    /**
     * @param LotCategoryWriteRepository $lotCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryWriteRepository(LotCategoryWriteRepository $lotCategoryWriteRepository): static
    {
        $this->lotCategoryWriteRepository = $lotCategoryWriteRepository;
        return $this;
    }
}
