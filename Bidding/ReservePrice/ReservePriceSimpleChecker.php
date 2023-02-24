<?php
/**
 * SAM-5045: Reserve met label for auctions
 *
 * This class intended to be simple without state, like helper with static methods
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/03/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ReservePrice;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ReservePriceSimpleChecker
 * @package Sam\Bidding\ReservePrice
 */
class ReservePriceSimpleChecker extends CustomizableClass
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
     * Check, if amount of bid meet the reserve price.
     * @param float|null $amount
     * @param float|null $reservePrice
     * @param bool $isAuctionReverse
     * @return bool
     */
    public function meet(
        ?float $amount,
        ?float $reservePrice,
        bool $isAuctionReverse = false
    ): bool {
        $isMet = $isAuctionReverse
            ? Floating::lteq($amount, $reservePrice)
            : Floating::gteq($amount, $reservePrice);
        return $isMet;
    }
}
