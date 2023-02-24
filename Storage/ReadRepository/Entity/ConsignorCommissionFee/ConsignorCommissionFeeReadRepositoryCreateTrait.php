<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee;

trait ConsignorCommissionFeeReadRepositoryCreateTrait
{
    protected ?ConsignorCommissionFeeReadRepository $consignorCommissionFeeReadRepository = null;

    protected function createConsignorCommissionFeeReadRepository(): ConsignorCommissionFeeReadRepository
    {
        return $this->consignorCommissionFeeReadRepository ?: ConsignorCommissionFeeReadRepository::new();
    }

    /**
     * @param ConsignorCommissionFeeReadRepository $consignorCommissionFeeReadRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeReadRepository(ConsignorCommissionFeeReadRepository $consignorCommissionFeeReadRepository): static
    {
        $this->consignorCommissionFeeReadRepository = $consignorCommissionFeeReadRepository;
        return $this;
    }
}
