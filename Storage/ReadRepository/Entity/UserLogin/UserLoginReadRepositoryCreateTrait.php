<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserLogin;

trait UserLoginReadRepositoryCreateTrait
{
    protected ?UserLoginReadRepository $userLoginReadRepository = null;

    protected function createUserLoginReadRepository(): UserLoginReadRepository
    {
        return $this->userLoginReadRepository ?: UserLoginReadRepository::new();
    }

    /**
     * @param UserLoginReadRepository $userLoginReadRepository
     * @return static
     * @internal
     */
    public function setUserLoginReadRepository(UserLoginReadRepository $userLoginReadRepository): static
    {
        $this->userLoginReadRepository = $userLoginReadRepository;
        return $this;
    }
}
