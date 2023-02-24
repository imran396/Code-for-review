<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Bidding\StartingBid;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StartingBidPureChecker
 * @package Sam\Core\Bidding\StartingBid
 */
class StartingBidPureChecker extends CustomizableClass
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
     * Check, if bid amount meet starting bid
     *
     * @param float|null $amount
     * @param float|null $startingBid
     * @param bool $isReverse
     * @return bool
     */
    public function meet(?float $amount, ?float $startingBid, bool $isReverse = false): bool
    {
        if ($isReverse) {
            return $startingBid === null // null starting bid in reverse auction means any amount. Should "0" be as well considered as any starting bid?
                || Floating::lteq($amount, $startingBid);
        }

        return Floating::gteq($amount, $startingBid);
    }

}
