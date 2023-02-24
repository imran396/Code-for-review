<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAuthentication;

trait UserAuthenticationReadRepositoryCreateTrait
{
    protected ?UserAuthenticationReadRepository $userAuthenticationReadRepository = null;

    protected function createUserAuthenticationReadRepository(): UserAuthenticationReadRepository
    {
        return $this->userAuthenticationReadRepository ?: UserAuthenticationReadRepository::new();
    }

    /**
     * @param UserAuthenticationReadRepository $userAuthenticationReadRepository
     * @return static
     * @internal
     */
    public function setUserAuthenticationReadRepository(UserAuthenticationReadRepository $userAuthenticationReadRepository): static
    {
        $this->userAuthenticationReadRepository = $userAuthenticationReadRepository;
        return $this;
    }
}
