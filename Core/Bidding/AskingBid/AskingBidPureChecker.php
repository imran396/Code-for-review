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

namespace Sam\Core\Bidding\AskingBid;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AskingBidPureChecker
 * @package Sam\Core\Bidding\AskingBid
 */
class AskingBidPureChecker extends CustomizableClass
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
     * Check, if bid amount meet asking bid
     *
     * @param float|null $amount
     * @param float|null $askingBid
     * @param bool $isReverse
     * @return bool
     */
    public function meet(?float $amount, ?float $askingBid, bool $isReverse = false): bool
    {
        if ($isReverse) {
            return $askingBid === null // null asking bid means any amount for the first bid where starting bid is undefined
                || Floating::lteq($amount, $askingBid);
        }

        return Floating::gteq($amount, $askingBid);
    }

}
