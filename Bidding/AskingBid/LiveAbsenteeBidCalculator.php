<?php
/**
 *  Asking bid calculator for live/hybrid auction lot.
 *
 * SAM-3502 : Accidental high bid warning
 * https://bidpath.atlassian.net/browse/SAM-3502
 *
 * @author        Imran Rahman
 * @version       SVN: 3.0
 * @since         Feb 10, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Bidding\AskingBid;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LiveAbsenteeBidCalculator
 * @package Sam\Bidding\AskingBid
 */
class LiveAbsenteeBidCalculator extends CustomizableClass
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
     * Calculating asking bid.
     *
     * @param float $highBid
     * @param float $secondBid
     * @param float $startingBid
     * @param float $increment
     * @param float $currentBid 0 when absent
     * @return float
     */
    public function calculate(
        float $highBid,
        float $secondBid,
        float $startingBid,
        float $increment,
        float $currentBid
    ): float {
        $askingBid = 0.;
        if (!$startingBid && !$highBid && !$secondBid) {
            $askingBid = $increment;
        } elseif ($startingBid && !$highBid && !$secondBid) {
            $askingBid = $startingBid;
        } elseif ($startingBid && $highBid && !$secondBid) {
            $askingBid = $startingBid + $increment;
        } elseif (!$startingBid && $highBid && !$secondBid) {
            $askingBid = $increment;
        } elseif ($highBid && $secondBid && Floating::neq($highBid, $secondBid)) {
            //$currentBid = $secondBid + $increment;
            $askingBid = $currentBid + $increment;
        } elseif ($highBid && $secondBid && Floating::eq($highBid, $secondBid)) {
            $askingBid = $highBid + $increment;
        }
        return $askingBid;
    }
}
