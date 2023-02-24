<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Account;

trait AccountDeleteRepositoryCreateTrait
{
    protected ?AccountDeleteRepository $accountDeleteRepository = null;

    protected function createAccountDeleteRepository(): AccountDeleteRepository
    {
        return $this->accountDeleteRepository ?: AccountDeleteRepository::new();
    }

    /**
     * @param AccountDeleteRepository $accountDeleteRepository
     * @return static
     * @internal
     */
    public function setAccountDeleteRepository(AccountDeleteRepository $accountDeleteRepository): static
    {
        $this->accountDeleteRepository = $accountDeleteRepository;
        return $this;
    }
}
