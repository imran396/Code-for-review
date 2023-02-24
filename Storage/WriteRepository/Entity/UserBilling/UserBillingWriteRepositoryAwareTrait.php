<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserBilling;

trait UserBillingWriteRepositoryAwareTrait
{
    protected ?UserBillingWriteRepository $userBillingWriteRepository = null;

    protected function getUserBillingWriteRepository(): UserBillingWriteRepository
    {
        if ($this->userBillingWriteRepository === null) {
            $this->userBillingWriteRepository = UserBillingWriteRepository::new();
        }
        return $this->userBillingWriteRepository;
    }

    /**
     * @param UserBillingWriteRepository $userBillingWriteRepository
     * @return static
     * @internal
     */
    public function setUserBillingWriteRepository(UserBillingWriteRepository $userBillingWriteRepository): static
    {
        $this->userBillingWriteRepository = $userBillingWriteRepository;
        return $this;
    }
}
