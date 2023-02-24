<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserLog;

trait UserLogDeleteRepositoryCreateTrait
{
    protected ?UserLogDeleteRepository $userLogDeleteRepository = null;

    protected function createUserLogDeleteRepository(): UserLogDeleteRepository
    {
        return $this->userLogDeleteRepository ?: UserLogDeleteRepository::new();
    }

    /**
     * @param UserLogDeleteRepository $userLogDeleteRepository
     * @return static
     * @internal
     */
    public function setUserLogDeleteRepository(UserLogDeleteRepository $userLogDeleteRepository): static
    {
        $this->userLogDeleteRepository = $userLogDeleteRepository;
        return $this;
    }
}
