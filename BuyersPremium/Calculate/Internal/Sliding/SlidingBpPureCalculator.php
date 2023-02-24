<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate\Internal\Sliding;

use BuyersPremiumRange;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SlidingBpPureCalculator
 * @package Sam\BuyersPremium\Calculate\Internal\Tiered
 */
class SlidingBpPureCalculator extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param BuyersPremiumRange|null $bpr , null -buyer prime range can be null when no range is defined
     * @param float $amount
     * @param float $addPercent
     * @return float
     * #[Pure]
     */
    public function calculate(?BuyersPremiumRange $bpr, float $amount, float $addPercent): float
    {
        $premium = 0.;
        if (Floating::gteq($addPercent, 0.)) {
            $addPercent /= 100;
        }
        if ($bpr) {
            $bpFixed = (float)$bpr->Fixed;
            $percent = (Floating::gt($bpr->Percent, 0.) ? $bpr->Percent / 100 : 0.) + $addPercent;
            $bpPercent = $amount * $percent;
            if ($bpr->Mode === Constants\BuyersPremium::MODE_GREATER) {// Greater
                $premium = Floating::gteq($bpFixed, $bpPercent) ? $bpFixed : $bpPercent;
            } else {// Sum
                $premium = $bpFixed + $bpPercent;
            }
        } else {
            if (Floating::gteq($addPercent, 0.)) {
                $premium = $amount * $addPercent;
            }
        }
        return $premium;
    }
}
