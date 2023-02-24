<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItem;

trait LotItemWriteRepositoryAwareTrait
{
    protected ?LotItemWriteRepository $lotItemWriteRepository = null;

    protected function getLotItemWriteRepository(): LotItemWriteRepository
    {
        if ($this->lotItemWriteRepository === null) {
            $this->lotItemWriteRepository = LotItemWriteRepository::new();
        }
        return $this->lotItemWriteRepository;
    }

    /**
     * @param LotItemWriteRepository $lotItemWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemWriteRepository(LotItemWriteRepository $lotItemWriteRepository): static
    {
        $this->lotItemWriteRepository = $lotItemWriteRepository;
        return $this;
    }
}
