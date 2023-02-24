<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ConsignorCommissionFee;

trait ConsignorCommissionFeeDeleteRepositoryCreateTrait
{
    protected ?ConsignorCommissionFeeDeleteRepository $consignorCommissionFeeDeleteRepository = null;

    protected function createConsignorCommissionFeeDeleteRepository(): ConsignorCommissionFeeDeleteRepository
    {
        return $this->consignorCommissionFeeDeleteRepository ?: ConsignorCommissionFeeDeleteRepository::new();
    }

    /**
     * @param ConsignorCommissionFeeDeleteRepository $consignorCommissionFeeDeleteRepository
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeDeleteRepository(ConsignorCommissionFeeDeleteRepository $consignorCommissionFeeDeleteRepository): static
    {
        $this->consignorCommissionFeeDeleteRepository = $consignorCommissionFeeDeleteRepository;
        return $this;
    }
}
