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

namespace Sam\CustomField\Base\Validate\Csv;

use Sam\Core\Constants;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class CustomFieldCsvDataSimpleValidator
 * @package Sam\CustomField\Base\Validate\Csv
 */
class CustomFieldCsvDataSimpleValidator extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;

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
     * Validate passed by import value for custom field
     *
     * @param string $value
     * @param int $fieldType
     * @param string $parameters
     * @param string|null $encoding optional
     * @return bool
     */
    public function validateImportByType(string $value, int $fieldType, string $parameters, ?string $encoding = null): bool
    {
        $this->initResultStatusCollector();

        switch ($fieldType) {
            case Constants\CustomField::TYPE_LABEL:
                $this->validateImportLabel($value);
                break;
            case Constants\CustomField::TYPE_INTEGER:
                $this->validateImportInteger($value);
                break;
            case Constants\CustomField::TYPE_DECIMAL:
                $this->validateImportDecimal($value);
                break;
            case Constants\CustomField::TYPE_SELECT:
                $this->validateImportSelect($value, $parameters, $encoding);
                break;
            case Constants\CustomField::TYPE_CHECKBOX:
                $this->validateImportCheckbox($value);
                break;
            case Constants\CustomField::TYPE_DATE:
                $this->validateImportDate($value);
                break;
            case Constants\CustomField::TYPE_TEXT:
                $this->validateImportText($value, $encoding);
                break;
            case Constants\CustomField::TYPE_FULLTEXT:
                $this->validateImportFulltext($value, $encoding);
                break;
            case Constants\CustomField::TYPE_PASSWORD:
                $this->validateImportPassword($value, $encoding);
                break;
            case Constants\CustomField::TYPE_RICHTEXT:
                $this->validateImportRichText($value, $encoding);
                break;
        }
        $success = !$this->getResultStatusCollector()->hasError();
        return $success;
    }

    /**
     * @param string $input
     * @return void
     */
    protected function validateImportLabel(string $input): void
    {
    }

    /**
     * @param string $input
     * @return void
     */
    protected function validateImportInteger(string $input): void
    {
        if (!NumberValidator::new()->isInt($input)) {
            $this->getResultStatusCollector()->addError(self::ERR_INTEGER);
        }
    }

    /**
     * @param string $input
     * @return void
     */
    protected function validateImportDecimal(string $input): void
    {
        if (!NumberValidator::new()->isReal($this->getNumberFormatter()->removeFormat($input))) {
            $this->getResultStatusCollector()->addError(self::ERR_NUMERIC);
            return;
        }

        $numberFormatValidateResult = $this->getNumberFormatter()->validateNumberFormat($input);
        if ($numberFormatValidateResult->hasError()) {
            $this->getResultStatusCollector()->addError(self::ERR_NUMBER_FORMAT);
        } elseif ($numberFormatValidateResult->isValidNumberWithThousandSeparator()) {
            $this->getResultStatusCollector()->addError(self::ERR_THOUSAND_SEPARATOR);
        }
    }

    /**
     * @param string $input
     * @param string $parameters
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportSelect(string $input, string $parameters, string $encoding = null): void
    {
        $options = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($parameters);
        foreach ($options as $key => $optionValue) {
            $options[$key] = trim((string)$optionValue);
        }
        if (!TextChecker::new()->hasValidEncoding($input, $encoding)) {
            $this->getResultStatusCollector()->addError(self::ERR_ENCODING);
        }
        if (!in_array(trim($input), $options, true)) {
            $this->getResultStatusCollector()->addError(self::ERR_OPTION);
        }
    }

    /**
     * @param string $input
     * @return void
     */
    protected function validateImportCheckbox(string $input): void
    {
        if (!in_array((int)$input, [0, 1], true)) {
            $this->getResultStatusCollector()->addError(self::ERR_CHECKBOX);
        }
    }

    /**
     * @param string $dateFormatted
     * @return void
     */
    protected function validateImportDate(string $dateFormatted): void
    {
        $year = substr($dateFormatted, 0, 4) ?: '';
        if (!is_numeric($year)) {
            $this->getResultStatusCollector()->addError(self::ERR_DATE);
            return;
        }
        $month = substr($dateFormatted, 5, 2) ?: '';
        $day = substr($dateFormatted, 8, 2) ?: '';
        $hour = substr($dateFormatted, 11, 2) ?: '';
        $minute = substr($dateFormatted, 14, 2) ?: '';
        $second = substr($dateFormatted, 17, 2) ?: '';
        if ($month === '') {
            $month = '01';
        }
        if ($day === '') {
            $day = '01';
        }
        if ($hour === '') {
            $hour = '00';
        }
        if ($minute === '') {
            $minute = '00';
        }
        if ($second === '') {
            $second = '00';
        }
        $dateIso = "$year-$month-$day $hour:$minute:$second";
        if (!DateFormatValidator::new()->isIsoFormatDateTime($dateIso)) {
            $this->getResultStatusCollector()->addError(self::ERR_DATE);
        }
    }

    /**
     * @param string $input
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportText(string $input, string $encoding = null): void
    {
        $this->validateImportEncoding($input, $encoding);
    }

    /**
     * @param string $input
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportFulltext(string $input, string $encoding = null): void
    {
        $this->validateImportEncoding($input, $encoding);
    }

    /**
     * @param string $input
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportPassword(string $input, string $encoding = null): void
    {
        $this->validateImportEncoding($input, $encoding);
    }

    /**
     * @param string $input
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportRichText(string $input, string $encoding = null): void
    {
        $this->validateImportEncoding($input, $encoding);
    }

    /**
     * @param string $input
     * @param string|null $encoding
     * @return void
     */
    protected function validateImportEncoding(string $input, string $encoding = null): void
    {
        if (!TextChecker::new()->hasValidEncoding($input, $encoding)) {
            $this->getResultStatusCollector()->addError(self::ERR_ENCODING);
        }
    }

    /**
     * @return string
     */
    public function findFirstError(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(
            [
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
