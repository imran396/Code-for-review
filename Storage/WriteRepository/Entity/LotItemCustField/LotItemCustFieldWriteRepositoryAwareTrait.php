<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustField;

trait LotItemCustFieldWriteRepositoryAwareTrait
{
    protected ?LotItemCustFieldWriteRepository $lotItemCustFieldWriteRepository = null;

    protected function getLotItemCustFieldWriteRepository(): LotItemCustFieldWriteRepository
    {
        if ($this->lotItemCustFieldWriteRepository === null) {
            $this->lotItemCustFieldWriteRepository = LotItemCustFieldWriteRepository::new();
        }
        return $this->lotItemCustFieldWriteRepository;
    }

    /**
     * @param LotItemCustFieldWriteRepository $lotItemCustFieldWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldWriteRepository(LotItemCustFieldWriteRepository $lotItemCustFieldWriteRepository): static
    {
        $this->lotItemCustFieldWriteRepository = $lotItemCustFieldWriteRepository;
        return $this;
    }
}
