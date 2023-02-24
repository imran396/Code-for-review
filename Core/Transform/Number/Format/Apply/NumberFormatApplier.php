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

namespace Sam\Core\Transform\Number\Format\Apply;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Number\Format\Common\NumberFormatSeparatorResolver;

/**
 * Class PureNumberFormatter
 * @package Sam\Core\Transform\Number\Format
 */
class NumberFormatApplier extends CustomizableClass
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
     * Format number with thousand separators according to "US Number Formatting" setting and define precision for decimal part of number.
     * Precision acts as custom length of fractional part, when decimal number is formatted. E.g. "1.2" produces "1.20".
     *
     * $addDecimalZerosForInteger argument defines necessity to add zeros in fractional part of whole integer number.
     * It doesn't have sense in case of decimal number, because we always add zeros in fractional part of decimal number according expected precision.
     *
     * @param float|int|string|null $input
     * @param bool $isUsNumberFormatting defined by Constants\Setting::US_NUMBER_FORMATTING
     * @param int $precision i.e. custom length of decimals.
     * @param bool $addDecimalZerosForInteger When true, then on "1234567" integer input results to "1,234,567.00" output.
     * @return string
     */
    public function format(
        float|int|string|null $input,
        bool $isUsNumberFormatting,
        bool $addDecimalZerosForInteger = false,
        int $precision = 2
    ): string {
        [$thousandSeparator, $decimalSeparator] = NumberFormatSeparatorResolver::new()
            ->thousandAndDecimalSeparators($isUsNumberFormatting);
        $input = round((float)$input, $precision);
        $fractionPart = $input - (int)$input;
        if (
            !$addDecimalZerosForInteger
            && Floating::eq($fractionPart, 0)
        ) {
            $precision = 0;
            $decimalSeparator = '';
            $input = (int)$input;
        }

        $resultNumber = number_format($input, $precision, $decimalSeparator, $thousandSeparator);
        return $resultNumber;
    }

    /**
     * Format number without thousand separators according to "US Number Formatting" setting and definite precision for decimal part of number.
     * Precision acts as custom length of fractional part, when decimal number is formatted. E.g. 1.2 input produces "1.20" for default arguments.
     *
     * The option "Must trim zeros in fractional part of number" ($mustTrimDecimalZeros) has higher precedence,
     * than "Add decimal zeros in fractional part of whole integer number" ($addDecimalZerosForInteger).
     * Enabled value of $addDecimalZerosForInteger argument has sense only when we pass Whole Integer number as input and $mustTrimDecimalZeros is disabled.
     *
     * @param float|int|string|null $input
     * @param bool $isUsNumberFormatting defined by Constants\Setting::US_NUMBER_FORMATTING
     * @param int $precision i.e. custom length of decimals.
     * @param bool $addDecimalZerosForInteger When true, then on "123" integer input make "123.00" output.
     * @param bool $mustTrimDecimalZeros When true, then on "123.40" input make "123.4" output.
     * @return string
     */
    public function formatNto(
        float|int|string|null $input,
        bool $isUsNumberFormatting,
        int $precision = 2,
        bool $addDecimalZerosForInteger = false,
        bool $mustTrimDecimalZeros = false
    ): string {
        $thousandSeparator = ''; // Avoid thousand separator
        $decimalSeparator = NumberFormatSeparatorResolver::new()
            ->decimalSeparator($isUsNumberFormatting);

        $input = round((float)$input, $precision);
        $fractionPart = $input - (int)$input;

        $isProcessWithDecimals = $addDecimalZerosForInteger
            || Floating::neq($fractionPart, 0);

        if (!$isProcessWithDecimals) {
            // format value without decimals
            $result = number_format((int)$input, 0, '', $thousandSeparator);
            return $result;
        }

        // format value with decimals
        $result = number_format($input, $precision, $decimalSeparator, $thousandSeparator);
        if ($mustTrimDecimalZeros) {
            // right-trim trailing zeros
            $result = rtrim($result, "0");
            // right-trim possibly redundant decimal separator
            $result = rtrim($result, $decimalSeparator);
        }
        return $result;
    }

    /**
     * @param float|int|string|null $input null leads to empty string
     * @param bool $isUsNumberFormatting
     * @return string
     */
    public function formatPercent(float|int|string|null $input, bool $isUsNumberFormatting): string
    {
        if ($input === null) {
            return '';
        }
        return $this->formatNto($input, $isUsNumberFormatting, 4, false, true);
    }
}
