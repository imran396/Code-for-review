<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Consignor;

trait ConsignorReadRepositoryCreateTrait
{
    protected ?ConsignorReadRepository $consignorReadRepository = null;

    protected function createConsignorReadRepository(): ConsignorReadRepository
    {
        return $this->consignorReadRepository ?: ConsignorReadRepository::new();
    }

    /**
     * @param ConsignorReadRepository $consignorReadRepository
     * @return static
     * @internal
     */
    public function setConsignorReadRepository(ConsignorReadRepository $consignorReadRepository): static
    {
        $this->consignorReadRepository = $consignorReadRepository;
        return $this;
    }
}
