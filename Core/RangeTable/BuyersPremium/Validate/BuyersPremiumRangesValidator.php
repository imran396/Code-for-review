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

namespace Sam\Core\RangeTable\BuyersPremium\Validate;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult as Result;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRowValidationResult as RowResult;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class BuyersPremiumRangesValidator
 * @package Sam\Core\RangeTable\BuyersPremium\Validate
 */
class BuyersPremiumRangesValidator extends CustomizableClass
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
     * @return Result
     */
    public function validate(array $rows, array $optionals = []): Result
    {
        $errorMessages = $optionals[self::OP_ERROR_MESSAGES] ?? null;
        $result = Result::new()->construct($rows, $errorMessages);
        foreach ($result->rowResults() as $rowResult) {
            $this->validateRow($rowResult, $result);
        }
        if ($result->hasError()) {
            log_debug('Buyers Premium validation error' . composeSuffix($result->errorLogData()));
        }
        return $result;
    }

    protected function validateRow(RowResult $rowResult, Result $result): void
    {
        if (!$this->validateRowFormat($rowResult)) {
            return;
        }
        $this->validateStartAmount($rowResult, $result);
        $this->validateFixedValue($rowResult);
        $this->validatePercentValue($rowResult);
        $this->validateModeValue($rowResult);
    }

    protected function validateRowFormat(RowResult $rowResult): bool
    {
        if (count($rowResult->row) !== 4) {
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

        if ($rowResult->toStringStartAmount() === '') {
            $rowResult->addError(Result::ERR_START_AMOUNT_REQUIRED);
            return;
        }

        if (!is_float($startAmount)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_DECIMAL);
            return;
        }

        if ($result->existStartAmount($rowResult)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_UNIQUE);
            return;
        }

        if (!NumberValidator::new()->isRealPositive($startAmount)) {
            $rowResult->addError(Result::ERR_START_AMOUNT_MUST_BE_POSITIVE);
            return;
        }
    }

    protected function validateFixedValue(RowResult $rowResult): void
    {
        $fixedValue = $rowResult->toFloatFixedValue();

        if ($rowResult->toStringFixedValue() === '') {
            $rowResult->addError(Result::ERR_FIXED_VALUE_REQUIRED);
            return;
        }

        if (!NumberValidator::new()->isReal($fixedValue)) {
            $rowResult->addError(Result::ERR_FIXED_VALUE_MUST_BE_DECIMAL);
            return;
        }

        if (Floating::lt($fixedValue, 0)) {
            $rowResult->addError(Result::ERR_FIXED_VALUE_MUST_BE_POSITIVE);
            return;
        }
    }

    protected function validatePercentValue(RowResult $rowResult): void
    {
        $percentValue = $rowResult->toFloatPercentValue();

        if ($rowResult->toStringPercentValue() === '') {
            $rowResult->addError(Result::ERR_PERCENT_VALUE_REQUIRED);
            return;
        }

        if (!NumberValidator::new()->isReal($percentValue)) {
            $rowResult->addError(Result::ERR_PERCENT_VALUE_MUST_BE_DECIMAL);
            return;
        }

        if (Floating::lt($percentValue, 0)) {
            $rowResult->addError(Result::ERR_PERCENT_VALUE_MUST_BE_POSITIVE);
            return;
        }
    }

    protected function validateModeValue(RowResult $rowResult): void
    {
        $modeValue = $rowResult->toStringModeValue();
        $validRangeModes = array_merge(Constants\BuyersPremium::$rangeModes, Constants\BuyersPremium::$rangeModeNames);
        if (!in_array($modeValue, $validRangeModes, true)) {
            $rowResult->addError(Result::ERR_MODE_VALUE_INVALID);
            return;
        }
    }
}
