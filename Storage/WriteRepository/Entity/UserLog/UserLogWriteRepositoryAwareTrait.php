<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserLog;

trait UserLogWriteRepositoryAwareTrait
{
    protected ?UserLogWriteRepository $userLogWriteRepository = null;

    protected function getUserLogWriteRepository(): UserLogWriteRepository
    {
        if ($this->userLogWriteRepository === null) {
            $this->userLogWriteRepository = UserLogWriteRepository::new();
        }
        return $this->userLogWriteRepository;
    }

    /**
     * @param UserLogWriteRepository $userLogWriteRepository
     * @return static
     * @internal
     */
    public function setUserLogWriteRepository(UserLogWriteRepository $userLogWriteRepository): static
    {
        $this->userLogWriteRepository = $userLogWriteRepository;
        return $this;
    }
}
