<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumPanel;

use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyersPremiumRangeNormalizer
 * @package Sam\BuyersPremium\Edit\Normalize
 */
class BuyersPremiumRangeNormalizer extends CustomizableClass
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
        $validationResult = $this->getNumberFormatter()->validateNumberFormat($value, $accountId);
        return $validationResult->isValidNumberWithoutThousandSeparator();
    }

    /**
     * Remove number format if it is possible
     *
     * @param string $value
     * @param int $accountId
     * @return string
     */
    public function normalizeFloat(string $value, int $accountId): string
    {
        if (!$this->isFloat($value, $accountId)) {
            return $value;
        }
        $value = $this->getNumberFormatter()->removeFormat($value, $accountId);
        return $value;
    }
}
