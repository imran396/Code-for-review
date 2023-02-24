<?php

namespace Sam\Account\Validate;

/**
 * Trait AccountExistenceCheckerAwareTrait
 * @package Sam\Account\Validate
 */
trait AccountExistenceCheckerAwareTrait
{
    protected ?AccountExistenceChecker $accountExistenceChecker = null;

    /**
     * @return AccountExistenceChecker
     */
    protected function getAccountExistenceChecker(): AccountExistenceChecker
    {
        if ($this->accountExistenceChecker === null) {
            $this->accountExistenceChecker = AccountExistenceChecker::new();
        }
        return $this->accountExistenceChecker;
    }

    /**
     * @param AccountExistenceChecker $accountExistenceChecker
     * @return static
     * @internal
     */
    public function setAccountExistenceChecker(AccountExistenceChecker $accountExistenceChecker): static
    {
        $this->accountExistenceChecker = $accountExistenceChecker;
        return $this;
    }
}
