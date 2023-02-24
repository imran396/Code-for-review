<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
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

namespace Sam\Tax\StackedTax\Definition\Calculate\Tiered;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use TaxDefinitionRange;

/**
 * Class TieredBpCalculator
 * @package Sam\Tax\StackedTax\Calculate\Tiered
 */
class TaxDefinitionTieredBpPureCalculator extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param float $amount
     * @param TaxDefinitionRange[] $taxDefinitionRanges
     * @return float
     * #[Pure]
     */
    public function calculate(float $amount, array $taxDefinitionRanges): float
    {
        $tdrArray = [];
        $taxAmount = 0.;

        // loop and concatenate all percents in a string variable
        if ($taxDefinitionRanges) {
            foreach ($taxDefinitionRanges as $tdr) {
                $tdrArray[] = [
                    'amount' => $tdr->Amount,
                    'fixed' => $tdr->Fixed,
                    'percent' => $tdr->Percent,
                    'mode' => $tdr->Mode,
                ];
            }
        }

        foreach ($tdrArray as $index => $parts) {
            if ($index === 0) {
                $rangeAmountDiff = $amount - $parts['amount'];
            } else {
                $rangeAmountDiff = $tdrArray[$index - 1]['amount'] - $parts['amount'];
            }

            $fixed = $parts['fixed'];
            $percent = Floating::gt($parts['percent'], 0.) ? $parts['percent'] / 100 : 0.;
            $rangeAmountDiffPercentage = $rangeAmountDiff * $percent;

            if ($parts['mode'] === Constants\StackedTax::RM_GREATER) {
                $addValue = Floating::gteq($fixed, $rangeAmountDiffPercentage)
                    ? $fixed
                    : $rangeAmountDiffPercentage;
            } else {
                $addValue = $fixed + $rangeAmountDiffPercentage;
            }

            $taxAmount += $addValue;
        }

        return $taxAmount;
    }
}
