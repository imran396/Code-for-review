<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserWavebid;

trait UserWavebidReadRepositoryCreateTrait
{
    protected ?UserWavebidReadRepository $userWavebidReadRepository = null;

    protected function createUserWavebidReadRepository(): UserWavebidReadRepository
    {
        return $this->userWavebidReadRepository ?: UserWavebidReadRepository::new();
    }

    /**
     * @param UserWavebidReadRepository $userWavebidReadRepository
     * @return static
     * @internal
     */
    public function setUserWavebidReadRepository(UserWavebidReadRepository $userWavebidReadRepository): static
    {
        $this->userWavebidReadRepository = $userWavebidReadRepository;
        return $this;
    }
}
