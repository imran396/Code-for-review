<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal;

use AuctionCache;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionTotals;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionTotalsMessageFactory
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal
 * @internal
 */
class AuctionTotalsMessageFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build protobuf message object with an auction totals
     *
     * @param AuctionCache $auctionCache
     * @return AuctionTotals
     */
    public function create(AuctionCache $auctionCache): AuctionTotals
    {
        $auctionTotals = (new AuctionTotals())
            ->setTotalBid((float)$auctionCache->TotalBid)
            ->setTotalHammerPrice((float)$auctionCache->TotalHammerPrice)
            ->setTotalHammerPriceInternet((float)$auctionCache->TotalHammerPriceInternet)
            ->setTotalHighEstimate((float)$auctionCache->TotalHighEstimate)
            ->setTotalLowEstimate((float)$auctionCache->TotalLowEstimate)
            ->setTotalMaxBid((float)$auctionCache->TotalMaxBid)
            ->setTotalReserve((float)$auctionCache->TotalReserve)
            ->setTotalStartingBid((float)$auctionCache->TotalStartingBid)
            ->setTotalViews((int)$auctionCache->TotalViews);
        return $auctionTotals;
    }
}
