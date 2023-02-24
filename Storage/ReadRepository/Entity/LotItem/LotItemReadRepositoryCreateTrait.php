<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItem;

trait LotItemReadRepositoryCreateTrait
{
    protected ?LotItemReadRepository $lotItemReadRepository = null;

    protected function createLotItemReadRepository(): LotItemReadRepository
    {
        return $this->lotItemReadRepository ?: LotItemReadRepository::new();
    }

    /**
     * @param LotItemReadRepository $lotItemReadRepository
     * @return static
     * @internal
     */
    public function setLotItemReadRepository(LotItemReadRepository $lotItemReadRepository): static
    {
        $this->lotItemReadRepository = $lotItemReadRepository;
        return $this;
    }
}
