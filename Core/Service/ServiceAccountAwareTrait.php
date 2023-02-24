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

namespace Sam\Core\Service;

use Account;
use Sam\Storage\Entity\Aggregate\AccountAggregate;

/**
 * Trait ServiceAccountAwareTrait
 * @package Sam\Core\Service
 */
trait ServiceAccountAwareTrait
{
    /** @var AccountAggregate|null */
    protected ?AccountAggregate $serviceAccountAggregate = null;

    /**
     * @return int|null
     */
    public function getServiceAccountId(): ?int
    {
        return $this->getServiceAccountAggregate()->getAccountId();
    }

    /**
     * @param int|null $accountId
     * @return static
     */
    public function setServiceAccountId(?int $accountId): static
    {
        $this->getServiceAccountAggregate()->setAccountId($accountId);
        return $this;
    }

    /**
     * Return Account|null object
     * @param bool $isReadOnlyDb
     * @return Account|null
     */
    public function getServiceAccount(bool $isReadOnlyDb = false): ?Account
    {
        return $this->getServiceAccountAggregate()->getAccount($isReadOnlyDb);
    }

    /**
     * @param Account|null $account
     * @return static
     */
    public function setServiceAccount(?Account $account = null): static
    {
        $this->getServiceAccountAggregate()->setAccount($account);
        return $this;
    }

    // --- AccountAggregate ---

    /**
     * @return AccountAggregate
     */
    protected function getServiceAccountAggregate(): AccountAggregate
    {
        if ($this->serviceAccountAggregate === null) {
            $this->serviceAccountAggregate = AccountAggregate::new();
        }
        return $this->serviceAccountAggregate;
    }
}
