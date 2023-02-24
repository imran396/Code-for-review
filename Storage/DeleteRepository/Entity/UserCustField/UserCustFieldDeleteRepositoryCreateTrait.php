<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserCustField;

trait UserCustFieldDeleteRepositoryCreateTrait
{
    protected ?UserCustFieldDeleteRepository $userCustFieldDeleteRepository = null;

    protected function createUserCustFieldDeleteRepository(): UserCustFieldDeleteRepository
    {
        return $this->userCustFieldDeleteRepository ?: UserCustFieldDeleteRepository::new();
    }

    /**
     * @param UserCustFieldDeleteRepository $userCustFieldDeleteRepository
     * @return static
     * @internal
     */
    public function setUserCustFieldDeleteRepository(UserCustFieldDeleteRepository $userCustFieldDeleteRepository): static
    {
        $this->userCustFieldDeleteRepository = $userCustFieldDeleteRepository;
        return $this;
    }
}
