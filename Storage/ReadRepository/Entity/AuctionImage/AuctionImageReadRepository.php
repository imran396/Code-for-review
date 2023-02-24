<?php
/**
 * General repository for AuctionImage entity
 *
 * SAM-3685:Image related repositories https://bidpath.atlassian.net/browse/SAM-3685
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           26 Apr, 2017
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
 * $auctionImageRepository = \Sam\Storage\ReadRepository\Entity\AuctionImage\AuctionImageReadRepository::new()
 *     ->filterId($ids)          // single value passed as argument
 *     ->filterActive($active)   // array passed as argument
 *     ->skipId([$myId]);        // search avoiding these user ids
 * $isFound = $auctionImageRepository->exist();
 * $count = $auctionImageRepository->count();
 * $auctionImages = $auctionImageRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $auctionImageRepository = \Sam\Storage\ReadRepository\Entity\AuctionImage\AuctionImageReadRepository::new()
 *     ->filterId(1);
 * $auctionImage = $auctionImageRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionImage;

/**
 * Class AuctionImageReadRepository
 */
class AuctionImageReadRepository extends AbstractAuctionImageReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
