<?php
/**
 * Factory for auction availability checkers
 *
 * SAM-4379: Create namespace for Auction Availability Checker functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Available;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;

/**
 * Class AuctionAvailabilityCheckerFactory
 * @package Sam\Auction\Available
 */
class AuctionAvailabilityCheckerFactory extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $auctionType
     * @return AuctionTimedAvailabilityChecker|AuctionLiveAvailabilityChecker|AuctionHybridAvailabilityChecker
     */
    public function create(string $auctionType): AuctionTimedAvailabilityChecker|AuctionLiveAvailabilityChecker|AuctionHybridAvailabilityChecker
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            return AuctionTimedAvailabilityChecker::new();
        }
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return AuctionLiveAvailabilityChecker::new();
        }
        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return AuctionHybridAvailabilityChecker::new();
        }
        throw new InvalidArgumentException("Unknown auction type: {$auctionType}");
    }
}
