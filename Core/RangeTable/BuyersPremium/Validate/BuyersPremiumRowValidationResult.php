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

namespace Sam\Core\RangeTable\BuyersPremium\Validate;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult as Result;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class BuyersPremiumRowValidationResult
 * @package Sam\Core\RangeTable\BuyersPremium\Validate
 */
class BuyersPremiumRowValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public int $index;
    public array $row;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $index
     * @param array $row
     * @param array $errorMessages
     * @return $this
     */
    public function construct(int $index, array $row, array $errorMessages): static
    {
        $this->index = $index;
        $this->row = $row;
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * Normalize to float an input value of "Start Amount".
     * @return float|null
     */
    public function toFloatStartAmount(): ?float
    {
        return isset($this->row[0]) && NumberValidator::new()->isReal($this->row[0]) ? (float)$this->row[0] : null;
    }

    /**
     * Normalize to float an input value of "Fixed Value".
     * @return float|null
     */
    public function toFloatFixedValue(): ?float
    {
        return isset($this->row[1]) && NumberValidator::new()->isReal($this->row[1]) ? (float)$this->row[1] : null;
    }

    /**
     * Normalize to float an input value of "Percent Value".
     * @return float|null
     */
    public function toFloatPercentValue(): ?float
    {
        return isset($this->row[2]) && NumberValidator::new()->isReal($this->row[2])
            ? (float)$this->row[2]
            : null;
    }

    /**
     * Normalize to string an input value of "Start Amount".
     * @return string
     */
    public function toStringStartAmount(): string
    {
        return (string)Cast::toString($this->row[0] ?? '');
    }

    /**
     * Normalize to string an input value of "Fixed Value".
     * @return string
     */
    public function toStringFixedValue(): string
    {
        return (string)Cast::toString($this->row[1] ?? '');
    }

    /**
     * Normalize to string an input value of "Percent Value".
     * @return string
     */
    public function toStringPercentValue(): string
    {
        return (string)Cast::toString($this->row[2] ?? '');
    }

    /**
     * Normalize to string an input value of "Mode Value".
     * @return string
     */
    public function toStringModeValue(): string
    {
        return (string)Cast::toString($this->row[3] ?? '');
    }

    /**
     * Compare equality by "Start Amount" when input is type-casted to float.
     * @param BuyersPremiumRowValidationResult $rowResult
     * @return bool
     */
    public function isEqualByStartAmount(self $rowResult): bool
    {
        return Floating::eq($this->toFloatStartAmount(), $rowResult->toFloatStartAmount());
    }

    /**
     * Compare indexes are the same.
     * @param BuyersPremiumRowValidationResult $rowResult
     * @return bool
     */
    public function isEqualByIndex(self $rowResult): bool
    {
        return $this->index === $rowResult->index;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        $logData = ['index' => $this->index, 'row' => $this->row];
        if ($this->hasError()) {
            $logData['error code'] = $this->errorCodes();
            $logData['error message'] = $this->errorMessages();
        }
        return $logData;
    }

    /**
     * Is error for "Start Amount" field.
     * @return bool
     */
    public function hasStartAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::START_AMOUNT_ERROR_CODES);
    }

    /**
     * Return error message for "Start Amount" field.
     * @return string
     */
    public function getStartAmountErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(Result::START_AMOUNT_ERROR_CODES) ?? '';
    }

    /**
     * Is error for "Fixed Value" field.
     * @return bool
     */
    public function hasFixedValueError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::FIXED_VALUE_ERROR_CODES);
    }

    /**
     * Return error message for "Fixed Value" field.
     * @return string
     */
    public function getFixedValueErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(Result::FIXED_VALUE_ERROR_CODES) ?? '';
    }

    /**
     * Is error for "Percent Value" field.
     * @return bool
     */
    public function hasPercentValueError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::PERCENT_VALUE_ERROR_CODES);
    }

    /**
     * Return error message for "Percent Value" field.
     * @return string
     */
    public function getPercentValueErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(Result::PERCENT_VALUE_ERROR_CODES) ?? '';
    }

    /**
     * Is error for "Mode Value" field.
     * @return bool
     */
    public function hasModeValueError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::MODE_VALUE_ERROR_CODES);
    }

    /**
     * Return error message for "Mode Value" field.
     * @return string
     */
    public function getModeValueErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(Result::MODE_VALUE_ERROR_CODES) ?? '';
    }

    /**
     * Is error because of input format.
     * @return bool
     */
    public function hasFormatError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(Result::FORMAT_ERROR_CODES);
    }

    /**
     * Return error message for input format.
     * @return string
     */
    public function getFormatErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(Result::FORMAT_ERROR_CODES) ?? '';
    }
}
