<?php
/**
 * General repository for AuctionincStats entity
 *
 * SAM-3722 : Statistics related repositories https://bidpath.atlassian.net/browse/SAM-3722
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           5 August, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of AuctionincStats filtered by criteria
 * $auctionincStatsRepository = \Sam\Storage\ReadRepository\Entity\AuctionincStats\AuctionincStatsReadRepository::new()
 *     ->filterActive($active)   // array passed as argument
 * $isFound = $auctionincStatsRepository->exist();
 * $count = $auctionincStatsRepository->count();
 * $auctionincStats = $auctionincStatsRepository->loadEntities();
 *
 * // Sample2. Load single AuctionincStats
 * $auctionincStatsRepository = \Sam\Storage\ReadRepository\Entity\AuctionincStats\AuctionincStatsReadRepository::new()
 *     ->filterId(1);
 * $auctionincStat = $auctionincStatsRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionincStats;

/**
 * Class AuctionincStatsReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionincStats
 */
class AuctionincStatsReadRepository extends AbstractAuctionincStatsReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
