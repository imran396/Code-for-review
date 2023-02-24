<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccountStatsCurrency;

trait UserAccountStatsCurrencyWriteRepositoryAwareTrait
{
    protected ?UserAccountStatsCurrencyWriteRepository $userAccountStatsCurrencyWriteRepository = null;

    protected function getUserAccountStatsCurrencyWriteRepository(): UserAccountStatsCurrencyWriteRepository
    {
        if ($this->userAccountStatsCurrencyWriteRepository === null) {
            $this->userAccountStatsCurrencyWriteRepository = UserAccountStatsCurrencyWriteRepository::new();
        }
        return $this->userAccountStatsCurrencyWriteRepository;
    }

    /**
     * @param UserAccountStatsCurrencyWriteRepository $userAccountStatsCurrencyWriteRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsCurrencyWriteRepository(UserAccountStatsCurrencyWriteRepository $userAccountStatsCurrencyWriteRepository): static
    {
        $this->userAccountStatsCurrencyWriteRepository = $userAccountStatsCurrencyWriteRepository;
        return $this;
    }
}
