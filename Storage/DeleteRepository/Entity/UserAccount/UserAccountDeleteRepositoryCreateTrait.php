<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAccount;

trait UserAccountDeleteRepositoryCreateTrait
{
    protected ?UserAccountDeleteRepository $userAccountDeleteRepository = null;

    protected function createUserAccountDeleteRepository(): UserAccountDeleteRepository
    {
        return $this->userAccountDeleteRepository ?: UserAccountDeleteRepository::new();
    }

    /**
     * @param UserAccountDeleteRepository $userAccountDeleteRepository
     * @return static
     * @internal
     */
    public function setUserAccountDeleteRepository(UserAccountDeleteRepository $userAccountDeleteRepository): static
    {
        $this->userAccountDeleteRepository = $userAccountDeleteRepository;
        return $this;
    }
}
