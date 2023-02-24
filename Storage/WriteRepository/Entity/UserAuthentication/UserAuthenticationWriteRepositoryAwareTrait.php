<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAuthentication;

trait UserAuthenticationWriteRepositoryAwareTrait
{
    protected ?UserAuthenticationWriteRepository $userAuthenticationWriteRepository = null;

    protected function getUserAuthenticationWriteRepository(): UserAuthenticationWriteRepository
    {
        if ($this->userAuthenticationWriteRepository === null) {
            $this->userAuthenticationWriteRepository = UserAuthenticationWriteRepository::new();
        }
        return $this->userAuthenticationWriteRepository;
    }

    /**
     * @param UserAuthenticationWriteRepository $userAuthenticationWriteRepository
     * @return static
     * @internal
     */
    public function setUserAuthenticationWriteRepository(UserAuthenticationWriteRepository $userAuthenticationWriteRepository): static
    {
        $this->userAuthenticationWriteRepository = $userAuthenticationWriteRepository;
        return $this;
    }
}
