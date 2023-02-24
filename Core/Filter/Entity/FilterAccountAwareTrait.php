<?php
/**
 * SAM-4616: Reports code refactoring
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

namespace Sam\Core\Filter\Entity;

use Account;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Storage\Entity\Aggregate\AccountAggregate;

/**
 * Trait FilterAccountAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterAccountAwareTrait
{
    /** @var AccountAggregate[] */
    protected array $filterAccountAggregates = [];

    /**
     * @return int|int[]|null
     */
    public function getFilterAccountId(): int|array|null
    {
        $accountIds = [];
        foreach ($this->getFilterAccountAggregates() as $accountAggregate) {
            $accountIds[] = $accountAggregate->getAccountId();
        }
        $returnValue = empty($accountIds) ? null
            : (count($accountIds) === 1 ? $accountIds[0] : $accountIds);
        return $returnValue;
    }

    /**
     * @param int|int[]|null $accountIds
     * @return static
     */
    public function filterAccountId(int|array|null $accountIds): static
    {
        $this->initFilterAccountAggregates($accountIds);
        return $this;
    }

    /**
     * @return Account|Account[]|null
     */
    public function getFilterAccount(): Account|array|null
    {
        $accounts = [];
        foreach ($this->getFilterAccountAggregates() as $accountAggregate) {
            $accounts[] = $accountAggregate->getAccount();
        }
        $returnValue = empty($accounts) ? null
            : (count($accounts) === 1 ? $accounts[0] : $accounts);
        return $returnValue;
    }

    /**
     * @param Account|Account[]|null $accounts
     * @return static
     */
    public function filterAccount(Account|array|null $accounts = null): static
    {
        $this->initFilterAccountAggregates($accounts);
        return $this;
    }

    // --- AccountAggregate ---

    /**
     * @return AccountAggregate[]
     */
    protected function getFilterAccountAggregates(): array
    {
        return $this->filterAccountAggregates;
    }

    /**
     * @param int|int[]|Account|Account[]|null $accounts
     */
    protected function initFilterAccountAggregates(Account|int|array|null $accounts): void
    {
        $this->filterAccountAggregates = [];
        if (empty($accounts)) {
            return;
        }
        $accounts = is_array($accounts) ? $accounts : [$accounts];
        if (!($accounts[0] instanceof Account)) {
            $accountIds = ArrayCast::makeIntArray($accounts);
            foreach ($accountIds as $accountId) {
                $this->filterAccountAggregates[$accountId] = AccountAggregate::new()->setAccountId($accountId);
            }
        } else {
            foreach ($accounts as $account) {
                $this->filterAccountAggregates[$account->Id] = AccountAggregate::new()->setAccount($account);
            }
        }
    }
}
