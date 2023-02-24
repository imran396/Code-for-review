<?php
/**
 * General repository for ActionQueue entity
 *
 * SAM-3727 Application settings/workflow related repositories https://bidpath.atlassian.net/browse/SAM-3727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since          29 June, 2017
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
 * $actionQueueRepository = \Sam\Storage\ReadRepository\Entity\ActionQueue\ActionQueueReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterAccountId(cfg()->core->portal->mainAccountId);           // search for main account actions
 * $isFound = $actionQueueRepository->exist();
 * $count = $actionQueueRepository->count();
 * $actionQueues = $actionQueueRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $actionQueueRepository = \Sam\Storage\ReadRepository\Entity\ActionQueue\ActionQueueReadRepository::new()
 *     ->filterId(1);
 * $actionQueue = $actionQueueRepository->loadEntity();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\ActionQueue;

/**
 * Class ActionQueueReadRepository
 * @package Sam\Storage\ReadRepository\Entity\ActionQueue
 */
class ActionQueueReadRepository extends AbstractActionQueueReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function filterAttemptsLowerThenMaxAttempts(): static
    {
        $this->inlineCondition('aq.attempts < aq.max_attempts');
        return $this;
    }
}
