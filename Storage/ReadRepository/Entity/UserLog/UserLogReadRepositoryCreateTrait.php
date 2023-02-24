<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserLog;

trait UserLogReadRepositoryCreateTrait
{
    protected ?UserLogReadRepository $userLogReadRepository = null;

    protected function createUserLogReadRepository(): UserLogReadRepository
    {
        return $this->userLogReadRepository ?: UserLogReadRepository::new();
    }

    /**
     * @param UserLogReadRepository $userLogReadRepository
     * @return static
     * @internal
     */
    public function setUserLogReadRepository(UserLogReadRepository $userLogReadRepository): static
    {
        $this->userLogReadRepository = $userLogReadRepository;
        return $this;
    }
}
