<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemCustField;

trait LotItemCustFieldDeleteRepositoryCreateTrait
{
    protected ?LotItemCustFieldDeleteRepository $lotItemCustFieldDeleteRepository = null;

    protected function createLotItemCustFieldDeleteRepository(): LotItemCustFieldDeleteRepository
    {
        return $this->lotItemCustFieldDeleteRepository ?: LotItemCustFieldDeleteRepository::new();
    }

    /**
     * @param LotItemCustFieldDeleteRepository $lotItemCustFieldDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustFieldDeleteRepository(LotItemCustFieldDeleteRepository $lotItemCustFieldDeleteRepository): static
    {
        $this->lotItemCustFieldDeleteRepository = $lotItemCustFieldDeleteRepository;
        return $this;
    }
}
