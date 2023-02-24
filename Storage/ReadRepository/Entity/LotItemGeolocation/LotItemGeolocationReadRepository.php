<?php
/**
 * General repository for LotItemGeolocation entity
 *
 * SAM-3686:Custom field related repositories https://bidpath.atlassian.net/browse/SAM-3686
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           22 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of LotItemGeolocation filtered by criteria
 * $lotItemGeolocationRepository = \Sam\Storage\ReadRepository\Entity\LotItemGeolocation\LotItemGeolocationReadRepository::new()
 *     ->filterId($ids)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $lotItemGeolocationRepository->exist();
 * $count = $lotItemGeolocationRepository->count();
 * $lotItemGeolocation = $lotItemGeolocationRepository->loadEntities();
 *
 * // Sample2. Load single LotItemGeolocation
 * $lotItemGeolocationRepository = \Sam\Storage\ReadRepository\Entity\LotItemGeolocation\LotItemGeolocationReadRepository::new()
 *     ->filterId(1);
 * $lotItemGeolocation = $lotItemGeolocationRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemGeolocation;

/**
 * Class LotItemGeolocationReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotItemGeolocation
 */
class LotItemGeolocationReadRepository extends AbstractLotItemGeolocationReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
