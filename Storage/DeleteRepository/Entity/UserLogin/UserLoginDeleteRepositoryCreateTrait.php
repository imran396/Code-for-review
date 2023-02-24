<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserLogin;

trait UserLoginDeleteRepositoryCreateTrait
{
    protected ?UserLoginDeleteRepository $userLoginDeleteRepository = null;

    protected function createUserLoginDeleteRepository(): UserLoginDeleteRepository
    {
        return $this->userLoginDeleteRepository ?: UserLoginDeleteRepository::new();
    }

    /**
     * @param UserLoginDeleteRepository $userLoginDeleteRepository
     * @return static
     * @internal
     */
    public function setUserLoginDeleteRepository(UserLoginDeleteRepository $userLoginDeleteRepository): static
    {
        $this->userLoginDeleteRepository = $userLoginDeleteRepository;
        return $this;
    }
}
