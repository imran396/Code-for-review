<?php
/**
 * Decimal-type custom data converter from entity's value to php native
 *
 * SAM-7658: Decimal custom field: Last digit of entered value is reduced by .01 after saving
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\CustomField\Decimal;

use Sam\Core\Service\CustomizableClass;

/**
 * Class DecimalCustomFieldCalculator
 * @package Sam\CustomField\Base\DecimalType
 */
class CustomDataDecimalPureCalculator extends CustomizableClass
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
     * Convert php native value to value stored in Numeric db field of custom data
     * @param float $realValue
     * @param int $precision
     * @return int
     */
    public function calcModelValue(float $realValue, int $precision): int
    {
        $multiplier = $precision > 0 ? 10 ** $precision : 1;
        $modelValue = (int)round($realValue * $multiplier);
        return $modelValue;
    }

    /**
     * Convert value from custom field's data to php native value
     * @param int $modelValue
     * @param int $precision
     * @return float
     */
    public function calcRealValue(int $modelValue, int $precision): float
    {
        $denominator = $precision > 0 ? 10 ** $precision : 1;
        $realValue = $modelValue / $denominator;
        return $realValue;
    }
}
