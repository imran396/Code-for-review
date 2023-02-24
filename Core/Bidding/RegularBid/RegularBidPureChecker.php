<?php
/**
 * SAM-11182: Extract timed lot bidding logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Bidding\RegularBid;

use Auction;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TimedAuctionBidChecker
 * @package Sam\Core\Bidding\TimedAuction
 */
class RegularBidPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if $bid1 beat $bid2
     *
     * @param float $bid1
     * @param float $bid2
     * @param Auction $auction
     * @return bool
     */
    public function checkBidBeatBidInAuction(
        float $bid1,
        float $bid2,
        Auction $auction
    ): bool {
        return $this->checkBidBeatBid(
            $bid1,
            $bid2,
            $auction->AuctionType,
            $auction->Reverse
        );
    }

    public function checkBidBeatBidInTimedAuction(
        float $bid1,
        float $bid2,
        bool $isReverse = false
    ): bool {
        return $this->checkBidBeatBid(
            $bid1,
            $bid2,
            Constants\Auction::TIMED,
            $isReverse
        );
    }

    public function checkBidBeatBid(
        float $bid1,
        float $bid2,
        string $auctionType,
        bool $isReverse = false
    ): bool {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            return (
                    !$isReverse
                    && Floating::gt($bid1, $bid2)
                ) || (
                    $isReverse
                    && Floating::lt($bid1, $bid2)
                );
        }

        // Live/Hybrid auction
        return Floating::gt($bid1, $bid2);
    }

    /**
     * For Timed auction: Check if bid corresponds to current bid according to auction.reverse value
     *
     * @param float $maxBid
     * @param float $currentMaxBid
     * @param Auction $auction
     * @return bool
     */
    public function checkBidToCurrentMaxBid(
        float $maxBid,
        float $currentMaxBid,
        Auction $auction
    ): bool {
        $success = true;
        if ($auction->isTimed()) {
            $success = $this->checkBidBeatBidInTimedAuction($maxBid, $currentMaxBid, $auction->Reverse);
        }
        return $success;
    }

    /**
     * For Timed auction: Check if bid corresponds to asking according to auction.reverse value
     *
     * @param float $maxBid
     * @param float|null $askingBid null - means any bid
     * @param Auction $auction
     * @return bool
     */
    public function checkBidToAskingBid(
        float $maxBid,
        ?float $askingBid,
        Auction $auction
    ): bool {
        if ($askingBid === null) {
            return true;
        }

        $success = true;
        if ($auction->isTimed()) {
            $success = (!$auction->Reverse
                    && Floating::gteq($maxBid, $askingBid))
                || ($auction->Reverse
                    && Floating::lteq($maxBid, $askingBid));
        }
        return $success;
    }
}
