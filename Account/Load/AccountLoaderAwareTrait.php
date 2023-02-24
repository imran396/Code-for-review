<?php

namespace Sam\Account\Load;

/**
 * Trait AccountLoaderAwareTrait
 * @package Sam\Account\Load
 */
trait AccountLoaderAwareTrait
{
    protected ?AccountLoader $accountLoader = null;

    /**
     * @return AccountLoader
     */
    protected function getAccountLoader(): AccountLoader
    {
        if ($this->accountLoader === null) {
            $this->accountLoader = AccountLoader::new();
        }
        return $this->accountLoader;
    }

    /**
     * @param AccountLoader $accountLoader
     * @return static
     * @internal
     */
    public function setAccountLoader(AccountLoader $accountLoader): static
    {
        $this->accountLoader = $accountLoader;
        return $this;
    }
}
