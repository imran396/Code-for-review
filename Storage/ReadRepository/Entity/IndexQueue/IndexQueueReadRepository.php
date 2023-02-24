<?php
/**
 * SAM-3726 Search index related repositories  https://bidpath.atlassian.net/browse/SAM-3726
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of IndexQueue filtered by criteria
 * $indexQueueRepository = \Sam\Storage\ReadRepository\Entity\IndexQueue\IndexQueueReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $indexQueueRepository->exist();
 * $count = $indexQueueRepository->count();
 * $indexQueue = $indexQueueRepository->loadEntities();
 *
 * // Sample2. Load single IndexQueue
 * $indexQueueRepository = \Sam\Storage\ReadRepository\Entity\IndexQueue\IndexQueueReadRepository::new()
 *     ->filterId(1);
 * $indexQueue = $indexQueueRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\IndexQueue;

/**
 * Class IndexQueueReadRepository
 * @package Sam\Storage\ReadRepository\Entity\IndexQueue
 */
class IndexQueueReadRepository extends AbstractIndexQueueReadRepository
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
