<?php
/**
 * General repository for UserSentLots entity
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
 * $userSentLotsRepository = \Sam\Storage\ReadRepository\Entity\UserSentLots\UserSentLotsReadRepository::new()
 *     ->filterUserId($ids)      // array passed as argument
 * $isFound = $userSentLotsRepository->exist();
 * $count = $userSentLotsRepository->count();
 * $users = $userSentLotsRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserSentLots;

/**
 * Class UserSentLotsReadRepository
 */
class UserSentLotsReadRepository extends AbstractUserSentLotsReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}

