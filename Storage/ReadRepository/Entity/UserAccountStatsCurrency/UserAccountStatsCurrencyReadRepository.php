<?php
/**
 * General repository for UserAccountStatsCurrency entity
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
 * $userAccountStatsCurrencyRepository = \Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency\UserAccountStatsCurrencyReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $userAccountStatsCurrencyRepository->exist();
 * $count = $userAccountStatsCurrencyRepository->count();
 * $userAccountStats = $userAccountStatsCurrencyRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $userAccountStatsCurrencyRepository = \Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency\UserAccountStatsCurrencyReadRepository::new()
 *     ->filterId(1);
 * $userAccountStats = $userAccountStatsCurrencyRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency;

/**
 * Class UserAccountStatsCurrencyReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency
 */
class UserAccountStatsCurrencyReadRepository extends AbstractUserAccountStatsCurrencyReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
