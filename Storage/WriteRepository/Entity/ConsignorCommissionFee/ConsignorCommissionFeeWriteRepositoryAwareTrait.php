<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ConsignorCommissionFee;

trait ConsignorCommissionFeeWriteRepositoryAwareTrait
{
    protected ?ConsignorCommissionFeeWriteRepository $consignorCommissionFeeWriteRepository = null;

    protected function getConsignorCommissionFeeWriteRepository(): ConsignorCommissionFeeWriteRepository
    {
        if ($this->consignorCommissionFeeWriteRepository === null) {
            $this->consignorCommissionFeeWriteRepository = ConsignorCommissionFeeWriteRepository::new();
        }
        return $this->consignorCommissionFeeWriteRepository;
    }

    /**
     * @param ConsignorCommissionFeeWriteRepository $consignorCommissionFeeWriteRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeWriteRepository(ConsignorCommissionFeeWriteRepository $consignorCommissionFeeWriteRepository): static
    {
        $this->consignorCommissionFeeWriteRepository = $consignorCommissionFeeWriteRepository;
        return $this;
    }
}
