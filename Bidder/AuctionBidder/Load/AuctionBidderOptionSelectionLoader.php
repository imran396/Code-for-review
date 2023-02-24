<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

use AuctionBidderOptionSelection;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionReadRepositoryCreateTrait;

/**
 * Class AuctionBidderOptionSelectionLoader
 * @package Sam\Bidder\AuctionBidder\Load
 */
class AuctionBidderOptionSelectionLoader extends CustomizableClass
{
    use AuctionBidderOptionSelectionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load auction bidder option selection entity
     *
     * @param int $auctionId
     * @param int $userId
     * @param int $auctionBidderOptionId
     * @param bool $isReadOnlyDb
     * @return AuctionBidderOptionSelection|null
     */
    public function load(int $auctionId, int $userId, int $auctionBidderOptionId, bool $isReadOnlyDb = false): ?AuctionBidderOptionSelection
    {
        $auctionBidderOptionSelection = $this->createAuctionBidderOptionSelectionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterAuctionBidderOptionId($auctionBidderOptionId)
            ->filterUserId($userId)
            ->loadEntity();
        return $auctionBidderOptionSelection;
    }
}
