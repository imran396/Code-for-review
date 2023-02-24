<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserSalesCommission;

trait UserSalesCommissionDeleteRepositoryCreateTrait
{
    protected ?UserSalesCommissionDeleteRepository $userSalesCommissionDeleteRepository = null;

    protected function createUserSalesCommissionDeleteRepository(): UserSalesCommissionDeleteRepository
    {
        return $this->userSalesCommissionDeleteRepository ?: UserSalesCommissionDeleteRepository::new();
    }

    /**
     * @param UserSalesCommissionDeleteRepository $userSalesCommissionDeleteRepository
     * @return static
     * @internal
     */
    public function setUserSalesCommissionDeleteRepository(UserSalesCommissionDeleteRepository $userSalesCommissionDeleteRepository): static
    {
        $this->userSalesCommissionDeleteRepository = $userSalesCommissionDeleteRepository;
        return $this;
    }
}
