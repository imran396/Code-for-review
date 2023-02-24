<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Edit\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Settlement\Check\Action\Edit\Single\Common\Input\SettlementCheckEditingInput as Input;
use Sam\Settlement\Check\Action\Edit\Single\Validate\Result\SettlementCheckEditingValidationResult as Result;
use Sam\Settlement\Check\Content\Build\Internal\Amount\AmountRendererCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use SettlementCheck;

/**
 * Class SettlementCheckEditingValidator
 * @package Sam\Settlement\Check
 */
class SettlementCheckEditingValidator extends CustomizableClass
{
    use AmountRendererCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Matches for calendar date + time according US date-time format: m\d\Y, hh:mm AM|PM
     * eg. '11/19/2021, 2:00 AM', '01/31/2022, 11:59 PM'
     * @var string
     */
    protected const DATE_REGEXP = '/^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[0-1])\/(202[1-9]),\s([1-9]|1[0-2]):(00|0[1-9]|1\d|2\d|3\d|4\d|5\d)\s(AM|PM)$/';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $this->getNumberFormatter()->construct($input->systemAccountId);
        $result = Result::new()->construct();
        $result = $this->validatePayee($input->payee, $result);
        $result = $this->validateAmount($input->amount, $result);
        $result = $this->validateCheckNo($input->checkNo, $result);
        $result = $this->validateAmountSpelling($input->amountSpelling, $result);
        $result = $this->validateMemo($input->memo, $result);
        $result = $this->validateNote($input->note, $result);
        $result = $this->validatePostedOnDate($input->postedOnSysIso, $result);
        $result = $this->validateClearedOnDate($input->clearedOnSysIso, $result);
        return $result;
    }

    protected function validatePayee(string $payee, Result $result): Result
    {
        if (trim($payee) === '') {
            return $result->addError(Result::ERR_PAYEE_REQUIRED);
        }

        if (mb_strlen($payee) > SettlementCheck::PAYEE_MAX_LENGTH) {
            return $result->addError(Result::ERR_PAYEE_MAX_LENGTH);
        }

        return $result;
    }

    protected function validateAmount(string $amount, Result $result): Result
    {
        $amount = trim($amount);
        if ($amount === '') {
            $result->addError(Result::ERR_AMOUNT_REQUIRED);
            return $result;
        }

        $amount = $this->getNumberFormatter()->removeFormat($amount);
        if (!NumberValidator::new()->isRealPositive($amount)) {
            return $result->addError(Result::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL);
        }

        return $result;
    }

    protected function validateCheckNo(string $checkNo, Result $result): Result
    {
        if (
            trim($checkNo) !== ''
            && !NumberValidator::new()->isIntPositive($checkNo)
        ) {
            return $result->addError(Result::ERR_CHECK_NO);
        }

        return $result;
    }

    protected function validateAmountSpelling(string $amountSpelling, Result $result): Result
    {
        if (trim($amountSpelling) === '') {
            return $result;
        }

        if (mb_strlen($amountSpelling) > SettlementCheck::AMOUNT_SPELLING_MAX_LENGTH) {
            return $result->addError(Result::ERR_AMOUNT_SPELLING_MAX_LENGTH);
        }

        return $result;
    }

    protected function validateMemo(string $memo, Result $result): Result
    {
        if (trim($memo) === '') {
            return $result;
        }

        if (mb_strlen($memo) > SettlementCheck::MEMO_MAX_LENGTH) {
            return $result->addError(Result::ERR_MEMO_MAX_LENGTH);
        }

        return $result;
    }

    protected function validateNote(string $note, Result $result): Result
    {
        if (trim($note) === '') {
            return $result;
        }

        if (mb_strlen($note) > SettlementCheck::NOTE_MAX_LENGTH) {
            return $result->addError(Result::ERR_NOTE_MAX_LENGTH);
        }

        return $result;
    }

    protected function validatePostedOnDate(?string $postedOnDate, Result $result): Result
    {
        if (trim((string)$postedOnDate) === '') {
            return $result;
        }

        if (!preg_match(self::DATE_REGEXP, trim($postedOnDate))) {
            return $result->addError(Result::ERR_POSTED_ON_DATE);
        }

        return $result;
    }

    protected function validateClearedOnDate(?string $clearedOnDate, Result $result): Result
    {
        if (trim((string)$clearedOnDate) === '') {
            return $result;
        }

        if (!preg_match(self::DATE_REGEXP, trim($clearedOnDate))) {
            return $result->addError(Result::ERR_CLEARED_ON_DATE);
        }

        return $result;
    }
}
