<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\User;

trait UserReadRepositoryCreateTrait
{
    protected ?UserReadRepository $userReadRepository = null;

    protected function createUserReadRepository(): UserReadRepository
    {
        return $this->userReadRepository ?: UserReadRepository::new();
    }

    /**
     * @param UserReadRepository $userReadRepository
     * @return static
     * @internal
     */
    public function setUserReadRepository(UserReadRepository $userReadRepository): static
    {
        $this->userReadRepository = $userReadRepository;
        return $this;
    }
}
