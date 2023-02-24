<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAuthentication;

trait UserAuthenticationDeleteRepositoryCreateTrait
{
    protected ?UserAuthenticationDeleteRepository $userAuthenticationDeleteRepository = null;

    protected function createUserAuthenticationDeleteRepository(): UserAuthenticationDeleteRepository
    {
        return $this->userAuthenticationDeleteRepository ?: UserAuthenticationDeleteRepository::new();
    }

    /**
     * @param UserAuthenticationDeleteRepository $userAuthenticationDeleteRepository
     * @return static
     * @internal
     */
    public function setUserAuthenticationDeleteRepository(UserAuthenticationDeleteRepository $userAuthenticationDeleteRepository): static
    {
        $this->userAuthenticationDeleteRepository = $userAuthenticationDeleteRepository;
        return $this;
    }
}
