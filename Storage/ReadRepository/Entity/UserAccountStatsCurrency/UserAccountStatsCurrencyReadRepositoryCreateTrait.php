<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency;

trait UserAccountStatsCurrencyReadRepositoryCreateTrait
{
    protected ?UserAccountStatsCurrencyReadRepository $userAccountStatsCurrencyReadRepository = null;

    protected function createUserAccountStatsCurrencyReadRepository(): UserAccountStatsCurrencyReadRepository
    {
        return $this->userAccountStatsCurrencyReadRepository ?: UserAccountStatsCurrencyReadRepository::new();
    }

    /**
     * @param UserAccountStatsCurrencyReadRepository $userAccountStatsCurrencyReadRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsCurrencyReadRepository(UserAccountStatsCurrencyReadRepository $userAccountStatsCurrencyReadRepository): static
    {
        $this->userAccountStatsCurrencyReadRepository = $userAccountStatsCurrencyReadRepository;
        return $this;
    }
}
