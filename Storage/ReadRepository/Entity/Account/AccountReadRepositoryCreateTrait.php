<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Account;

trait AccountReadRepositoryCreateTrait
{
    protected ?AccountReadRepository $accountReadRepository = null;

    protected function createAccountReadRepository(): AccountReadRepository
    {
        return $this->accountReadRepository ?: AccountReadRepository::new();
    }

    /**
     * @param AccountReadRepository $accountReadRepository
     * @return static
     * @internal
     */
    public function setAccountReadRepository(AccountReadRepository $accountReadRepository): static
    {
        $this->accountReadRepository = $accountReadRepository;
        return $this;
    }
}
