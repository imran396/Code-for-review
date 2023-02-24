<?php
/**
 * General repository for UserCustField entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/browse/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Mar, 2017
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
 * $userCustFieldRepository = \Sam\Storage\Repository\UserCustFieldReadRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $userCustFieldRepository->exist();
 * $count = $userCustFieldRepository->count();
 * $users = $userCustFieldRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $userCustFieldRepository = \Sam\Storage\Repository\UserCustFieldReadRepository::new()
 *     ->filterId(1);
 * $user = $userCustFieldRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustField;

/**
 * Class UserCustFieldReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserCustField
 */
class UserCustFieldReadRepository extends AbstractUserCustFieldReadRepository
{
    protected array $joins = [
        'user_cust_data' => 'JOIN user_cust_data AS ucd ON ucf.id = ucd.user_cust_field_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `user_cust_data` table
     * @return static
     */
    public function joinUserCustData(): static
    {
        $this->join('user_cust_data');
        return $this;
    }

    /**
     * Define filtering by ucd.active
     * @param bool|bool[]|null $active
     * @return static
     */
    public function joinUserCustDataFilterActive(bool|array|null $active): static
    {
        $this->joinUserCustData();
        $this->filterArray('ucd.active', $active);
        return $this;
    }

    /**
     * Define LIKE filter condition ucd.text
     * @param string $text
     * @return static
     */
    public function joinUserCustDataLikeText(string $text): static
    {
        $this->joinUserCustData();
        $this->like('ucd.text', $text);
        return $this;
    }
}

