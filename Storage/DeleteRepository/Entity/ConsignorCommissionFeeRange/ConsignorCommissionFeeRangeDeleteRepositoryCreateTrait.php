<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ConsignorCommissionFeeRange;

trait ConsignorCommissionFeeRangeDeleteRepositoryCreateTrait
{
    protected ?ConsignorCommissionFeeRangeDeleteRepository $consignorCommissionFeeRangeDeleteRepository = null;

    protected function createConsignorCommissionFeeRangeDeleteRepository(): ConsignorCommissionFeeRangeDeleteRepository
    {
        return $this->consignorCommissionFeeRangeDeleteRepository ?: ConsignorCommissionFeeRangeDeleteRepository::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeDeleteRepository $consignorCommissionFeeRangeDeleteRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeDeleteRepository(ConsignorCommissionFeeRangeDeleteRepository $consignorCommissionFeeRangeDeleteRepository): static
    {
        $this->consignorCommissionFeeRangeDeleteRepository = $consignorCommissionFeeRangeDeleteRepository;
        return $this;
    }
}
