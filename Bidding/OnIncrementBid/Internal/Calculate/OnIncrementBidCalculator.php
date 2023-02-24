<?php
/**
 * If bid amount is off increment then it provides us nearest and next on increment bid message.
 *
 * SAM-1878: Improved prevent off increment bids
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @author        Imran Rahman
 * @version       SVN: 3.0
 * @since         Feb 10, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\OnIncrementBid\Internal\Calculate;

use Sam\Core\Bidding\AskingBid\AskingBidPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class OnIncrementBidValidator
 * @package Sam\Bidding\OnIncrementBid
 */
class OnIncrementBidCalculator extends CustomizableClass
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
     * Search for nearest to max bid on-increment bid.
     * Can be: 1. startingBid 2. prev increment 3. next increment 4. asking bid
     * @param float $checkingAmount
     * @param float|null $currentBid
     * @param float|null $quantizedLowBid
     * @param float|null $quantizedHighBid
     * @param float|null $startingBid
     * @param float|null $askingBid
     * @param bool $isReverse
     * @return float
     * #[Pure]
     */
    public function calculateLowEffective(
        float $checkingAmount,
        ?float $currentBid,
        ?float $quantizedLowBid,
        ?float $quantizedHighBid,
        ?float $startingBid,
        ?float $askingBid,
        bool $isReverse = false
    ): float {
        if (!AskingBidPureChecker::new()->meet($checkingAmount, $askingBid, $isReverse)) {
            return $askingBid;
        }

        /**
         * Below condition also implicitly considers that "starting bid" may be undefined in reverse auction,
         * and then we skip this block that results with "starting bid"
         */
        if (
            !$currentBid
            && $startingBid
        ) {
            $quant = $quantizedHighBid - $quantizedLowBid;
            $bidDiff = abs($checkingAmount - $startingBid);
            if (Floating::lt($bidDiff, $quant)) {
                return $startingBid;
            }
        }

        return $quantizedLowBid;
    }
}
