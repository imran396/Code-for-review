<?php
/**
 * SAM-8683: Adjustments and fixes for number formatting
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

use Sam\Core\Service\Dummy\DummyServiceTrait;
use Sam\Core\Transform\Number\Format\Apply\NumberFormatApplier;
use Sam\Core\Transform\Number\Format\Clear\NumberFormatRemover;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidateResult;
use Sam\Core\Transform\Number\Format\Validate\NumberFormatValidator;

/**
 * Class DummyNumberFormatter
 * @package Sam\Transform
 */
class DummyNumberFormatter implements NumberFormatterInterface
{
    use DummyServiceTrait;

    /**
     * {@inheritDoc}
     */
    public function construct(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): static
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function constructForInvoice(int $serviceAccountId): static
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function constructForSettlement(int $serviceAccountId): static
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function format($input, int $precision = 2, ?int $accountId = null): string
    {
        return NumberFormatApplier::new()->format($input, true);
    }

    /**
     * {@inheritDoc}
     */
    public function formatNto($input, int $precision = 2, ?int $accountId = null, bool $mustTrimZeroInDecimals = false): string
    {
        return NumberFormatApplier::new()->formatNto($input, true);
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
     * {@inheritDoc}
     */
    public function formatPercent($input, ?int $accountId = null): string
    {
        return NumberFormatApplier::new()->formatPercent($input, true);
    }

    /**
     * {@inheritDoc}
     */
    public function parse(string $numberFormatted, ?int $precision = 2, ?int $accountId = null): float
    {
        return NumberFormatRemover::new()->parse($numberFormatted, true, $precision);
    }

    /**
     * @inheritDoc
     */
    public function parseMoney(string $numberFormatted, ?int $accountId = null): float
    {
        return $this->parse($numberFormatted, 2, $accountId);
    }

    /**
     * {@inheritDoc}
     */
    public function parsePercent(string $numberFormatted, ?int $accountId = null): float
    {
        return NumberFormatRemover::new()->parsePercent($numberFormatted, true);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFormat(string $numberFormatted, ?int $accountId = null): string
    {
        return NumberFormatRemover::new()->removeFormat($numberFormatted, true);
    }

    /**
     * {@inheritDoc}
     */
    public function convertBidToFloat(string $numberFormatted, ?int $accountId = null): float|false
    {
        return (float)$this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function validateNumberFormat(string $number, ?int $accountId = null): NumberFormatValidateResult
    {
        return NumberFormatValidator::new()->validate($number, true);
    }
}
