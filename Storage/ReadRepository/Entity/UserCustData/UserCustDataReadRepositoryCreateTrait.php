<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustData;

trait UserCustDataReadRepositoryCreateTrait
{
    protected ?UserCustDataReadRepository $userCustDataReadRepository = null;

    protected function createUserCustDataReadRepository(): UserCustDataReadRepository
    {
        return $this->userCustDataReadRepository ?: UserCustDataReadRepository::new();
    }

    /**
     * @param UserCustDataReadRepository $userCustDataReadRepository
     * @return static
     * @internal
     */
    public function setUserCustDataReadRepository(UserCustDataReadRepository $userCustDataReadRepository): static
    {
        $this->userCustDataReadRepository = $userCustDataReadRepository;
        return $this;
    }
}
