<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustField;

trait UserCustFieldReadRepositoryCreateTrait
{
    protected ?UserCustFieldReadRepository $userCustFieldReadRepository = null;

    protected function createUserCustFieldReadRepository(): UserCustFieldReadRepository
    {
        return $this->userCustFieldReadRepository ?: UserCustFieldReadRepository::new();
    }

    /**
     * @param UserCustFieldReadRepository $userCustFieldReadRepository
     * @return static
     * @internal
     */
    public function setUserCustFieldReadRepository(UserCustFieldReadRepository $userCustFieldReadRepository): static
    {
        $this->userCustFieldReadRepository = $userCustFieldReadRepository;
        return $this;
    }
}
