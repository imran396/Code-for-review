<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ConsignorCommissionFeeRange;

trait ConsignorCommissionFeeRangeWriteRepositoryAwareTrait
{
    protected ?ConsignorCommissionFeeRangeWriteRepository $consignorCommissionFeeRangeWriteRepository = null;

    protected function getConsignorCommissionFeeRangeWriteRepository(): ConsignorCommissionFeeRangeWriteRepository
    {
        if ($this->consignorCommissionFeeRangeWriteRepository === null) {
            $this->consignorCommissionFeeRangeWriteRepository = ConsignorCommissionFeeRangeWriteRepository::new();
        }
        return $this->consignorCommissionFeeRangeWriteRepository;
    }

    /**
     * @param ConsignorCommissionFeeRangeWriteRepository $consignorCommissionFeeRangeWriteRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeWriteRepository(ConsignorCommissionFeeRangeWriteRepository $consignorCommissionFeeRangeWriteRepository): static
    {
        $this->consignorCommissionFeeRangeWriteRepository = $consignorCommissionFeeRangeWriteRepository;
        return $this;
    }
}
