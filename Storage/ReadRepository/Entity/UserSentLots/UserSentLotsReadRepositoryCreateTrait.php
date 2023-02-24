<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserSentLots;

trait UserSentLotsReadRepositoryCreateTrait
{
    protected ?UserSentLotsReadRepository $userSentLotsReadRepository = null;

    protected function createUserSentLotsReadRepository(): UserSentLotsReadRepository
    {
        return $this->userSentLotsReadRepository ?: UserSentLotsReadRepository::new();
    }

    /**
     * @param UserSentLotsReadRepository $userSentLotsReadRepository
     * @return static
     * @internal
     */
    public function setUserSentLotsReadRepository(UserSentLotsReadRepository $userSentLotsReadRepository): static
    {
        $this->userSentLotsReadRepository = $userSentLotsReadRepository;
        return $this;
    }
}
