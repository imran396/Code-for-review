<?php
/**
 * Contains live auction availability checking methods
 *
 * SAM-4379: Create namespace for Auction Availability Checker functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 5, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Available;

use Auction;
use DateTimeInterface;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;

/**
 * Class AuctionLiveAvailabilityChecker
 * @package Sam\Auction\Available
 */
class AuctionLiveAvailabilityChecker extends AuctionAvailabilityCheckerBase
{
    use CurrentDateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if bidding console available for live
     * @param Auction $auction
     * @return bool
     */
    public function isBiddingConsoleAvailable(Auction $auction): bool
    {
        return $this->isBiddingConsoleAvailableByValues($auction->AuctionStatusId, $auction->BiddingConsoleAccessDate);
    }

    /**
     * Check, if bidding console available for live
     * @param int $auctionStatusId
     * @param DateTimeInterface|null $biddingConsoleAccessDateUtc
     * @return bool
     */
    public function isBiddingConsoleAvailableByValues(int $auctionStatusId, ?DateTimeInterface $biddingConsoleAccessDateUtc): bool
    {
        if ($biddingConsoleAccessDateUtc === null) {
            return false;
        }

        $currentDateUtc = $this->getCurrentDateUtc();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isAvailable = ($auctionStatusPureChecker->isStartedOrPaused($auctionStatusId)
            || ($auctionStatusPureChecker->isActive($auctionStatusId)
                && $biddingConsoleAccessDateUtc <= $currentDateUtc)
        );
        return $isAvailable;
    }
}
