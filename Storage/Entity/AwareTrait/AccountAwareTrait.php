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
use Sam\Storage\Entity\Aggregate\AccountAggregate;

/**
 * Trait AccountAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait AccountAwareTrait
{
    protected ?AccountAggregate $accountAggregate = null;

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->getAccountAggregate()->getAccountId();
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setAccountId(?int $accountId): static
    {
        $this->getAccountAggregate()->setAccountId($accountId);
        return $this;
    }

    /**
     * Return Account|null object
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function getAccount(bool $isReadOnlyDb = false): ?Account
    {
        return $this->getAccountAggregate()->getAccount($isReadOnlyDb);
    }

    /**
     * @param Account|null $account
     * @return static
     */
    public function setAccount(?Account $account): static
    {
        $this->getAccountAggregate()->setAccount($account);
        return $this;
    }

    // --- AccountAggregate ---

    /**
     * @return AccountAggregate
     */
    protected function getAccountAggregate(): AccountAggregate
    {
        if ($this->accountAggregate === null) {
            $this->accountAggregate = AccountAggregate::new();
        }
        return $this->accountAggregate;
    }
}
