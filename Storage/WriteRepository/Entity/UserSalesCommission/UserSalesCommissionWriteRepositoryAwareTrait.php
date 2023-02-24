<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserSalesCommission;

trait UserSalesCommissionWriteRepositoryAwareTrait
{
    protected ?UserSalesCommissionWriteRepository $userSalesCommissionWriteRepository = null;

    protected function getUserSalesCommissionWriteRepository(): UserSalesCommissionWriteRepository
    {
        if ($this->userSalesCommissionWriteRepository === null) {
            $this->userSalesCommissionWriteRepository = UserSalesCommissionWriteRepository::new();
        }
        return $this->userSalesCommissionWriteRepository;
    }

    /**
     * @param UserSalesCommissionWriteRepository $userSalesCommissionWriteRepository
     * @return static
     * @internal
     */
    public function setUserSalesCommissionWriteRepository(UserSalesCommissionWriteRepository $userSalesCommissionWriteRepository): static
    {
        $this->userSalesCommissionWriteRepository = $userSalesCommissionWriteRepository;
        return $this;
    }
}
