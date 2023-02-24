<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccount;

trait UserAccountReadRepositoryCreateTrait
{
    protected ?UserAccountReadRepository $userAccountReadRepository = null;

    protected function createUserAccountReadRepository(): UserAccountReadRepository
    {
        return $this->userAccountReadRepository ?: UserAccountReadRepository::new();
    }

    /**
     * @param UserAccountReadRepository $userAccountReadRepository
     * @return static
     * @internal
     */
    public function setUserAccountReadRepository(UserAccountReadRepository $userAccountReadRepository): static
    {
        $this->userAccountReadRepository = $userAccountReadRepository;
        return $this;
    }
}
