<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserConsignorCommissionFee;

trait UserConsignorCommissionFeeReadRepositoryCreateTrait
{
    protected ?UserConsignorCommissionFeeReadRepository $userConsignorCommissionFeeReadRepository = null;

    protected function createUserConsignorCommissionFeeReadRepository(): UserConsignorCommissionFeeReadRepository
    {
        return $this->userConsignorCommissionFeeReadRepository ?: UserConsignorCommissionFeeReadRepository::new();
    }

    /**
     * @param UserConsignorCommissionFeeReadRepository $userConsignorCommissionFeeReadRepository
     * @return static
     * @internal
     */
    public function setUserConsignorCommissionFeeReadRepository(UserConsignorCommissionFeeReadRepository $userConsignorCommissionFeeReadRepository): static
    {
        $this->userConsignorCommissionFeeReadRepository = $userConsignorCommissionFeeReadRepository;
        return $this;
    }
}
