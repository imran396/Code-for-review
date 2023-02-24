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

use AuctionBidderOption;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidderOption\AuctionBidderOptionReadRepositoryCreateTrait;

/**
 * Class AuctionBidderOptionLoader
 * @package Sam\Bidder\AuctionBidder\Load
 */
class AuctionBidderOptionLoader extends CustomizableClass
{
    use AuctionBidderOptionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load auction bidder option entity by id
     *
     * @param int|null $auctionBidderOptionId
     * @param bool $isReadOnlyDb
     * @return AuctionBidderOption|null
     */
    public function load(?int $auctionBidderOptionId, bool $isReadOnlyDb = false): ?AuctionBidderOption
    {
        if (!$auctionBidderOptionId) {
            return null;
        }

        return $this->createAuctionBidderOptionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($auctionBidderOptionId)
            ->filterActive(true)
            ->loadEntity();
    }
}
