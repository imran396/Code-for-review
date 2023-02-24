<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Normalize;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class contains methods for normalizing consignor commission fee range input data to be validated and persisted
 *
 * Class ConsignorCommissionRangeDtoNormalizer
 * @package Sam\Consignor\Commission\Edit
 */
class ConsignorCommissionFeeRangeNormalizer extends CustomizableClass implements ConsignorCommissionFeeRangeNormalizerInterface
{
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if input string is float
     *
     * @param string $value
     * @return bool
     */
    public function isFloat(string $value): bool
    {
        if ($value === '') {
            return false;
        }
        $value = $this->getNumberFormatter()->removeFormat($value);
        return is_numeric($value);
    }

    /**
     * Convert input string to float
     *
     * @param string $value
     * @return float
     */
    public function toFloat(string $value): float
    {
        if (!$this->isFloat($value)) {
            throw new \LogicException(
                'Value does not correspond float type'
                . composeSuffix(['value' => $value])
            );
        }
        $value = $this->getNumberFormatter()->removeFormat($value);
        return (float)$value;
    }

    /**
     * Convert range mode id or name to integer
     *
     * @param string|int $value
     * @return int
     */
    public function normalizeMode(string|int $value): int
    {
        if (!$value) {
            return 0;
        }
        if (ctype_digit($value)) {
            return (int)$value;
        }
        $normalizedValue = array_search($value, Constants\ConsignorCommissionFee::RANGE_MODE_NAMES, true);
        return (int)$normalizedValue;
    }
}
