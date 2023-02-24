<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserCredit;

trait UserCreditDeleteRepositoryCreateTrait
{
    protected ?UserCreditDeleteRepository $userCreditDeleteRepository = null;

    protected function createUserCreditDeleteRepository(): UserCreditDeleteRepository
    {
        return $this->userCreditDeleteRepository ?: UserCreditDeleteRepository::new();
    }

    /**
     * @param UserCreditDeleteRepository $userCreditDeleteRepository
     * @return static
     * @internal
     */
    public function setUserCreditDeleteRepository(UserCreditDeleteRepository $userCreditDeleteRepository): static
    {
        $this->userCreditDeleteRepository = $userCreditDeleteRepository;
        return $this;
    }
}
