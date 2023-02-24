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

namespace Sam\Tax\StackedTax\Definition\Calculate\Sliding;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use TaxDefinitionRange;

/**
 * Class SlidingBpPureCalculator
 * @package Sam\Tax\StackedTax\Calculate\Tiered
 */
class TaxDefinitionSlidingPureCalculator extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param float $amount
     * @param TaxDefinitionRange $tdr
     * @return float
     * #[Pure]
     */
    public function calculate(float $amount, TaxDefinitionRange $tdr): float
    {
        $fixed = $tdr->Fixed;
        $percent = Floating::gt($tdr->Percent, 0.) ? $tdr->Percent / 100 : 0.;
        $amountPercentage = $amount * $percent;
        if ($tdr->Mode === Constants\StackedTax::RM_GREATER) {
            $taxAmount = Floating::gteq($fixed, $amountPercentage) ? $fixed : $amountPercentage;
        } else {
            $taxAmount = $fixed + $amountPercentage;
        }
        return $taxAmount;
    }
}
