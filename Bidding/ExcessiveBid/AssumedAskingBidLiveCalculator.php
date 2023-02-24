<?php
/**
 * Calculate a bid amount depends if can or not reveal absentee bid\asking bid
 * SAM-5229: Outrageous bid alert reveals hidden high absentee bid
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Jul, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ExcessiveBid;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ExcessiveBidHelper
 */
class AssumedAskingBidLiveCalculator extends CustomizableClass
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
     * Detect amount of asking bid, that can be revealed to user
     * it should be - in that order -
     * 0. actual asking, if is allowed to know its value
     * a. my current max absentee bid, (I may not have an absentee bid on the lot)
     * b. if there is none, the starting bid, (there may not be a starting bid on the lot,
     *    that’s possible on live and hybrid auctions)
     * c. if there is none, the first increment (there will always be an increment defined on the account,
     *    auction or lot level)
     * b and c calculated in $auctionLotCache->StartingBidNormalized
     *
     * @param float $askingBid
     * @param float|null $maxBid - null means absent maxBid
     * @param float $startingBid
     * @param bool $isNotifyAbsenteeBidders
     * @param string $absenteeBidDisplay
     * @return float
     */
    public function calc(
        float $askingBid,
        ?float $maxBid,
        float $startingBid,
        bool $isNotifyAbsenteeBidders,
        string $absenteeBidDisplay
    ): float {
        $amount = $askingBid;
        if (!$this->isActualAskingBidVisible($isNotifyAbsenteeBidders, $absenteeBidDisplay)) {
            $amount = $this->detectUserKnownAskingBid($startingBid, $maxBid);
            //in case amount == null\0, then we return askingBid as it contains first increment
            $amount = $amount ?: $askingBid;
        }
        return $amount;
    }

    /**
     * Checks, if actual asking bid value can be revealed to user
     * @param bool $isNotifyAbsenteeBidders
     * @param string $absenteeBidDisplay
     * @return bool
     */
    protected function isActualAskingBidVisible(bool $isNotifyAbsenteeBidders, string $absenteeBidDisplay): bool
    {
        $canReveal = !AuctionStatusPureChecker::new()->isAbsenteeBidsDisplaySetAsDoNotDisplay($absenteeBidDisplay)
            || $isNotifyAbsenteeBidders;
        return $canReveal;
    }

    /**
     * Detect amount, when we don't want to reveal actual asking bid
     * @param float $startingBidNormalized
     * @param float|null $maxBid - null means absent maxBid
     * @return float
     */
    protected function detectUserKnownAskingBid(float $startingBidNormalized, ?float $maxBid): float
    {
        $amount = $maxBid ?: $startingBidNormalized;
        return $amount;
    }
}
