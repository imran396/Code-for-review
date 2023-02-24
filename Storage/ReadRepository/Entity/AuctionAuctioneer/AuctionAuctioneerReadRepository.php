<?php
/**
 * General repository for AuctionAuctioneerReadRepository Parameters entity
 *
 * SAM-3688:  Auction related repositories  https://bidpath.atlassian.net/browse/SAM-3688
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionAuctioneerRepository = \Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionStatusId($auctionStatusIds)
 *      ->filterAccountId($auctionIds);
 * $isFound = $auctionAuctioneerRepository->exist();
 * $count = $auctionAuctioneerRepository->count();
 * $item = $auctionAuctioneerRepository->loadEntity();
 * $items = $auctionAuctioneerRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionAuctioneer;

/**
 * Class AuctionAuctioneerReadRepository
 */
class AuctionAuctioneerReadRepository extends AbstractAuctionAuctioneerReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
