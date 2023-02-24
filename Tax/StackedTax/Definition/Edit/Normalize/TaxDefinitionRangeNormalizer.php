<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Normalize;

use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class TaxDefinitionRangeNormalizer
 * @package Sam\Tax\StackedTax\Definition\Edit\Normalize
 */
class TaxDefinitionRangeNormalizer extends CustomizableClass
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
     * @param int $accountId
     * @return bool
     */
    public function isFloat(string $value, int $accountId): bool
    {
        if ($value === '') {
            return false;
        }
        $value = $this->getNumberFormatter()->removeFormat($value, $accountId);
        return is_numeric($value);
    }

    /**
     * Convert input string to float
     *
     * @param string $value
     * @param int $accountId
     * @return float
     */
    public function toFloat(string $value, int $accountId): float
    {
        if (!$this->isFloat($value, $accountId)) {
            throw new \LogicException(
                'Value does not correspond float type'
                . composeSuffix(['value' => $value])
            );
        }
        $value = $this->getNumberFormatter()->removeFormat($value, $accountId);
        return (float)$value;
    }
}
