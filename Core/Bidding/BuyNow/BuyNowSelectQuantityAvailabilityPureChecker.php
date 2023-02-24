<?php
/**
 * Pure checker for buy now select quantity feature.
 * This core class implements detection logic of Buy Now with quantity choice function availability for lot.
 *
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Bidding\BuyNow;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Quantity\LotQuantityPureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyNowSelectQuantityAvailabilityPureChecker
 * @package Sam\Core\Bidding\BuyNow
 */
class BuyNowSelectQuantityAvailabilityPureChecker extends CustomizableClass
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
     * Check if all conditions are met to buy now with a quantity choice
     * If the Buy Now Select Quantity option is enabled, we need to check that bidding for lot is disabled and
     * the quantity with buyNowAmount is set
     *
     * @param bool $isEnabledForApp
     * @param bool $isEnabledForAccount
     * @param bool $isEnabledForAuctionLot
     * @param string $auctionType
     * @param bool $isAuctionReverse
     * @param float|null $buyNowAmount
     * @param bool $quantityXMoney
     * @param float|null $quantity
     * @param int $quantityScale
     * @param bool $isNoBidding
     * @return bool
     */
    public function isAvailable(
        bool $isEnabledForApp,
        bool $isEnabledForAccount,
        bool $isEnabledForAuctionLot,
        string $auctionType,
        bool $isAuctionReverse,
        ?float $buyNowAmount,
        bool $quantityXMoney,
        ?float $quantity,
        int $quantityScale,
        bool $isNoBidding
    ): bool {
        $isQuantityXMoneyEffective = LotQuantityPureCalculator::new()
            ->isQuantityXMoneyEffective($quantity, $quantityScale, $quantityXMoney);
        return $isEnabledForApp
            && $isEnabledForAccount
            && $isEnabledForAuctionLot
            && AuctionStatusPureChecker::new()->isTimed($auctionType)
            && !$isAuctionReverse
            && $buyNowAmount
            && $isQuantityXMoneyEffective
            && $isNoBidding
            && fmod(round($quantity, $quantityScale), 1) === 0.
            && Floating::gt($quantity, 1, $quantityScale);
    }
}
