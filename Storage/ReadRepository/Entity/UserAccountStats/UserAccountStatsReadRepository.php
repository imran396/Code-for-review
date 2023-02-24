<?php
/**
 * General repository for UserAccountStats entity
 *
 * SAM-3722 : Statistics related repositories https://bidpath.atlassian.net/browse/SAM-3722
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           3 July, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of accounts filtered by criteria
 * $userAccountStatsRepository = \Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $userAccountStatsRepository->exist();
 * $count = $userAccountStatsRepository->count();
 * $userAccountStats = $userAccountStatsRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $userAccountStatsRepository = \Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepository::new()
 *     ->filterId(1);
 * $userAccountStats = $userAccountStatsRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStats;

/**
 * Class UserAccountStatsReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserAccountStats
 */
class UserAccountStatsReadRepository extends AbstractUserAccountStatsReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'user' => 'JOIN user u ON uas.user_id = u.id',
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
