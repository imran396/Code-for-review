<?php
/**
 * SAM-4173: Consider number formatting at public side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidateResult;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidator;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Locale\Formatter\LocaleNumberFormatterAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class NextNumberFormatter
 * @package Sam\Transform\Number
 */
class NextNumberFormatter extends CustomizableClass implements NumberFormatterInterface
{
    use ConfigRepositoryAwareTrait;
    use LocaleNumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Enable output context, where we should consider KEEP_DECIMAL_INVOICE setting
     * true - Should add decimal zeros for whole integer number, when "Constants\Setting::KEEP_DECIMAL_INVOICE" is enabled.
     */
    protected bool $shouldAddDecimalZerosForInteger = false;
    /**
     * Account for loading system parameters, like US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE.
     */
    protected ?int $serviceAccountId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId - Account for loading system parameters, like US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE.
     * @param bool $shouldAddDecimalZerosForInteger - Enable output context, where we should consider KEEP_DECIMAL_INVOICE setting
     * @return $this
     */
    public function construct(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): static
    {
        $this->serviceAccountId = $serviceAccountId;
        $this->shouldAddDecimalZerosForInteger = $shouldAddDecimalZerosForInteger;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function constructForInvoice(int $serviceAccountId): static
    {
        return $this->construct($serviceAccountId, true);
    }

    /**
     * @inheritDoc
     */
    public function constructForSettlement(int $serviceAccountId): static
    {
        return $this->construct($serviceAccountId, true);
    }

    /**
     * @inheritDoc
     */
    public function format($input, int $precision = 2, ?int $accountId = null): string
    {
        $attributes = [
            \NumberFormatter::MAX_FRACTION_DIGITS => $precision,
        ];

        if ($this->shouldKeepFractionalPart($input, $precision, $accountId)) {
            $attributes[\NumberFormatter::FRACTION_DIGITS] = $precision;
        }
        return $this->getLocaleNumberFormatter()->formatDecimal(Cast::toFloat($input), $attributes, [], [], $this->detectLocale($accountId));
    }

    /**
     * @inheritDoc
     */
    public function formatNto($input, int $precision = 2, ?int $accountId = null, bool $mustTrimZeroInDecimals = false): string
    {
        $attributes = [
            \NumberFormatter::MAX_FRACTION_DIGITS => $precision,
            \NumberFormatter::GROUPING_USED => false,
        ];

        if (
            !$mustTrimZeroInDecimals
            && $this->shouldKeepFractionalPart($input, $precision, $accountId)
        ) {
            $attributes[\NumberFormatter::FRACTION_DIGITS] = $precision;
        }
        return $this->getLocaleNumberFormatter()->formatDecimal(Cast::toFloat($input), $attributes, [], [], $this->detectLocale($accountId));
    }

    /**
     * @inheritDoc
     */
    public function formatInteger(float|int|string|null $input, ?int $accountId = null): string
    {
        return $this->format($input, 0, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function formatIntegerNto(float|int|string|null $input, ?int $accountId = null): string
    {
        return $this->formatNto($input, 0, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function formatMoney(float|int|string|null $input, ?int $accountId = null): string
    {
        return $this->format($input, 2, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function formatMoneyNto(float|int|string|null $input, ?int $accountId = null, bool $mustTrimZeroInDecimals = false): string
    {
        return $this->formatNto($input, 2, $accountId, $mustTrimZeroInDecimals);
    }

    /**
     * @inheritDoc
     */
    public function formatMoneyDetail(float|int|string|null $input, ?int $accountId = null): string
    {
        return $this->format($input, 4, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function formatPercent($input, ?int $accountId = null): string
    {
        $attributes = [
            \NumberFormatter::MAX_FRACTION_DIGITS => 4,
            \NumberFormatter::GROUPING_USED => false,
        ];
        return $this->getLocaleNumberFormatter()->formatDecimal(Cast::toFloat($input), $attributes, [], [], $this->detectLocale($accountId));
    }

    /**
     * @inheritDoc
     */
    public function parse(string $numberFormatted, ?int $precision = 2, ?int $accountId = null): float
    {
        $numberFloat = (float)$this->removeFormat($numberFormatted, $accountId);
        if ($precision !== null) {
            $numberFloat = round($numberFloat, $precision);
        }
        return $numberFloat;
    }

    /**
     * @inheritDoc
     */
    public function parseMoney(string $numberFormatted, ?int $accountId = null): float
    {
        return $this->parse($numberFormatted, 2, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function parsePercent(string $numberFormatted, ?int $accountId = null): float
    {
        return $this->parse($numberFormatted, 4, $accountId);
    }

    /**
     * @inheritDoc
     */
    public function removeFormat(string $numberFormatted, ?int $accountId = null): string
    {
        return (string)$this->getLocaleNumberFormatter()->parseDecimal($numberFormatted, [], [], [], $this->detectLocale($accountId));
    }

    /**
     * @inheritDoc
     */
    public function convertBidToFloat(string $numberFormatted, ?int $accountId = null): float|false
    {
        $locale = $this->detectLocale($accountId);
        $formatter = $this->getLocaleNumberFormatter();
        $groupingSymbol = $formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, \NumberFormatter::DECIMAL, $locale);
        /** @var non-empty-string $decimalSymbol */
        $decimalSymbol = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, \NumberFormatter::DECIMAL, $locale);

        //we check a general format and all appropriate symbols
        $formatPattern = "/^[\d{$groupingSymbol}]*\{$decimalSymbol}?\d?\d?$/ui";
        if (!preg_match($formatPattern, $numberFormatted)) {
            return false;
        }

        //check for correct symbol for fractional part. To decline cases like "300,00" for US formatting
        $formatPattern = "/\{$groupingSymbol}\d?\d?$/ui";
        if (preg_match($formatPattern, $numberFormatted)) {
            return false;
        }

        $numberParts = explode($decimalSymbol, $numberFormatted);
        $countParts = count($numberParts);
        if ($countParts > 2) {
            return false;
        }

        $number = (float)$this->removeFormat($numberFormatted, $accountId);
        return $number;
    }

    /**
     * @inheritDoc
     */
    public function validateNumberFormat(string $number, ?int $accountId = null): NumberFormatValidateResult
    {
        $locale = $this->detectLocale($accountId);
        $isUsNumberFormatting = (strtolower($locale) === 'en_us');
        $result = NumberFormatValidator::new()->validate($number, $isUsNumberFormatting);
        return $result;
    }

    /**
     * Keep fractional digits if the input is not an integer or "AddDecimalZerosForInteger" is enabled
     *
     * @param $input
     * @param int $precision
     * @param int|null $accountId
     * @return bool
     */
    protected function shouldKeepFractionalPart($input, int $precision, ?int $accountId): bool
    {
        $isInteger = fmod(round((float)$input, $precision), 1) === 0.;
        return !$isInteger || $this->shouldAddDecimalZerosForInteger($accountId);
    }

    /**
     * @param int|null $accountId
     * @return string
     */
    protected function detectLocale(?int $accountId): string
    {
        $accountId ??= $this->serviceAccountId;
        $locale = $accountId
            ? $this->getSettingsManager()->get(Constants\Setting::LOCALE, $accountId)
            : $this->getSettingsManager()->getForSystem(Constants\Setting::LOCALE);

        if (!$locale) {
            $locale = $this->cfg()->get('core->app->locale->default');
        }
        return $locale;
    }

    /**
     * Check if keep_in_decimal enable and return true on invoice and settlement pages, otherwise return false
     *
     * @param int|null $accountId
     * @return bool
     */
    protected function shouldAddDecimalZerosForInteger(?int $accountId): bool
    {
        $accountId ??= $this->serviceAccountId;
        $shouldAddDecimalZerosForInteger = $this->shouldAddDecimalZerosForInteger
            && $this->getSettingsManager()->get(Constants\Setting::KEEP_DECIMAL_INVOICE, $accountId);
        return $shouldAddDecimalZerosForInteger;
    }
}
