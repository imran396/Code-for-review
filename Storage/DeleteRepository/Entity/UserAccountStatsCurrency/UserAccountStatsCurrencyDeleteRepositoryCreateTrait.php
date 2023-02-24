<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAccountStatsCurrency;

trait UserAccountStatsCurrencyDeleteRepositoryCreateTrait
{
    protected ?UserAccountStatsCurrencyDeleteRepository $userAccountStatsCurrencyDeleteRepository = null;

    protected function createUserAccountStatsCurrencyDeleteRepository(): UserAccountStatsCurrencyDeleteRepository
    {
        return $this->userAccountStatsCurrencyDeleteRepository ?: UserAccountStatsCurrencyDeleteRepository::new();
    }

    /**
     * @param UserAccountStatsCurrencyDeleteRepository $userAccountStatsCurrencyDeleteRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsCurrencyDeleteRepository(UserAccountStatsCurrencyDeleteRepository $userAccountStatsCurrencyDeleteRepository): static
    {
        $this->userAccountStatsCurrencyDeleteRepository = $userAccountStatsCurrencyDeleteRepository;
        return $this;
    }
}
