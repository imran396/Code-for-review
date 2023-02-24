<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCustField;

trait UserCustFieldWriteRepositoryAwareTrait
{
    protected ?UserCustFieldWriteRepository $userCustFieldWriteRepository = null;

    protected function getUserCustFieldWriteRepository(): UserCustFieldWriteRepository
    {
        if ($this->userCustFieldWriteRepository === null) {
            $this->userCustFieldWriteRepository = UserCustFieldWriteRepository::new();
        }
        return $this->userCustFieldWriteRepository;
    }

    /**
     * @param UserCustFieldWriteRepository $userCustFieldWriteRepository
     * @return static
     * @internal
     */
    public function setUserCustFieldWriteRepository(UserCustFieldWriteRepository $userCustFieldWriteRepository): static
    {
        $this->userCustFieldWriteRepository = $userCustFieldWriteRepository;
        return $this;
    }
}
