<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserInfo;

trait UserInfoDeleteRepositoryCreateTrait
{
    protected ?UserInfoDeleteRepository $userInfoDeleteRepository = null;

    protected function createUserInfoDeleteRepository(): UserInfoDeleteRepository
    {
        return $this->userInfoDeleteRepository ?: UserInfoDeleteRepository::new();
    }

    /**
     * @param UserInfoDeleteRepository $userInfoDeleteRepository
     * @return static
     * @internal
     */
    public function setUserInfoDeleteRepository(UserInfoDeleteRepository $userInfoDeleteRepository): static
    {
        $this->userInfoDeleteRepository = $userInfoDeleteRepository;
        return $this;
    }
}
