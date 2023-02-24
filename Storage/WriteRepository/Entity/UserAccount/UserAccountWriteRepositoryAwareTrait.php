<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccount;

trait UserAccountWriteRepositoryAwareTrait
{
    protected ?UserAccountWriteRepository $userAccountWriteRepository = null;

    protected function getUserAccountWriteRepository(): UserAccountWriteRepository
    {
        if ($this->userAccountWriteRepository === null) {
            $this->userAccountWriteRepository = UserAccountWriteRepository::new();
        }
        return $this->userAccountWriteRepository;
    }

    /**
     * @param UserAccountWriteRepository $userAccountWriteRepository
     * @return static
     * @internal
     */
    public function setUserAccountWriteRepository(UserAccountWriteRepository $userAccountWriteRepository): static
    {
        $this->userAccountWriteRepository = $userAccountWriteRepository;
        return $this;
    }
}
