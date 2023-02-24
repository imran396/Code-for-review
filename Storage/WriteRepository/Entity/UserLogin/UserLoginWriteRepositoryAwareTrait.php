<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserLogin;

trait UserLoginWriteRepositoryAwareTrait
{
    protected ?UserLoginWriteRepository $userLoginWriteRepository = null;

    protected function getUserLoginWriteRepository(): UserLoginWriteRepository
    {
        if ($this->userLoginWriteRepository === null) {
            $this->userLoginWriteRepository = UserLoginWriteRepository::new();
        }
        return $this->userLoginWriteRepository;
    }

    /**
     * @param UserLoginWriteRepository $userLoginWriteRepository
     * @return static
     * @internal
     */
    public function setUserLoginWriteRepository(UserLoginWriteRepository $userLoginWriteRepository): static
    {
        $this->userLoginWriteRepository = $userLoginWriteRepository;
        return $this;
    }
}
