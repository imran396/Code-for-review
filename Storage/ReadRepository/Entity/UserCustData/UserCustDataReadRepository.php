<?php
/**
 * General repository for UserCustData entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/browse/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           28 Mar, 2017
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
 * $userCustDataRepository = \Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepository::new()
 *     ->filterId($ids)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $userCustDataRepository->exist();
 * $count = $userCustDataRepository->count();
 * $users = $userCustDataRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $userCustFieldRepository = \Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepository::new()
 *     ->filterId(1);
 * $user = $userCustDataRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustData;

/**
 * Class UserCustDataReadRepository
 */
class UserCustDataReadRepository extends AbstractUserCustDataReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = u.account_id',
        'user_cust_field' => 'JOIN user_cust_field ucf ON ucf.id = ucd.user_cust_field_id',
        'user' => 'JOIN user u ON ucd.user_id = u.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Define filtering by u.user_status_id
     * @param int|int[] $userStatusIds
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusIds): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusIds);
        return $this;
    }

    /**
     * Join `user_cust_field` table
     * @return static
     */
    public function joinUserCustField(): static
    {
        $this->join('user_cust_field');
        return $this;
    }

    /**
     * Define filtering by ucf.active
     * @param bool|bool[]|null $active
     * @return static
     */
    public function joinUserCustFieldFilterActive(bool|array|null $active): static
    {
        $this->joinUserCustField();
        $this->filterArray('ucf.active', $active);
        return $this;
    }

    /**
     * Define filtering by ucf.type
     * @param string|string[]|null $type
     * @return static
     */
    public function joinUserCustFieldFilterType(string|array|null $type): static
    {
        $this->joinUserCustField();
        $this->filterArray('ucf.type', $type);
        return $this;
    }

    /**
     * Left join user table
     * Define filtering by u.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinUserFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinUser();
        $this->filterArray('u.account_id', $accountIds);
        return $this;
    }

    /**
     * Define ordering by u.account_id
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByAccountId(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.account_id', $ascending);
        return $this;
    }
}

