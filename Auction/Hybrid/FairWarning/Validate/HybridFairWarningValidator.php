<?php
/**
 * SAM-6315: Unit tests for hybrid fair warning manager
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\FairWarning\Validate;


use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Hybrid\FairWarning\Parse\HybridFairWarningParserCreateTrait;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class HybridFairWarningValidator
 * @package Sam\Auction\Hybrid\FairWarning\Validate
 */
class HybridFairWarningValidator extends CustomizableClass
{
    use HybridFairWarningParserCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check format of fair warning text saved in setting_rtb.fair_warnings
     * @param string $warningTexts
     * @return bool
     */
    public function validateFormat(string $warningTexts): bool
    {
        $isSuccess = true;
        $warnings = $this->createHybridFairWarningParser()->parse($warningTexts);
        foreach ($warnings as $second => $text) {
            if (
                !NumberValidator::new()->isIntPositive($second)
                || trim($text) === ''
            ) {
                $isSuccess = false;
                break;
            }
        }
        return $isSuccess;
    }
}
