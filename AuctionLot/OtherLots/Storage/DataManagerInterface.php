<?php

namespace Sam\AuctionLot\OtherLots\Storage;

use AuctionLotItem;

/**
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Oct 20, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
interface DataManagerInterface
{
    /**
     * Get lots of an auction
     * @param int $auctionId
     * @param int $amount
     * @param int $offset
     * @return AuctionLotItem[]
     */
    public function getAuctionLots(int $auctionId, int $amount, int $offset = 0): iterable;

    /**
     * @param int $auctionId
     * @return int
     */
    public function countAllAuctionLots(int $auctionId): int;

    /**
     * @param int $auctionId
     * @return AuctionLotItem[]
     */
    public function getAllOrderedAuctionLotIds(int $auctionId): iterable;

    /**
     * @param int[] $ids
     * @return AuctionLotItem[]
     */
    public function getAuctionLotsByIds(array $ids): iterable;
}
