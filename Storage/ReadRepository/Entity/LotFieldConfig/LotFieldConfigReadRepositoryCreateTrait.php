<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotFieldConfig;

trait LotFieldConfigReadRepositoryCreateTrait
{
    protected ?LotFieldConfigReadRepository $lotFieldConfigReadRepository = null;

    protected function createLotFieldConfigReadRepository(): LotFieldConfigReadRepository
    {
        return $this->lotFieldConfigReadRepository ?: LotFieldConfigReadRepository::new();
    }

    /**
     * @param LotFieldConfigReadRepository $lotFieldConfigReadRepository
     * @return static
     * @internal
     */
    public function setLotFieldConfigReadRepository(LotFieldConfigReadRepository $lotFieldConfigReadRepository): static
    {
        $this->lotFieldConfigReadRepository = $lotFieldConfigReadRepository;
        return $this;
    }
}
