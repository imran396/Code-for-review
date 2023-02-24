<?php
/**
 * SAM-8106: Improper validations displayed for invalid inputs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\RangeTable\SalesCommission\Validate;

use Sam\Core\RangeTable\SalesCommission\Validate\SalesCommissionRowValidationResult as RowResult;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SalesCommissionRangesValidationResult
 * @package Sam\Core\RangeTable\SalesCommission\Validate
 */
class SalesCommissionRangesValidationResult extends CustomizableClass
{
    public const ERR_ROW_FORMAT_INVALID = 1;
    public const ERR_START_AMOUNT_MUST_BE_DECIMAL = 2;
    public const ERR_FIRST_START_AMOUNT_MUST_BE_ZERO = 3;
    public const ERR_START_AMOUNT_MUST_BE_POSITIVE = 4;
    public const ERR_START_AMOUNT_MUST_BE_UNIQUE = 5;
    public const ERR_PERCENT_VALUE_MUST_BE_DECIMAL = 6;
    public const ERR_PERCENT_VALUE_MUST_BE_POSITIVE_OR_ZERO = 7;

    public array $errorMessages = [
        self::ERR_ROW_FORMAT_INVALID => 'Expected row format is array of two float numbers',
        self::ERR_START_AMOUNT_MUST_BE_DECIMAL => 'Start amount must be decimal number',
        self::ERR_FIRST_START_AMOUNT_MUST_BE_ZERO => 'First range start amount should be 0',
        self::ERR_START_AMOUNT_MUST_BE_POSITIVE => 'Range start amount must be positive number',
        self::ERR_START_AMOUNT_MUST_BE_UNIQUE => 'Range start amount must be unique',
        self::ERR_PERCENT_VALUE_MUST_BE_POSITIVE_OR_ZERO => 'Percent value must be positive or zero',
        self::ERR_PERCENT_VALUE_MUST_BE_DECIMAL => 'Percent value must be decimal number',
    ];

    public const START_AMOUNT_ERROR_CODES = [
        self::ERR_START_AMOUNT_MUST_BE_DECIMAL,
        self::ERR_FIRST_START_AMOUNT_MUST_BE_ZERO,
        self::ERR_START_AMOUNT_MUST_BE_POSITIVE,
        self::ERR_START_AMOUNT_MUST_BE_UNIQUE,
    ];

    public const PERCENT_VALUE_ERROR_CODES = [
        self::ERR_PERCENT_VALUE_MUST_BE_DECIMAL,
        self::ERR_PERCENT_VALUE_MUST_BE_POSITIVE_OR_ZERO,
    ];

    public const FORMAT_ERROR_CODES = [
        self::ERR_ROW_FORMAT_INVALID,
    ];

    /** @var RowResult[] */
    protected array $rowResults = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $rows multi-dimensional array of ["Start Amount", "Percent Value"] input pairs.
     * @param array|null $errorMessages
     * @return $this
     */
    public function construct(array $rows, array $errorMessages = null): static
    {
        foreach ($rows as $index => $row) {
            $this->addRowResult($index, (array)$row);
        }
        $this->errorMessages = $errorMessages ?? $this->errorMessages;
        return $this;
    }

    public function findRowResultByIndex(int $index): ?RowResult
    {
        return $this->rowResults[$index] ?? null;
    }

    /**
     * Check if start amount from this row-result is not unique in collection
     * @param SalesCommissionRowValidationResult $checkingRowResult
     * @return bool
     */
    public function existStartAmount(RowResult $checkingRowResult): bool
    {
        foreach ($this->rowResults as $rowResult) {
            if ($rowResult->isEqualByIndex($checkingRowResult)) {
                continue; // skip himself
            }
            if ($rowResult->isEqualByStartAmount($checkingRowResult)) {
                return true;
            }
        }
        return false;
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasError(): bool
    {
        foreach ($this->rowResults as $rowResult) {
            if ($rowResult->hasError()) {
                return true;
            }
        }
        return false;
    }

    public function errorCodes(): array
    {
        $codes = [];
        foreach ($this->rowResults as $rowResult) {
            $codes[$rowResult->index] = $rowResult->errorCodes();
        }
        $codes = array_filter($codes);
        return $codes;
    }

    public function errorLogData(): array
    {
        $logData = [];
        foreach ($this->errorRowResults() as $rowResult) {
            $logData[] = $rowResult->logData();
        }
        return $logData;
    }

    /**
     * @return SalesCommissionRowValidationResult[]
     */
    public function errorRowResults(): array
    {
        $rowResults = [];
        foreach ($this->rowResults as $rowResult) {
            if ($rowResult->hasError()) {
                $rowResults[] = $rowResult;
            }
        }
        return $rowResults;
    }

    public function rowResults(): array
    {
        return $this->rowResults;
    }

    protected function addRowResult(int $index, array $row): RowResult
    {
        $rowResult = RowResult::new()->construct($index, $row, $this->errorMessages);
        $this->rowResults[] = $rowResult;
        return $rowResult;
    }
}
