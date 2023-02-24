<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCredit;

trait UserCreditWriteRepositoryAwareTrait
{
    protected ?UserCreditWriteRepository $userCreditWriteRepository = null;

    protected function getUserCreditWriteRepository(): UserCreditWriteRepository
    {
        if ($this->userCreditWriteRepository === null) {
            $this->userCreditWriteRepository = UserCreditWriteRepository::new();
        }
        return $this->userCreditWriteRepository;
    }

    /**
     * @param UserCreditWriteRepository $userCreditWriteRepository
     * @return static
     * @internal
     */
    public function setUserCreditWriteRepository(UserCreditWriteRepository $userCreditWriteRepository): static
    {
        $this->userCreditWriteRepository = $userCreditWriteRepository;
        return $this;
    }
}
