<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\Formatter;

use NumberFormatter as IntlNumberFormatter;
use Sam\Core\Service\CustomizableClass;
use Sam\Locale\LocaleAwareTrait;

/**
 * This class formats different styles of numbers in localized format
 *
 * Class LocaleNumberFormatter
 * @package Sam\Locale\Formatter
 */
class LocaleNumberFormatter extends CustomizableClass
{
    use LocaleAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Format a number
     * @param int|float|null $value The value to format. Can be integer or float, other values will be converted to a numeric value.
     * @param int $style Style of the formatting, one of the format style constantsConstant of \NumberFormatter
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @param string|null $locale Locale of formatting. NULL means the most relevance locale or default if it is impossible to determine.
     * @return string
     */
    public function format(
        int|float|null $value,
        int $style,
        array $attributes = [],
        array $textAttributes = [],
        array $symbols = [],
        ?string $locale = null
    ): string {
        if ((string)$value === '') {
            return '';
        }

        if (!$locale) {
            $locale = $this->getLocale();
        }
        return $this->getFormatter($locale, $style, $attributes, $textAttributes, $symbols)
            ->format($value);
    }

    /**
     * Format a currency value
     * @param float|null $value The numeric currency value.
     * @param string $currencyCode Currency code
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @param string|null $locale Locale of formatting. NULL means the most relevance locale or default if it is impossible to determine.
     *
     * @return string
     */
    public function formatCurrency(
        ?float $value,
        string $currencyCode = '',
        array $attributes = [],
        array $textAttributes = [],
        array $symbols = [],
        ?string $locale = null
    ): string {
        if ($value === null) {
            return '';
        }

        if (
            !$currencyCode
            && !array_key_exists(IntlNumberFormatter::CURRENCY_SYMBOL, $symbols)
        ) {
            $symbols[IntlNumberFormatter::CURRENCY_SYMBOL] = '';
        }

        $locale = $locale ?? $this->getLocale();

        $formatter = $this->getFormatter(
            $locale . '@currency=' . $currencyCode,
            IntlNumberFormatter::CURRENCY,
            $attributes,
            $textAttributes,
            $symbols
        );

        return $formatter->formatCurrency($value, $currencyCode);
    }

    /**
     * Format decimal
     * @param int|float|null $value The value to format
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @param string|null $locale Locale of formatting. NULL means the most relevance locale or default if it is impossible to determine.
     * @return string
     */
    public function formatDecimal(
        int|float|null $value,
        array $attributes = [],
        array $textAttributes = [],
        array $symbols = [],
        ?string $locale = null
    ): string {
        return $this->format($value, IntlNumberFormatter::DECIMAL, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Parse a number
     * @param string $value
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @param string|null $locale Locale of formatting. NULL means the most relevance locale or default if it is impossible to determine.
     * @return int|float|null NULL on error
     */
    public function parseDecimal(string $value, array $attributes = [], array $textAttributes = [], array $symbols = [], string $locale = null): int|float|null
    {
        $locale = $locale ?? $this->getLocale();
        $formatter = $this->getFormatter($locale, IntlNumberFormatter::DECIMAL, $attributes, $textAttributes, $symbols);
        $decimalSeparator = $symbols[IntlNumberFormatter::DECIMAL_SEPARATOR_SYMBOL]
            ?? $this->getSymbol(IntlNumberFormatter::DECIMAL_SEPARATOR_SYMBOL, IntlNumberFormatter::DECIMAL, $locale);
        if (str_contains($value, $decimalSeparator)) {
            return $formatter->parse($value);
        }
        return $formatter->parse($value, IntlNumberFormatter::TYPE_INT32) ?: null;
    }

    /**
     * Format percent
     * @param float|null $value The value to format
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @param string|null $locale Locale of formatting. NULL means the most relevance locale or default if it is impossible to determine.
     * @return string
     */
    public function formatPercent(
        ?float $value,
        array $attributes = [],
        array $textAttributes = [],
        array $symbols = [],
        ?string $locale = null
    ): string {
        if (!array_key_exists(IntlNumberFormatter::MAX_FRACTION_DIGITS, $attributes)) {
            $attributes[IntlNumberFormatter::MAX_FRACTION_DIGITS] = 4;
        }
        return $this->format($value, IntlNumberFormatter::PERCENT, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Gets value of symbol associated with \NumberFormatter
     *
     * Supported symbol constants of \NumberFormatter are:
     *  DECIMAL_SEPARATOR_SYMBOL
     *  GROUPING_SEPARATOR_SYMBOL
     *  PATTERN_SEPARATOR_SYMBOL
     *  PERCENT_SYMBOL
     *  ZERO_DIGIT_SYMBOL
     *  DIGIT_SYMBOL
     *  MINUS_SIGN_SYMBOL
     *  PLUS_SIGN_SYMBOL
     *  CURRENCY_SYMBOL
     *  INTL_CURRENCY_SYMBOL
     *  MONETARY_SEPARATOR_SYMBOL
     *  EXPONENTIAL_SYMBOL
     *  PERMILL_SYMBOL
     *  PAD_ESCAPE_SYMBOL
     *  INFINITY_SYMBOL
     *  NAN_SYMBOL
     *  SIGNIFICANT_DIGIT_SYMBOL
     *  MONETARY_GROUPING_SEPARATOR_SYMBOL
     *
     *
     * @param int $symbol Format symbol constant of \NumberFormatter or it's string name
     * @param int $style Constant of \NumberFormatter (DECIMAL, CURRENCY, PERCENT, etc)
     * @param string|null $locale
     * @return string|null
     */
    public function getSymbol(int $symbol, int $style, string $locale = null): ?string
    {
        $locale = $locale ?? $this->getLocale();
        return $this->getFormatter($locale, $style)->getSymbol($symbol) ?: null;
    }

    /**
     * Creates instance of NumberFormatter class of intl extension
     * @param string $locale Locale of formatting
     * @param int $style Style of the formatting, one of the format style constantsConstant of \NumberFormatter
     * @param array $attributes Set of attributes of \NumberFormatter
     * @param array $textAttributes Set of text attributes of \NumberFormatter
     * @param array $symbols Set of symbols of \NumberFormatter
     * @return IntlNumberFormatter
     */
    protected function getFormatter(string $locale, int $style, array $attributes = [], array $textAttributes = [], array $symbols = []): IntlNumberFormatter
    {
        $formatter = new IntlNumberFormatter($locale, $style);
        foreach ($attributes as $attribute => $value) {
            $formatter->setAttribute($attribute, $value);
        }
        foreach ($textAttributes as $attribute => $value) {
            $formatter->setTextAttribute($attribute, $value);
        }
        foreach ($symbols as $symbol => $value) {
            $formatter->setSymbol($symbol, $value);
        }
        return $formatter;
    }
}
