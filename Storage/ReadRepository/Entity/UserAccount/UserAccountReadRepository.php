<?php
/**
 * General repository for UserAccount entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           06 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of users filtered by criteria
 * $userAccountRepository = \Sam\Storage\ReadRepository\Entity\UserAccount\UserAccountReadRepository::new()
 *     ->filterAccountId($accountIds)          // single value passed as argument
 *     ->filterUserId($ids)      // array passed as argument
 * $isFound = $userAccountRepository->exist();
 * $count = $userAccountRepository->count();
 * $users = $userAccountRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccount;

/**
 * Class UserAccountReadRepository
 */
class UserAccountReadRepository extends AbstractUserAccountReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = ua.account_id'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join `account` table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by acc.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }
}
