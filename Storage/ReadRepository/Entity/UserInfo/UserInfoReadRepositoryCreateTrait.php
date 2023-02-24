<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserInfo;

trait UserInfoReadRepositoryCreateTrait
{
    protected ?UserInfoReadRepository $userInfoReadRepository = null;

    protected function createUserInfoReadRepository(): UserInfoReadRepository
    {
        return $this->userInfoReadRepository ?: UserInfoReadRepository::new();
    }

    /**
     * @param UserInfoReadRepository $userInfoReadRepository
     * @return static
     * @internal
     */
    public function setUserInfoReadRepository(UserInfoReadRepository $userInfoReadRepository): static
    {
        $this->userInfoReadRepository = $userInfoReadRepository;
        return $this;
    }
}
