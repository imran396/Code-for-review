<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserSalesCommission;

trait UserSalesCommissionReadRepositoryCreateTrait
{
    protected ?UserSalesCommissionReadRepository $userSalesCommissionReadRepository = null;

    protected function createUserSalesCommissionReadRepository(): UserSalesCommissionReadRepository
    {
        return $this->userSalesCommissionReadRepository ?: UserSalesCommissionReadRepository::new();
    }

    /**
     * @param UserSalesCommissionReadRepository $userSalesCommissionReadRepository
     * @return static
     * @internal
     */
    public function setUserSalesCommissionReadRepository(UserSalesCommissionReadRepository $userSalesCommissionReadRepository): static
    {
        $this->userSalesCommissionReadRepository = $userSalesCommissionReadRepository;
        return $this;
    }
}
