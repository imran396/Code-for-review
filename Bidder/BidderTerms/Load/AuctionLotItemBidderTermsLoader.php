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

namespace Sam\Bidder\BidderTerms\Load;

use AuctionLotItemBidderTerms;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsReadRepositoryCreateTrait;

/**
 * Class AuctionLotItemBidderTermsLoader
 * @package Sam\Bidder\BidderTerms\Load
 */
class AuctionLotItemBidderTermsLoader extends CustomizableClass
{
    use AuctionLotItemBidderTermsReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a AuctionLotItemBidderTerms entity
     *
     * @param int $auctionId
     * @param int $lotItemId
     * @param int $userId
     * @param bool $isReadOnlyDb query to read-only db
     * @return AuctionLotItemBidderTerms|null
     */
    public function load(int $auctionId, int $lotItemId, int $userId, bool $isReadOnlyDb = false): ?AuctionLotItemBidderTerms
    {
        $auctionLotItemBidderTerms = $this->createAuctionLotItemBidderTermsReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterUserId($userId)
            ->loadEntity();
        return $auctionLotItemBidderTerms;
    }
}
