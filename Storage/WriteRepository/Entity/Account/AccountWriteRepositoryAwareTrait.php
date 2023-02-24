<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Account;

trait AccountWriteRepositoryAwareTrait
{
    protected ?AccountWriteRepository $accountWriteRepository = null;

    protected function getAccountWriteRepository(): AccountWriteRepository
    {
        if ($this->accountWriteRepository === null) {
            $this->accountWriteRepository = AccountWriteRepository::new();
        }
        return $this->accountWriteRepository;
    }

    /**
     * @param AccountWriteRepository $accountWriteRepository
     * @return static
     * @internal
     */
    public function setAccountWriteRepository(AccountWriteRepository $accountWriteRepository): static
    {
        $this->accountWriteRepository = $accountWriteRepository;
        return $this;
    }
}
