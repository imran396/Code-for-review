<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemCustData;

trait LotItemCustDataDeleteRepositoryCreateTrait
{
    protected ?LotItemCustDataDeleteRepository $lotItemCustDataDeleteRepository = null;

    protected function createLotItemCustDataDeleteRepository(): LotItemCustDataDeleteRepository
    {
        return $this->lotItemCustDataDeleteRepository ?: LotItemCustDataDeleteRepository::new();
    }

    /**
     * @param LotItemCustDataDeleteRepository $lotItemCustDataDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustDataDeleteRepository(LotItemCustDataDeleteRepository $lotItemCustDataDeleteRepository): static
    {
        $this->lotItemCustDataDeleteRepository = $lotItemCustDataDeleteRepository;
        return $this;
    }
}
