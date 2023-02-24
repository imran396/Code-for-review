<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several Account entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Account;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;

/**
 * Class AccountAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class AccountAggregate extends EntityAggregateBase
{
    use AccountLoaderAwareTrait;

    private ?int $accountId = null;
    private ?Account $account = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->accountId = null;
        $this->account = null;
        return $this;
    }

    // --- account.id ---

    /**
     * @return bool
     */
    public function hasAccountId(): bool
    {
        return $this->accountId > 0;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setAccountId(?int $accountId): static
    {
        $accountId = $accountId ?: null;
        if ($this->accountId !== $accountId) {
            $this->clear();
        }
        $this->accountId = $accountId;
        return $this;
    }

    // --- Account ---

    /**
     * @return bool
     */
    public function hasAccount(): bool
    {
        return $this->account !== null;
    }

    /**
     * Return Account object
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function getAccount(bool $isReadOnlyDb = false): ?Account
    {
        if ($this->account === null) {
            $this->account = $this->getAccountLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->getAccountId(), $isReadOnlyDb);
        }
        return $this->account;
    }

    /**
     * @param Account|null $account
     * @return static
     */
    public function setAccount(?Account $account = null): static
    {
        if (!$account) {
            $this->clear();
        } elseif ($account->Id !== $this->getAccountId()) {
            $this->clear();
            $this->accountId = $account->Id;
        }
        $this->account = $account;
        return $this;
    }

    /**
     * Check, if current system account is portal sub-account of multiple tenant installation
     * @return bool
     */
    public function isPortalAccount(): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isPortalAccount(
            $this->accountId,
            (bool)$this->cfg()->get('core->portal->enabled'),
            (int)$this->cfg()->get('core->portal->mainAccountId')
        );
    }

    /**
     * Check, if currently we are visiting main account.
     * It is always true for Single Tenant installation.
     * @return bool
     */
    public function isMainAccount(): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $this->accountId,
            (bool)$this->cfg()->get('core->portal->enabled'),
            (int)$this->cfg()->get('core->portal->mainAccountId')
        );
    }

    /**
     * Check, if currently we are visiting main account in SAM Portal installation
     * @return bool
     */
    public function isMainAccountForMultipleTenant(): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccountForMultipleTenant(
            $this->accountId,
            (bool)$this->cfg()->get('core->portal->enabled'),
            (int)$this->cfg()->get('core->portal->mainAccountId')
        );
    }
}
