<?php
/**
 * SAM-3695 : Settlement related repositories  https://bidpath.atlassian.net/browse/SAM-3695
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of SettlementAdditional filtered by criteria
 * $settlementAdditionalRepository = \Sam\Storage\ReadRepository\Entity\SettlementAdditional\SettlementAdditionalReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $settlementAdditionalRepository->exist();
 * $count = $settlementAdditionalRepository->count();
 * $settlementAdditional = $settlementAdditionalRepository->loadEntities();
 *
 * // Sample2. Load single SettlementAdditional
 * $settlementAdditionalRepository = \Sam\Storage\ReadRepository\Entity\SettlementAdditional\SettlementAdditionalReadRepository::new()
 *     ->filterId(1);
 * $settlementAdditional = $settlementAdditionalRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementAdditional;

/**
 * Class SettlementAdditionalReadRepository
 * @package Sam\Storage\ReadRepository\Entity\SettlementAdditional
 */
class SettlementAdditionalReadRepository extends AbstractSettlementAdditionalReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
