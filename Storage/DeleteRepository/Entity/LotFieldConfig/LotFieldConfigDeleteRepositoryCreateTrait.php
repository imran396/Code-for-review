<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotFieldConfig;

trait LotFieldConfigDeleteRepositoryCreateTrait
{
    protected ?LotFieldConfigDeleteRepository $lotFieldConfigDeleteRepository = null;

    protected function createLotFieldConfigDeleteRepository(): LotFieldConfigDeleteRepository
    {
        return $this->lotFieldConfigDeleteRepository ?: LotFieldConfigDeleteRepository::new();
    }

    /**
     * @param LotFieldConfigDeleteRepository $lotFieldConfigDeleteRepository
     * @return static
     * @internal
     */
    public function setLotFieldConfigDeleteRepository(LotFieldConfigDeleteRepository $lotFieldConfigDeleteRepository): static
    {
        $this->lotFieldConfigDeleteRepository = $lotFieldConfigDeleteRepository;
        return $this;
    }
}
