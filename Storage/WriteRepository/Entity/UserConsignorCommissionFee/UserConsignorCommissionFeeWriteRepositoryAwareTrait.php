<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserConsignorCommissionFee;

trait UserConsignorCommissionFeeWriteRepositoryAwareTrait
{
    protected ?UserConsignorCommissionFeeWriteRepository $userConsignorCommissionFeeWriteRepository = null;

    protected function getUserConsignorCommissionFeeWriteRepository(): UserConsignorCommissionFeeWriteRepository
    {
        if ($this->userConsignorCommissionFeeWriteRepository === null) {
            $this->userConsignorCommissionFeeWriteRepository = UserConsignorCommissionFeeWriteRepository::new();
        }
        return $this->userConsignorCommissionFeeWriteRepository;
    }

    /**
     * @param UserConsignorCommissionFeeWriteRepository $userConsignorCommissionFeeWriteRepository
     * @return static
     * @internal
     */
    public function setUserConsignorCommissionFeeWriteRepository(UserConsignorCommissionFeeWriteRepository $userConsignorCommissionFeeWriteRepository): static
    {
        $this->userConsignorCommissionFeeWriteRepository = $userConsignorCommissionFeeWriteRepository;
        return $this;
    }
}
