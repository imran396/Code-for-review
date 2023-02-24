<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Consignor;

trait ConsignorWriteRepositoryAwareTrait
{
    protected ?ConsignorWriteRepository $consignorWriteRepository = null;

    protected function getConsignorWriteRepository(): ConsignorWriteRepository
    {
        if ($this->consignorWriteRepository === null) {
            $this->consignorWriteRepository = ConsignorWriteRepository::new();
        }
        return $this->consignorWriteRepository;
    }

    /**
     * @param ConsignorWriteRepository $consignorWriteRepository
     * @return static
     * @internal
     */
    public function setConsignorWriteRepository(ConsignorWriteRepository $consignorWriteRepository): static
    {
        $this->consignorWriteRepository = $consignorWriteRepository;
        return $this;
    }
}
