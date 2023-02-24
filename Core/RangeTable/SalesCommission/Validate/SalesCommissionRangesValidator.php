<?php
/**
 * Pure validation logic for Sales Commission input expected in multi-dimensional array structure [[start amount, percent], ...]
 *
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

use Sam\Core\RangeTable\SalesCommission\Validate\SalesCommissionRangesValidationResult as Result;
use Sam\Core\RangeTable\SalesCommission\Validate\SalesCommissionRowValidationResult as RowResult;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class SalesCommissionRangesValidator
 * @package Sam\Core\RangeTable\SalesCommission\Validate
 */
class SalesCommissionRangesValidator extends CustomizableClass
{
    public const OP_ERROR_MESSAGES = 'errorMessages';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate input of ["Start Amount", "Percent Value"] pairs.
     * @param array $rows
     * @param array $optionals
     * @return SalesCommissionRangesValidationResult
     */
    public function validate(array $rows, array $optionals = []): Result
    {
        $errorMessages = $optionals[self::OP_ERROR_MESSAGES] ?? null;
        $result = Result::new()->construct($rows, $errorMessages);
        foreach ($result->rowResults() as $rowResult) {
            $this->validateRow($rowResult, $result);
        }
        if ($result->hasError()) {
            log_debug('Sales Commission validation error' . composeSuffix($result->errorLogData()));
        }
        return $result;
    }

    protected function validateRow(RowResult $rowResult, Result $result): void
    {
        if (!$this->validateRowFormat($rowResult)) {
            return;
        }
        $this->validateStartAmount($rowResult, $result);
        $this->validatePercentValue($rowResult);
    }

    protected function validateRowFormat(RowResult $rowResult): bool
    {
        if (count($rowResult->row) !== 2) {
            $rowResult->addError(Result::ERR_ROW_FORMAT_INVALID);
            return false;
        }
        return true;
    }

    protected function validateStartAmount(RowResult $rowResult, Result $result): void
    {
        $startAmount = $rowResult->toFloatStartAmount();

        if ($rowResult->index === 0) {
            /**
             * There is only one validation for "Start Amount" of the 1st row - it should have zero value.
             */
            if (
                !is_float($startAmount)
                || Floating::neq($startAmount, 0.)
            ) {
                $rowResult->addError(Result::ERR_FIRST_START_AMOUNT_MUST_BE_ZERO);
            }
            return;
        }

        /**
         * Next validations are for all rows except the 1st one.
         */

        if (!is_float($startAmount)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_DECIMAL);
            return;
        }

        if (!NumberValidator::new()->isRealPositive($startAmount)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_POSITIVE);
            return;
        }

        if ($result->existStartAmount($rowResult)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_UNIQUE);
            return;
        }
    }

    protected function validatePercentValue(RowResult $rowResult): void
    {
        $percentValue = $rowResult->toFloatPercentValue();

        if (!is_float($percentValue)) {
            $rowResult->addError(Result::ERR_PERCENT_VALUE_MUST_BE_DECIMAL);
            return;
        }

        if (Floating::lt($percentValue, 0.)) {
            $rowResult->addError(Result::ERR_PERCENT_VALUE_MUST_BE_POSITIVE_OR_ZERO);
            return;
        }
    }
}
