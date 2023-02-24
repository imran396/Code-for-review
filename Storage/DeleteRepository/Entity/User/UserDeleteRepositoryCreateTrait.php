<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\User;

trait UserDeleteRepositoryCreateTrait
{
    protected ?UserDeleteRepository $userDeleteRepository = null;

    protected function createUserDeleteRepository(): UserDeleteRepository
    {
        return $this->userDeleteRepository ?: UserDeleteRepository::new();
    }

    /**
     * @param UserDeleteRepository $userDeleteRepository
     * @return static
     * @internal
     */
    public function setUserDeleteRepository(UserDeleteRepository $userDeleteRepository): static
    {
        $this->userDeleteRepository = $userDeleteRepository;
        return $this;
    }
}
