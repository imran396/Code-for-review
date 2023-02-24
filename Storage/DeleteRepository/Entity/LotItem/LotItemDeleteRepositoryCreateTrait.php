<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItem;

trait LotItemDeleteRepositoryCreateTrait
{
    protected ?LotItemDeleteRepository $lotItemDeleteRepository = null;

    protected function createLotItemDeleteRepository(): LotItemDeleteRepository
    {
        return $this->lotItemDeleteRepository ?: LotItemDeleteRepository::new();
    }

    /**
     * @param LotItemDeleteRepository $lotItemDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemDeleteRepository(LotItemDeleteRepository $lotItemDeleteRepository): static
    {
        $this->lotItemDeleteRepository = $lotItemDeleteRepository;
        return $this;
    }
}
