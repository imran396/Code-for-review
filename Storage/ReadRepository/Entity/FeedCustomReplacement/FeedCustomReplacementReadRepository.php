<?php

/**
 * General repository for Feed Custom Replacement entity.
 *
 * SAM-3678 : Repositories for Feed tables
 * https://bidpath.atlassian.net/browse/SAM-3678
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           02 April, 2017
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
 * $feedCustomReplacementRepository = \Sam\Storage\ReadRepository\Entity\FeedCustomReplacement\FeedCustomReplacementReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterId($ids);
 * $isFound = $feedCustomReplacementRepository->exist();
 * $count = $feedCustomReplacementRepository->count();
 * $feedCustomReplacements = $feedCustomReplacementRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\FeedCustomReplacement;

/**
 * Class FeedCustomReplacementReadRepository
 * @package Sam\Storage\ReadRepository\Entity\FeedCustomReplacement
 */
class FeedCustomReplacementReadRepository extends AbstractFeedCustomReplacementReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
