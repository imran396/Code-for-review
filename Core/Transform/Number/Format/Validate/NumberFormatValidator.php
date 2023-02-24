<?php
/**
 * SAM-8683: Adjustments and fixes for number formatting
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Number\Format\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Number\Format\Common\NumberFormatSeparatorResolver;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidateResult as Result;

/**
 * Class NumberFormatValidator
 * @package Sam\Core\Transform\Number\Format\Validate
 */
class NumberFormatValidator extends CustomizableClass
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
     * Check number format
     *
     * @param string $number
     * @param bool $isUsNumberFormatting
     * @return Result
     */
    public function validate(string $number, bool $isUsNumberFormatting): Result
    {
        $number = trim($number);
        $result = NumberFormatValidateResult::new()->construct();
        [$thousandSeparator, $decimalSeparator] = NumberFormatSeparatorResolver::new()
            ->thousandAndDecimalSeparators($isUsNumberFormatting);

        if (
            isset($number[0])
            && $number[0] === '-'
        ) {
            $number = substr($number, 1);
        }

        $countParts = substr_count($number, $decimalSeparator);
        if ($countParts > 1) {
            return $result->addError(Result::ERR_INVALID);
        }

        if ($countParts === 1) {
            [$integerPart, $fractionalPart] = explode($decimalSeparator, $number);
            if (!NumberValidator::new()->isIntPositiveOrZero($fractionalPart)) {
                return $result->addError(Result::ERR_INVALID);
            }
        } else { // $countParts === 0
            $integerPart = $number;
        }

        $hasThousandSeparation = substr_count($integerPart, $thousandSeparator);
        if ($hasThousandSeparation) {
            return $this->validateIntegerPartWithThousandSeparation($integerPart, $thousandSeparator, $result);
        }

        return $this->validateIntegerPartWithoutThousandSeparation($integerPart, $result);
    }

    /**
     * @param string $integerPart
     * @param non-empty-string $thousandSeparator
     * @param NumberFormatValidateResult $result
     * @return NumberFormatValidateResult
     */
    protected function validateIntegerPartWithThousandSeparation(string $integerPart, string $thousandSeparator, Result $result): Result
    {
        $thousands = explode($thousandSeparator, $integerPart);
        for ($i = 0, $iMax = count($thousands); $i < $iMax; $i++) {
            $length = strlen($thousands[$i]);
            $isValidNumber = NumberValidator::new()->isIntPositiveOrZero($thousands[$i]);
            if ((
                    $i === 0
                    && $length > 3
                )
                || (
                    $i !== 0
                    && $length !== 3
                )
                || !$isValidNumber
            ) {
                return $result->addError(Result::ERR_INVALID);
            }
        }
        return $result->addSuccess(Result::OK_VALID_WITH_THOUSAND_SEPARATOR);
    }

    /**
     * @param string $integerPart
     * @param NumberFormatValidateResult $result
     * @return NumberFormatValidateResult
     */
    public function validateIntegerPartWithoutThousandSeparation(string $integerPart, Result $result): Result
    {
        if (!NumberValidator::new()->isIntPositiveOrZero($integerPart)) {
            return $result->addError(Result::ERR_INVALID);
        }
        return $result->addSuccess(Result::OK_VALID_WITHOUT_THOUSAND_SEPARATOR);
    }
}
