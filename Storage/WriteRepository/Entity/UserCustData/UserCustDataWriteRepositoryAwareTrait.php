<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCustData;

trait UserCustDataWriteRepositoryAwareTrait
{
    protected ?UserCustDataWriteRepository $userCustDataWriteRepository = null;

    protected function getUserCustDataWriteRepository(): UserCustDataWriteRepository
    {
        if ($this->userCustDataWriteRepository === null) {
            $this->userCustDataWriteRepository = UserCustDataWriteRepository::new();
        }
        return $this->userCustDataWriteRepository;
    }

    /**
     * @param UserCustDataWriteRepository $userCustDataWriteRepository
     * @return static
     * @internal
     */
    public function setUserCustDataWriteRepository(UserCustDataWriteRepository $userCustDataWriteRepository): static
    {
        $this->userCustDataWriteRepository = $userCustDataWriteRepository;
        return $this;
    }
}
