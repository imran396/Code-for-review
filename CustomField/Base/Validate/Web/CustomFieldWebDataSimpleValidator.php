<?php

/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 31, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Validate\Web;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Core\Constants;

/**
 * Class CustomFieldWebDataSimpleValidator
 * @package Sam\CustomField\Base\Validate\Web
 */
class CustomFieldWebDataSimpleValidator extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;

    public const ERR_REQUIRED = 1;
    public const ERR_INTEGER = 2;
    public const ERR_NUMERIC = 3;
    public const ERR_NUMBER_FORMAT = 4;
    public const ERR_THOUSAND_SEPARATOR = 5;
    public const ERR_OPTION = 6;
    public const ERR_DATE = 7;
    public const ERR_ENCODING = 8;
    public const ERR_CHECKBOX = 9;

    /**
     * Determine some translation for rendering, we need at public to translate errors, at admin side do not
     */
    protected bool $isTranslating = true;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isTranslating(): bool
    {
        return $this->isTranslating;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableTranslating(bool $enable): static
    {
        $this->isTranslating = $enable;
        return $this;
    }

    /**
     * Validate value of custom field
     *
     * @param int|string|bool|null $value
     * @param int $fieldType
     * @param string $parameters
     * @param bool $isRequired
     * @return bool
     */
    public function validateByType(int|string|bool|null $value, int $fieldType, string $parameters, bool $isRequired): bool
    {
        $this->initResultStatusCollector();

        $isEmpty = (string)$value === '';
        if ($isRequired && $isEmpty) {
            $this->getResultStatusCollector()->addError(self::ERR_REQUIRED);
        } elseif (!$isEmpty) {
            switch ($fieldType) {
                case Constants\CustomField::TYPE_LABEL:
                    $this->validateLabel($value);
                    break;
                case Constants\CustomField::TYPE_INTEGER:
                    $this->validateInteger($value);
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $this->validateDecimal($value);
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $this->validateSelect($value, $parameters);
                    break;
                case Constants\CustomField::TYPE_CHECKBOX:
                    $this->validateCheckbox($value);
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $this->validateDate($value);
                    break;
                case Constants\CustomField::TYPE_TEXT:
                    $this->validateText($value);
                    break;
                case Constants\CustomField::TYPE_FULLTEXT:
                    $this->validateFulltext($value);
                    break;
                case Constants\CustomField::TYPE_PASSWORD:
                    $this->validatePassword($value);
                    break;
                case Constants\CustomField::TYPE_RICHTEXT:
                    $this->validateRichText($value);
                    break;
            }
        }
        $success = !$this->getResultStatusCollector()->hasError();
        return $success;
    }

    /**
     * Validate value for label type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateLabel(int|string|bool $input): void
    {
    }

    /**
     * Validate value for integer type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateInteger(int|string|bool $input): void
    {
        if (!NumberValidator::new()->isInt($this->getNumberFormatter()->removeFormat((string)$input))) {
            $this->getResultStatusCollector()->addError(self::ERR_INTEGER);
        }
    }

    /**
     * Validate value for decimal type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateDecimal(int|string|bool $input): void
    {
        if (!NumberValidator::new()->isReal($this->getNumberFormatter()->removeFormat((string)$input))) {
            $this->getResultStatusCollector()->addError(self::ERR_NUMERIC);
        }

        $numberFormatValidateResult = $this->getNumberFormatter()->validateNumberFormat((string)$input);
        if ($numberFormatValidateResult->hasError()) {
            $this->getResultStatusCollector()->addError(self::ERR_NUMBER_FORMAT);
        }
    }

    /**
     * Validate value for select type custom field and return error type
     *
     * @param int|string|bool $input
     * @param string $parameters
     * @return void
     */
    protected function validateSelect(int|string|bool $input, string $parameters): void
    {
        $options = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($parameters);
        foreach ($options as $key => $optionValue) {
            $options[$key] = trim((string)$optionValue);
        }
        if (!in_array(trim($input), $options, true)) {
            $this->getResultStatusCollector()->addError(self::ERR_OPTION);
        }
    }

    /**
     * Validate value for checkbox type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateCheckbox(int|string|bool $input): void
    {
        if (!in_array((int)$input, [0, 1], true)) {
            $this->getResultStatusCollector()->addError(self::ERR_CHECKBOX);
        }
    }

    /**
     * Validate value for date type custom field and return error type
     *
     * @param string $input
     * @return void
     */
    protected function validateDate(string $input): void
    {
        $isValidDate = is_numeric($input) ? NumberValidator::new()->isInt($input) : (bool)strtotime($input);
        if (!$isValidDate) {
            $this->getResultStatusCollector()->addError(self::ERR_DATE);
        }
    }

    /**
     * Validate value for text type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateText(int|string|bool $input): void
    {
    }

    /**
     * Validate value for fulltext type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateFulltext(int|string|bool $input): void
    {
    }

    /**
     * Validate value for password type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validatePassword(int|string|bool $input): void
    {
    }

    /**
     * Validate value for rich text type custom field and return error type
     *
     * @param int|string|bool $input
     * @return void
     */
    protected function validateRichText(int|string|bool $input): void
    {
    }

    /**
     * @return bool
     */
    public function hasCheckboxError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_CHECKBOX]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function checkboxErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_CHECKBOX]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasEncodingError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_ENCODING]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function encodingErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_ENCODING]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasDateError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_DATE]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function dateErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_DATE]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasOptionError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_OPTION]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function optionErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_OPTION]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasThousandSeparatorError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_THOUSAND_SEPARATOR]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function thousandSeparatorErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_THOUSAND_SEPARATOR]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasRequiredError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_REQUIRED]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function requiredErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_REQUIRED]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasIntegerError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_INTEGER]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function integerErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_INTEGER]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasNumericError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_NUMERIC]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function numericErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_NUMERIC]);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasNumberFormatError(): bool
    {
        $hasError = $this->getResultStatusCollector()->hasConcreteError([self::ERR_NUMBER_FORMAT]);
        return $hasError;
    }

    /**
     * @return string
     */
    public function numberFormatErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_NUMBER_FORMAT]);
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function findFirstError(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(
            [
                self::ERR_REQUIRED,
                self::ERR_INTEGER,
                self::ERR_NUMERIC,
                self::ERR_NUMBER_FORMAT,
                self::ERR_THOUSAND_SEPARATOR,
                self::ERR_OPTION,
                self::ERR_DATE,
                self::ERR_ENCODING,
                self::ERR_CHECKBOX,
            ]
        );
        return $errorMessage;
    }

    /**
     * Initialize error messages
     */
    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_REQUIRED => 'required',
            self::ERR_INTEGER => 'should be numeric integer',
            self::ERR_NUMERIC => 'should be numeric',
            self::ERR_NUMBER_FORMAT => 'invalid number format',
            self::ERR_THOUSAND_SEPARATOR => 'number must be without thousand separator',
            self::ERR_OPTION => 'invalid option "%s"',
            self::ERR_DATE => 'invalid date',
            self::ERR_ENCODING => 'invalid "%s" encoding',
            self::ERR_CHECKBOX => 'should be 0 or 1',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
