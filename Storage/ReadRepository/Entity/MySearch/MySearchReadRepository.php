<?php
/**
 * General repository for Mysearch entity
 *
 * SAM-3723 : My search related repositories https://bidpath.atlassian.net/browse/SAM-3723
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           25 june, 2017
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
 * $mySearchRepository = \Sam\Storage\ReadRepository\Entity\MySearch\MySearchReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $mySearchRepository->exist();
 * $count = $mySearchRepository->count();
 * $mySearchResults = $mySearchRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $mySearchRepository = \Sam\Storage\ReadRepository\Entity\MySearch\MySearchReadRepository::new()
 *     ->filterId(1);
 * $mySearchResult = $mySearchRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\MySearch;

/**
 * Class MySearchReadRepository
 * @package Sam\Storage\ReadRepository\Entity\MySearch
 */
class MySearchReadRepository extends AbstractMySearchReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = ms.account_id',
        'user' => 'JOIN `user` u ON u.id = ms.user_id ',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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

    /**
     * Join `account` table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }
}
