<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Consignor;

trait ConsignorDeleteRepositoryCreateTrait
{
    protected ?ConsignorDeleteRepository $consignorDeleteRepository = null;

    protected function createConsignorDeleteRepository(): ConsignorDeleteRepository
    {
        return $this->consignorDeleteRepository ?: ConsignorDeleteRepository::new();
    }

    /**
     * @param ConsignorDeleteRepository $consignorDeleteRepository
     * @return static
     * @internal
     */
    public function setConsignorDeleteRepository(ConsignorDeleteRepository $consignorDeleteRepository): static
    {
        $this->consignorDeleteRepository = $consignorDeleteRepository;
        return $this;
    }
}
