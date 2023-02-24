<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserSentLots;

trait UserSentLotsDeleteRepositoryCreateTrait
{
    protected ?UserSentLotsDeleteRepository $userSentLotsDeleteRepository = null;

    protected function createUserSentLotsDeleteRepository(): UserSentLotsDeleteRepository
    {
        return $this->userSentLotsDeleteRepository ?: UserSentLotsDeleteRepository::new();
    }

    /**
     * @param UserSentLotsDeleteRepository $userSentLotsDeleteRepository
     * @return static
     * @internal
     */
    public function setUserSentLotsDeleteRepository(UserSentLotsDeleteRepository $userSentLotsDeleteRepository): static
    {
        $this->userSentLotsDeleteRepository = $userSentLotsDeleteRepository;
        return $this;
    }
}
