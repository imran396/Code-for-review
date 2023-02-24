<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserBilling;

trait UserBillingDeleteRepositoryCreateTrait
{
    protected ?UserBillingDeleteRepository $userBillingDeleteRepository = null;

    protected function createUserBillingDeleteRepository(): UserBillingDeleteRepository
    {
        return $this->userBillingDeleteRepository ?: UserBillingDeleteRepository::new();
    }

    /**
     * @param UserBillingDeleteRepository $userBillingDeleteRepository
     * @return static
     * @internal
     */
    public function setUserBillingDeleteRepository(UserBillingDeleteRepository $userBillingDeleteRepository): static
    {
        $this->userBillingDeleteRepository = $userBillingDeleteRepository;
        return $this;
    }
}
