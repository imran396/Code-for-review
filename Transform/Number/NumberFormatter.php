<?php
/**
 * SAM-4444: Move NumberFormatter logic
 * https://bidpath.atlassian.net/browse/SAM-4444
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           16 Sept, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;

use Sam\Application\Application;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Number\Format\Clear\NumberFormatRemover;
use Sam\Core\Transform\Number\Format\Apply\NumberFormatApplier;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidateResult;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidator;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class NumberFormatter
 * @package Sam\Transform
 */
class NumberFormatter extends CustomizableClass implements NumberFormatterInterface
{
    use SettingsManagerAwareTrait;

    /**
     * Enable output context, where we should consider KEEP_DECIMAL_INVOICE setting.
     * true - Should add decimal zeros for whole integer number, when "Constants\Setting::KEEP_DECIMAL_INVOICE" is enabled.
     */
    protected bool $shouldAddDecimalZerosForInteger = false;
    /**
     * Account for loading settings US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE
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
     * Construct service for invoice business domain.
     * @param int $serviceAccountId
     * @return $this
     */
    public function constructForInvoice(int $serviceAccountId): static
    {
        return $this->construct($serviceAccountId, true);
    }

    /**
     * Construct service for settlement business domain.
     * @param int $serviceAccountId
     * @return $this
     */
    public function constructForSettlement(int $serviceAccountId): static
    {
        return $this->construct($serviceAccountId, true);
    }

    /**
     * @param float|int|string|null $input
     * @param int $precision
     * @param int|null $accountId
     * @return string
     */
    public function format(float|int|string|null $input, int $precision = 2, ?int $accountId = null): string
    {
        $accountId = $this->detectAccountId($accountId);
        $addDecimalZerosForInteger = $this->shouldAddDecimalZerosForInteger($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatApplier::new()->format($input, $isUsNumberFormatting, $addDecimalZerosForInteger, $precision);
    }

    /**
     * No Thousand Operator
     * implementation on showing value amounts without cents if cents are zero
     *
     * @param float|int|string|null $input
     * @param int $precision
     * @param int|null $accountId
     * @param bool $mustTrimZeroInDecimals
     * @return string
     */
    public function formatNto(
        float|int|string|null $input,
        int $precision = 2,
        ?int $accountId = null,
        bool $mustTrimZeroInDecimals = false
    ): string {
        $accountId = $this->detectAccountId($accountId);
        $addDecimalZerosForInteger = $this->shouldAddDecimalZerosForInteger($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatApplier::new()->formatNto($input, $isUsNumberFormatting, $precision, $addDecimalZerosForInteger, $mustTrimZeroInDecimals);
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
     * @param float|int|string|null $input null leads to empty string
     * @param int|null $accountId
     * @return string
     */
    public function formatPercent(float|int|string|null $input, ?int $accountId = null): string
    {
        $accountId = $this->detectAccountId($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatApplier::new()->formatPercent((float)$input, $isUsNumberFormatting);
    }

    /**
     * @param string $numberFormatted
     * @param int|null $precision
     * @param int|null $accountId
     * @return float
     */
    public function parse(string $numberFormatted, ?int $precision = 2, ?int $accountId = null): float
    {
        $accountId = $this->detectAccountId($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatRemover::new()->parse($numberFormatted, $isUsNumberFormatting, $precision);
    }

    /**
     * @inheritDoc
     */
    public function parseMoney(string $numberFormatted, ?int $accountId = null): float
    {
        return $this->parse($numberFormatted, 2, $accountId);
    }

    /**
     * Remove formatting on percent expected input and make float number result.
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return float
     */
    public function parsePercent(string $numberFormatted, ?int $accountId = null): float
    {
        $accountId = $this->detectAccountId($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatRemover::new()->parsePercent($numberFormatted, $isUsNumberFormatting);
    }

    /**
     * Remove number formatting
     *
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return string $number
     */
    public function removeFormat(string $numberFormatted, ?int $accountId = null): string
    {
        $accountId = $this->detectAccountId($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        return NumberFormatRemover::new()->removeFormat($numberFormatted, $isUsNumberFormatting);
    }

    /**
     * Remove number formatting and force filter to float
     *
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return float|false $number
     */
    public function convertBidToFloat(string $numberFormatted, ?int $accountId = null): float|false
    {
        $accountId = $this->detectAccountId($accountId);

        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        //we check a general format and all appropriate symbols
        $formatPattern = $isUsNumberFormatting ? "/^[0-9,]*\.?\d?\d?$/ui" : "/^[0-9\.]*\,?\d?\d?$/ui";
        if (!preg_match($formatPattern, $numberFormatted)) {
            return false;
        }
        //check for correct symbol for fractional part. To decline cases like "300,00" for US formatting
        $formatPattern = $isUsNumberFormatting ? "/\,\d?\d?$/ui" : "/\.\d?\d?$/ui";
        if (preg_match($formatPattern, $numberFormatted)) {
            return false;
        }
        $numberParts = explode($isUsNumberFormatting ? "." : ",", $numberFormatted);
        if (count($numberParts) > 2) {
            return false;
        }

        $integerPart = (int)preg_replace("/\D+/u", '', $numberParts[0]);
        $fractionalPart = '';
        if (isset($numberParts[1])) {
            $fractionalPart = "." . preg_replace("/\D+/u", '', $numberParts[1]);
        }
        $number = (float)($integerPart . $fractionalPart);
        return $number;
    }

    /**
     * Check number format
     *
     * @param string $number
     * @param int|null $accountId
     * @return NumberFormatValidateResult
     */
    public function validateNumberFormat(string $number, ?int $accountId = null): NumberFormatValidateResult
    {
        $accountId = $this->detectAccountId($accountId);
        $isUsNumberFormatting = (bool)$this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $accountId);
        $result = NumberFormatValidator::new()->validate($number, $isUsNumberFormatting);
        return $result;
    }

    /**
     * Check if keep_in_decimal enable and return true on invoice and settlement pages, otherwise return false
     *
     * @param int|null $accountId
     * @return bool
     */
    protected function shouldAddDecimalZerosForInteger(?int $accountId): bool
    {
        $shouldAddDecimalZerosForInteger = $this->shouldAddDecimalZerosForInteger
            && $this->getSettingsManager()->get(Constants\Setting::KEEP_DECIMAL_INVOICE, $accountId);
        return $shouldAddDecimalZerosForInteger;
    }

    /**
     * Detect this service context account id.
     * @param int|null $accountId
     * @return int
     */
    protected function detectAccountId(?int $accountId = null): int
    {
        if ($accountId) {
            return $accountId;
        }

        if ($this->serviceAccountId) {
            return $this->serviceAccountId;
        }

        return Application::getInstance()->getSystemAccountId();
    }
}
