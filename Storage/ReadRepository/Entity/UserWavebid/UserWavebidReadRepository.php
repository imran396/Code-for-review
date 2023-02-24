<?php
/**
 * General repository for UserWavebid entity
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
 * $userWavebidRepository = \Sam\Storage\ReadRepository\Entity\UserWavebid\UserWavebidReadRepository::new()
 *     ->filterAccountId($accountIds)          // single value passed as argument
 *     ->filterUserId($ids)      // array passed as argument
 * $isFound = $userWavebidRepository->exist();
 * $count = $userWavebidRepository->count();
 * $users = $userWavebidRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserWavebid;

/**
 * Class UserWavebidReadRepository
 */
class UserWavebidReadRepository extends AbstractUserWavebidReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON uwavebid.account_id = acc.id',
        'user' => 'JOIN user u ON uwavebid.user_id = u.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
     * Left join user table and filter by user status
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
}
