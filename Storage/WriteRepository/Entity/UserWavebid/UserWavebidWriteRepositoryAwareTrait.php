<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserWavebid;

trait UserWavebidWriteRepositoryAwareTrait
{
    protected ?UserWavebidWriteRepository $userWavebidWriteRepository = null;

    protected function getUserWavebidWriteRepository(): UserWavebidWriteRepository
    {
        if ($this->userWavebidWriteRepository === null) {
            $this->userWavebidWriteRepository = UserWavebidWriteRepository::new();
        }
        return $this->userWavebidWriteRepository;
    }

    /**
     * @param UserWavebidWriteRepository $userWavebidWriteRepository
     * @return static
     * @internal
     */
    public function setUserWavebidWriteRepository(UserWavebidWriteRepository $userWavebidWriteRepository): static
    {
        $this->userWavebidWriteRepository = $userWavebidWriteRepository;
        return $this;
    }
}
