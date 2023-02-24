<?php
/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Account;
use Sam\Application\Application;
use Sam\Storage\Entity\Aggregate\AccountAggregate;

/**
 * Trait SystemAccountAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait SystemAccountAwareTrait
{
    protected ?AccountAggregate $systemAccountAggregate = null;

    /**
     * @return bool
     */
    public function hasSystemAccountId(): bool
    {
        return $this->getSystemAccountAggregate()->hasAccountId();
    }

    /**
     * @return int
     */
    public function getSystemAccountId(): int
    {
        $this->initSystemAccountFromApplicationContext();
        return $this->getSystemAccountAggregate()->getAccountId();
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setSystemAccountId(?int $accountId): static
    {
        $this->getSystemAccountAggregate()->setAccountId($accountId);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSystemAccount(): bool
    {
        return $this->getSystemAccountAggregate()->hasAccount();
    }

    /**
     * Return Account|null object
     * @param bool $isReadOnlyDb
     * @return Account
     */
    public function getSystemAccount(bool $isReadOnlyDb = false): Account
    {
        $this->initSystemAccountFromApplicationContext();
        return $this->getSystemAccountAggregate()->getAccount($isReadOnlyDb);
    }

    /**
     * @param Account|null $account
     * @return static
     */
    public function setSystemAccount(?Account $account): static
    {
        $this->getSystemAccountAggregate()->setAccount($account);
        return $this;
    }

    // --- AccountAggregate ---

    /**
     * @return AccountAggregate
     */
    protected function getSystemAccountAggregate(): AccountAggregate
    {
        if ($this->systemAccountAggregate === null) {
            $this->systemAccountAggregate = AccountAggregate::new();
        }
        return $this->systemAccountAggregate;
    }

    // --- Additional methods ---

    /**
     * Check, if current system account is portal sub-account of multiple tenant installation.
     * In case of single tenant installation, then return false.
     * @return bool
     */
    public function isPortalSystemAccount(): bool
    {
        $this->initSystemAccountFromApplicationContext();
        return $this->getSystemAccountAggregate()->isPortalAccount();
    }

    /**
     * Check, if currently we are visiting main account.
     * It is always true for Single Tenant installation.
     * @return bool
     */
    public function isMainSystemAccount(): bool
    {
        $this->initSystemAccountFromApplicationContext();
        return $this->getSystemAccountAggregate()->isMainAccount();
    }

    /**
     * Check, if currently we are visiting main account in SAM Portal installation
     * @return bool
     */
    public function isMainSystemAccountForMultipleTenant(): bool
    {
        $this->initSystemAccountFromApplicationContext();
        return $this->getSystemAccountAggregate()->isMainAccountForMultipleTenant();
    }

    private function initSystemAccountFromApplicationContext(): void
    {
        if (!$this->getSystemAccountAggregate()->hasAccountId()) {
            $this->setSystemAccountId(Application::getInstance()->getSystemAccountId());
        }
    }
}
