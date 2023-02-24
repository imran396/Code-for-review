<?php
/**
 * General repository for CachedQueue entity
 *
 * SAM-3685:Image related repositories https://bidpath.atlassian.net/browse/SAM-3685
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           27 Apr, 2017
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
 * $cachedQueueRepository = \Sam\Storage\ReadRepository\Entity\CachedQueue\CachedQueueReadRepository::new()
 *     ->filterId($ids)          // single value passed as argument
 *     ->filterActive($active)   // array passed as argument
 *     ->skipId([$myId]);        // search avoiding these user ids
 * $isFound = $cachedQueueRepository->exist();
 * $count = $cachedQueueRepository->count();
 * $cachedQueues = $cachedQueueRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $cachedQueueRepository = \Sam\Storage\ReadRepository\Entity\CachedQueue\CachedQueueReadRepository::new()
 *     ->filterId(1);
 * $cachedQueue = $cachedQueueRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\CachedQueue;

/**
 * Class CachedQueueReadRepository
 */
class CachedQueueReadRepository extends AbstractCachedQueueReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by cq.cached between
     * @param int $from
     * @param int $to
     * @return static
     */
    public function betweenCached(int $from, int $to): static
    {
        $this->between('cq.cached', $from, $to);
        return $this;
    }
}

