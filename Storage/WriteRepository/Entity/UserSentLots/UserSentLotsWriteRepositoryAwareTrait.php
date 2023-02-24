<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserSentLots;

trait UserSentLotsWriteRepositoryAwareTrait
{
    protected ?UserSentLotsWriteRepository $userSentLotsWriteRepository = null;

    protected function getUserSentLotsWriteRepository(): UserSentLotsWriteRepository
    {
        if ($this->userSentLotsWriteRepository === null) {
            $this->userSentLotsWriteRepository = UserSentLotsWriteRepository::new();
        }
        return $this->userSentLotsWriteRepository;
    }

    /**
     * @param UserSentLotsWriteRepository $userSentLotsWriteRepository
     * @return static
     * @internal
     */
    public function setUserSentLotsWriteRepository(UserSentLotsWriteRepository $userSentLotsWriteRepository): static
    {
        $this->userSentLotsWriteRepository = $userSentLotsWriteRepository;
        return $this;
    }
}
