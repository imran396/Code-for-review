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
 * $mySearchCategoryRepository = \Sam\Storage\ReadRepository\Entity\MySearchCategory\MySearchCategoryReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId($id);
 * $isFound = $mySearchCategoryRepository->exist();
 * $count = $mySearchCategoryRepository->count();
 * $mySearchCategoryResults = $mySearchCategoryRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $mySearchCategoryRepository = \Sam\Storage\ReadRepository\Entity\MySearchCategory\MySearchCategoryReadRepository::new()
 *     ->filterId(1);
 * $mySearchCategoryResult = $mySearchCategoryRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\MySearchCategory;

/**
 * Class MySearchCategoryReadRepository
 * @package Sam\Storage\ReadRepository\Entity\MySearchCategory
 */
class MySearchCategoryReadRepository extends AbstractMySearchCategoryReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
