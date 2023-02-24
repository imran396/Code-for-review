<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserWavebid;

trait UserWavebidDeleteRepositoryCreateTrait
{
    protected ?UserWavebidDeleteRepository $userWavebidDeleteRepository = null;

    protected function createUserWavebidDeleteRepository(): UserWavebidDeleteRepository
    {
        return $this->userWavebidDeleteRepository ?: UserWavebidDeleteRepository::new();
    }

    /**
     * @param UserWavebidDeleteRepository $userWavebidDeleteRepository
     * @return static
     * @internal
     */
    public function setUserWavebidDeleteRepository(UserWavebidDeleteRepository $userWavebidDeleteRepository): static
    {
        $this->userWavebidDeleteRepository = $userWavebidDeleteRepository;
        return $this;
    }
}
