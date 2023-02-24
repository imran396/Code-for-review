<?php
/**
 * SAM-3725 : Tax related repositories  https://bidpath.atlassian.net/browse/SAM-3725
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           28 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of SamTaxCountryStates filtered by criteria
 * $samTaxCountryStatesRepository = \Sam\Storage\ReadRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $samTaxCountryStatesRepository->exist();
 * $count = $samTaxCountryStatesRepository->count();
 * $samTaxCountryStates = $samTaxCountryStatesRepository->loadEntities();
 *
 * // Sample2. Load single SamTaxCountryStates
 * $samTaxCountryStatesRepository = \Sam\Storage\ReadRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesReadRepository::new()
 *     ->filterId(1);
 * $samTaxCountryStates = $samTaxCountryStatesRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\SamTaxCountryStates;

/**
 * Class SamTaxCountryStatesReadRepository
 * @package Sam\Storage\ReadRepository\Entity\SamTaxCountryStates
 */
class SamTaxCountryStatesReadRepository extends AbstractSamTaxCountryStatesReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
