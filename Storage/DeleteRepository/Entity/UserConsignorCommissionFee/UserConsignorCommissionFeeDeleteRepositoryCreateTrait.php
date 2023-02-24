<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserConsignorCommissionFee;

trait UserConsignorCommissionFeeDeleteRepositoryCreateTrait
{
    protected ?UserConsignorCommissionFeeDeleteRepository $userConsignorCommissionFeeDeleteRepository = null;

    protected function createUserConsignorCommissionFeeDeleteRepository(): UserConsignorCommissionFeeDeleteRepository
    {
        return $this->userConsignorCommissionFeeDeleteRepository ?: UserConsignorCommissionFeeDeleteRepository::new();
    }

    /**
     * @param UserConsignorCommissionFeeDeleteRepository $userConsignorCommissionFeeDeleteRepository
     * @return static
     * @internal
     */
    public function setUserConsignorCommissionFeeDeleteRepository(UserConsignorCommissionFeeDeleteRepository $userConsignorCommissionFeeDeleteRepository): static
    {
        $this->userConsignorCommissionFeeDeleteRepository = $userConsignorCommissionFeeDeleteRepository;
        return $this;
    }
}
