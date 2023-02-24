<?php
/**
 * General repository for UserSalesCommission entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           09 Mar, 2017
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
 * $userSalesCommissionRepository = \Sam\Storage\ReadRepository\Entity\UserSalesCommission\UserSalesCommissionReadRepository::new()
 *     ->filterUserId($ids)      // array passed as argument
 *     ->filterAccountId($accountIds)      // array passed as argument
 * $isFound = $userSalesCommissionRepository->exist();
 * $count = $userSalesCommissionRepository->count();
 * $users = $userSalesCommissionRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserSalesCommission;

/**
 * Class UserSalesCommissionReadRepository
 */
class UserSalesCommissionReadRepository extends AbstractUserSalesCommissionReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}

