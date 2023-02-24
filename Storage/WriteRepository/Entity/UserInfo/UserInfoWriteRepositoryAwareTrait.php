<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserInfo;

trait UserInfoWriteRepositoryAwareTrait
{
    protected ?UserInfoWriteRepository $userInfoWriteRepository = null;

    protected function getUserInfoWriteRepository(): UserInfoWriteRepository
    {
        if ($this->userInfoWriteRepository === null) {
            $this->userInfoWriteRepository = UserInfoWriteRepository::new();
        }
        return $this->userInfoWriteRepository;
    }

    /**
     * @param UserInfoWriteRepository $userInfoWriteRepository
     * @return static
     * @internal
     */
    public function setUserInfoWriteRepository(UserInfoWriteRepository $userInfoWriteRepository): static
    {
        $this->userInfoWriteRepository = $userInfoWriteRepository;
        return $this;
    }
}
