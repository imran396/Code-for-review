<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserBilling;

trait UserBillingReadRepositoryCreateTrait
{
    protected ?UserBillingReadRepository $userBillingReadRepository = null;

    protected function createUserBillingReadRepository(): UserBillingReadRepository
    {
        return $this->userBillingReadRepository ?: UserBillingReadRepository::new();
    }

    /**
     * @param UserBillingReadRepository $userBillingReadRepository
     * @return static
     * @internal
     */
    public function setUserBillingReadRepository(UserBillingReadRepository $userBillingReadRepository): static
    {
        $this->userBillingReadRepository = $userBillingReadRepository;
        return $this;
    }
}
