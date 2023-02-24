<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserCredit;

trait UserCreditReadRepositoryCreateTrait
{
    protected ?UserCreditReadRepository $userCreditReadRepository = null;

    protected function createUserCreditReadRepository(): UserCreditReadRepository
    {
        return $this->userCreditReadRepository ?: UserCreditReadRepository::new();
    }

    /**
     * @param UserCreditReadRepository $userCreditReadRepository
     * @return static
     * @internal
     */
    public function setUserCreditReadRepository(UserCreditReadRepository $userCreditReadRepository): static
    {
        $this->userCreditReadRepository = $userCreditReadRepository;
        return $this;
    }
}
