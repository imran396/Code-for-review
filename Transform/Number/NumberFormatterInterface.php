<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;

use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidateResult;

interface NumberFormatterInterface
{
    /**
     * @param int $serviceAccountId - Account for loading system parameters, like US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE.
     * @param bool $shouldAddDecimalZerosForInteger
     * @return self
     */
    public function construct(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): self;

    /**
     * Construct service for invoice business domain - it considers KEEP_DECIMAL_INVOICE setting.
     * @param int $serviceAccountId
     * @return $this
     */
    public function constructForInvoice(int $serviceAccountId): self;

    /**
     * Construct service for settlement business domain - it considers KEEP_DECIMAL_INVOICE setting.
     * @param int $serviceAccountId
     * @return $this
     */
    public function constructForSettlement(int $serviceAccountId): self;

    /**
     * @param float|int|string|null $input
     * @param int $precision
     * @param int|null $accountId
     * @return string
     */
    public function format(float|int|string|null $input, int $precision = 2, ?int $accountId = null): string;

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
    ): string;

    /**
     * Format integer value, i.e. without decimals, but with thousands separators.
     * @param float|int|string|null $input
     * @param int|null $accountId
     * @return string
     */
    public function formatInteger(float|int|string|null $input, ?int $accountId = null): string;

    /**
     * Format integer value, i.e. without decimals and without thousands separators.
     * @param float|int|string|null $input
     * @param int|null $accountId
     * @return string
     */
    public function formatIntegerNto(float|int|string|null $input, ?int $accountId = null): string;

    /**
     * Format decimal value according to monetary precision with a thousand separator.
     * @param float|int|string|null $input
     * @param int|null $accountId
     * @return string
     */
    public function formatMoney(float|int|string|null $input, ?int $accountId = null): string;

    /**
     * Format decimal value according to monetary precision without a thousand separator.
     * @param float|int|string|null $input
     * @param int|null $accountId
     * @param bool $mustTrimZeroInDecimals
     * @return string
     */
    public function formatMoneyNto(float|int|string|null $input, ?int $accountId = null, bool $mustTrimZeroInDecimals = false): string;

    /**
     * Format decimal money value with a thousand separator, when we need to represent detailed value with higher precision.
     * @param float|int|string|null $input
     * @param int|null $accountId
     * @return string
     */
    public function formatMoneyDetail(float|int|string|null $input, ?int $accountId = null): string;

    /**
     * @param float|int|string|null $input null leads to empty string
     * @param int|null $accountId
     * @return string
     */
    public function formatPercent(float|int|string|null $input, ?int $accountId = null): string;

    /**
     * Remove formatting from input and make float number result.
     * @param string $numberFormatted
     * @param int|null $precision
     * @param int|null $accountId
     * @return float
     */
    public function parse(string $numberFormatted, ?int $precision = 2, ?int $accountId = null): float;

    /**
     * Remove formatting from input and make float number result with monetary amount precision.
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return float
     */
    public function parseMoney(string $numberFormatted, ?int $accountId = null): float;

    /**
     * Remove formatting on percent expected input and make float number result.
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return float
     */
    public function parsePercent(string $numberFormatted, ?int $accountId = null): float;

    /**
     * Remove number formatting
     *
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return string $number
     */
    public function removeFormat(string $numberFormatted, ?int $accountId = null): string;

    /**
     * Remove number formatting and force filter to float
     *
     * @param string $numberFormatted
     * @param int|null $accountId
     * @return float|false $number
     */
    public function convertBidToFloat(string $numberFormatted, ?int $accountId = null): float|false;

    /**
     * Check number format and produce result, that defines one of the statuses:
     * - invalid formatting,
     * - valid with thousand separator,
     * - valid without thousand separator.
     * @param string $number
     * @param int|null $accountId
     * @return NumberFormatValidateResult
     */
    public function validateNumberFormat(string $number, ?int $accountId = null): NumberFormatValidateResult;

}
