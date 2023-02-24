<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserCustData;

trait UserCustDataDeleteRepositoryCreateTrait
{
    protected ?UserCustDataDeleteRepository $userCustDataDeleteRepository = null;

    protected function createUserCustDataDeleteRepository(): UserCustDataDeleteRepository
    {
        return $this->userCustDataDeleteRepository ?: UserCustDataDeleteRepository::new();
    }

    /**
     * @param UserCustDataDeleteRepository $userCustDataDeleteRepository
     * @return static
     * @internal
     */
    public function setUserCustDataDeleteRepository(UserCustDataDeleteRepository $userCustDataDeleteRepository): static
    {
        $this->userCustDataDeleteRepository = $userCustDataDeleteRepository;
        return $this;
    }
}
