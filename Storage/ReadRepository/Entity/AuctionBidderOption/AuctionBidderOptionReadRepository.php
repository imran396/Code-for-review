<?php
/**
 * General repository for AuctionBidderOptionReadRepository Parameters entity
 *
 * SAM-3680: Bidder and consignor related repositories https://bidpath.atlassian.net/browse/SAM-3680
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Apr, 2017
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
 * $auctionBidderOptionRepository = \Sam\Storage\ReadRepository\Entity\AuctionBidderOption\AuctionBidderOptionReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAccountId($auctionIds);
 * $isFound = $auctionBidderOptionRepository->exist();
 * $count = $auctionBidderOptionRepository->count();
 * $item = $auctionBidderOptionRepository->loadEntity();
 * $items = $auctionBidderOptionRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidderOption;

/**
 * Class AuctionBidderOptionReadRepository
 */
class AuctionBidderOptionReadRepository extends AbstractAuctionBidderOptionReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
