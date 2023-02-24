<?php
/**
 * Help methods for Bid Increment deleting
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 17, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\BidIncrement\BidIncrementDeleteRepositoryCreateTrait;

/**
 * Class BidIncrementDeleter
 * @package Sam\Bidding\BidIncrement\Delete
 */
class BidIncrementDeleter extends CustomizableClass
{
    use BidIncrementDeleteRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete bid increment for auction
     * @param int $auctionId
     */
    public function deleteForAuction(int $auctionId): void
    {
        $this->createBidIncrementDeleteRepository()
            ->filterAuctionId($auctionId)
            ->delete();
    }

    /**
     * Delete bid increment for lot
     * @param int $lotItemId
     */
    public function deleteForLot(int $lotItemId): void
    {
        $this->createBidIncrementDeleteRepository()
            ->filterLotItemId($lotItemId)
            ->delete();
    }
}
