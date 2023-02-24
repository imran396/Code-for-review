<?php
/**
 * SAM-4452: Apply Auction Bidder Deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\AbsenteeBid\AbsenteeBidDeleteRepositoryCreateTrait;

/**
 * Class AbsenteeBidDeleter
 * @package Sam\Bidding\AbsenteeBid\Delete
 */
class AbsenteeBidDeleter extends CustomizableClass
{
    use AbsenteeBidDeleteRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete absentee bids for user in auction avoiding processing in AbsenteeBid::Delete()
     * @param int $auctionId
     * @param int $userId
     */
    public function deleteForAuctionAndUserAvoidObserver(int $auctionId, int $userId): void
    {
        $this->createAbsenteeBidDeleteRepository()
            ->filterAuctionId($auctionId)
            ->filterUserId($userId)
            ->delete();
    }
}
