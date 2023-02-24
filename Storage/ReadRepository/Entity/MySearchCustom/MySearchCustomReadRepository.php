<?php
/**
 * General repository for MysearchCategory entity
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
 * $mySearchCustomRepository = \Sam\Storage\ReadRepository\Entity\MySearchCustom\MySearchCustomReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $mySearchCustomRepository->exist();
 * $count = $mySearchCustomRepository->count();
 * $mySearchCustomResults = $mySearchCategoryRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $mySearchCustomRepository = \Sam\Storage\ReadRepository\Entity\MySearchCustom\MySearchCustomReadRepository::new()
 *     ->filterId(1);
 * $mySearchCustomResult = $mySearchCategoryRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\MySearchCustom;

/**
 * Class MySearchCustomReadRepository
 * @package Sam\Storage\ReadRepository\Entity\MySearchCustom
 */
class MySearchCustomReadRepository extends AbstractMySearchCustomReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
