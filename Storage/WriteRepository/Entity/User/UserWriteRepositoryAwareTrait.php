<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\User;

trait UserWriteRepositoryAwareTrait
{
    protected ?UserWriteRepository $userWriteRepository = null;

    protected function getUserWriteRepository(): UserWriteRepository
    {
        if ($this->userWriteRepository === null) {
            $this->userWriteRepository = UserWriteRepository::new();
        }
        return $this->userWriteRepository;
    }

    /**
     * @param UserWriteRepository $userWriteRepository
     * @return static
     * @internal
     */
    public function setUserWriteRepository(UserWriteRepository $userWriteRepository): static
    {
        $this->userWriteRepository = $userWriteRepository;
        return $this;
    }
}
