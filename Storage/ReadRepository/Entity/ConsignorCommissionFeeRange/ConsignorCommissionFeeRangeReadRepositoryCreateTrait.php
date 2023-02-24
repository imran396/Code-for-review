<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ConsignorCommissionFeeRange;

trait ConsignorCommissionFeeRangeReadRepositoryCreateTrait
{
    protected ?ConsignorCommissionFeeRangeReadRepository $consignorCommissionFeeRangeReadRepository = null;

    protected function createConsignorCommissionFeeRangeReadRepository(): ConsignorCommissionFeeRangeReadRepository
    {
        return $this->consignorCommissionFeeRangeReadRepository ?: ConsignorCommissionFeeRangeReadRepository::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeReadRepository $consignorCommissionFeeRangeReadRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeReadRepository(ConsignorCommissionFeeRangeReadRepository $consignorCommissionFeeRangeReadRepository): static
    {
        $this->consignorCommissionFeeRangeReadRepository = $consignorCommissionFeeRangeReadRepository;
        return $this;
    }
}
