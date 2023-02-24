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

namespace Sam\BuyersPremium\Calculate\Internal\Tiered;

use BuyersPremiumRange;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class TieredBpCalculator
 * @package Sam\BuyersPremium\Calculate\Internal\Tiered
 */
class TieredBpPureCalculator extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param BuyersPremiumRange[] $bprArray
     * @param float $amount
     * @param float $addPercent
     * @return float
     * #[Pure]
     */
    public function calculate(array $bprArray, float $amount, float $addPercent): float
    {
        $premiumArray = [];
        $totalPremium = 0.;

        if (Floating::gteq($addPercent, 0.)) {
            $addPercent /= 100;
        }

        // loop and concatenate all percents in a string variable
        if ($bprArray) {
            foreach ($bprArray as $bpr) {
                $premiumArray[] = [
                    'amount' => $bpr->Amount,
                    'fixed' => $bpr->Fixed,
                    'percent' => $bpr->Percent,
                    'mode' => $bpr->Mode,
                ];
            }
        }

        $amtLeft = $amount;
        if (count($premiumArray) === 0) {
            // If NO bp range premium
            $totalPremium = $amount * $addPercent;
        }

        foreach ($premiumArray as $index => $parts) {
            if ($index === 0) {
                $hpVal = $amtLeft - $parts['amount'];
            } else {
                $hpVal = $premiumArray[$index - 1]['amount'] - $parts['amount'];
            }

            $bpFixed = $parts['fixed'];
            $percent = Floating::gt($parts['percent'], 0.) ? $parts['percent'] / 100 : 0.;
            $percent += $addPercent;
            $bpPercent = $hpVal * $percent;

            if ($parts['mode'] === Constants\BuyersPremium::MODE_GREATER) {// Greater
                $tmpPremium = Floating::gteq($bpFixed, $bpPercent) ? $bpFixed : $bpPercent;
            } else {// Sum
                $tmpPremium = $bpFixed + $bpPercent;
            }

            $totalPremium += $tmpPremium;
        } // end for

        return $totalPremium;
    }
}
