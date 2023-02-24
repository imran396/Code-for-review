<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotFieldConfig;

trait LotFieldConfigWriteRepositoryAwareTrait
{
    protected ?LotFieldConfigWriteRepository $lotFieldConfigWriteRepository = null;

    protected function getLotFieldConfigWriteRepository(): LotFieldConfigWriteRepository
    {
        if ($this->lotFieldConfigWriteRepository === null) {
            $this->lotFieldConfigWriteRepository = LotFieldConfigWriteRepository::new();
        }
        return $this->lotFieldConfigWriteRepository;
    }

    /**
     * @param LotFieldConfigWriteRepository $lotFieldConfigWriteRepository
     * @return static
     * @internal
     */
    public function setLotFieldConfigWriteRepository(LotFieldConfigWriteRepository $lotFieldConfigWriteRepository): static
    {
        $this->lotFieldConfigWriteRepository = $lotFieldConfigWriteRepository;
        return $this;
    }
}
