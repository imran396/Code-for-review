<?php
/**
 * General repository for BuyerGroup entity
 *
 * SAM-3691: Buyer Group related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3691
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           30 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of buyer group user filtered by criteria
 * $buyerGroupRepository = \Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepository::new()
 *     ->filterId($ids)      // array passed as argument
 *     ->filterActive(true)
 * $isFound = $buyerGroupRepository->exist();
 * $count = $buyerGroupRepository->count();
 * $users = $buyerGroupRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\BuyerGroup;

/**
 * Class BuyerGroupReadRepository
 */
class BuyerGroupReadRepository extends AbstractBuyerGroupReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
