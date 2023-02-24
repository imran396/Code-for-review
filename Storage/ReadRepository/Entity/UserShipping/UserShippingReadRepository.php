<?php
/**
 * General repository for UserShipping entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           08 Mar, 2017
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
 * $userShippingRepository = \Sam\Storage\ReadRepository\Entity\UserShipping\UserShippingReadRepository::new()
 *     ->filterUserId($ids)      // array passed as argument
 * $isFound = $userShippingRepository->exist();
 * $count = $userShippingRepository->count();
 * $users = $userShippingRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserShipping;

/**
 * Class UserShippingReadRepository
 */
class UserShippingReadRepository extends AbstractUserShippingReadRepository
{
    protected array $joins = [
        'user' => 'JOIN user u ON us.user_id = u.id',
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
     * join `user` table and define filtering by u.user_status_id
     * @param int|int[] $userStatusId
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusId): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusId);
        return $this;
    }
}
