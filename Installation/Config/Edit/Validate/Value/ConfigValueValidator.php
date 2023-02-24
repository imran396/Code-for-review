<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Edit\Validate\Exceptions\OptionValueCheckerExistenceException;

/**
 * Validate single input value.
 * Class OptionValueValidator
 */
class ConfigValueValidator extends CustomizableClass
{
    use InputValueCheckerCreateTrait;
    use OptionValueCheckerCreateTrait;
    use ResultStatusCollectorAwareTrait;

    public const V_FLOAT = 1;
    public const V_FLOAT_POSITIVE = 2;
    public const V_FLOAT_POSITIVE_OR_ZERO = 3;
    public const V_HEX_STRING = 4;
    public const V_INT = 5;
    public const V_INT_POSITIVE = 6;
    public const V_INT_POSITIVE_OR_ZERO = 7;
    public const V_IS_ARRAY = 8;
    public const V_KNOWN_SET = 9;
    public const V_MAX_LENGTH = 10;
    public const V_MIN_LENGTH = 11;
    public const V_NULL = 12;
    public const V_RANGE = 13;
    public const V_REG_EXP = 14;
    public const V_REQUIRED = 15;
    public const V_STRING = 16;
    public const V_SUBNET = 17;
    public const V_SUBSTRING_DELIMITER_COMA = 18;
    public const V_SUBSTRING_QUOTED = 19;
    public const V_BOOL = 20;
    public const V_OCT_STRING = 21;

    /**
     * Constraint name => Validation way
     * @var int[]
     */
    protected const ERROR_CODES_MAP = [
        Constants\Installation::C_BOOL => self::V_BOOL,
        Constants\Installation::C_FLOAT => self::V_FLOAT,
        Constants\Installation::C_FLOAT_POSITIVE => self::V_FLOAT_POSITIVE,
        Constants\Installation::C_FLOAT_POSITIVE_OR_ZERO => self::V_FLOAT_POSITIVE_OR_ZERO,
        Constants\Installation::C_HEX_STRING => self::V_HEX_STRING,
        Constants\Installation::C_OCT_STRING => self::V_OCT_STRING,
        Constants\Installation::C_INT => self::V_INT,
        Constants\Installation::C_INT_POSITIVE => self::V_INT_POSITIVE,
        Constants\Installation::C_INT_POSITIVE_OR_ZERO => self::V_INT_POSITIVE_OR_ZERO,
        Constants\Installation::C_IS_ARRAY => self::V_IS_ARRAY,
        Constants\Installation::C_KNOWN_SET => self::V_KNOWN_SET,
        Constants\Installation::C_MAX_LENGTH => self::V_MAX_LENGTH,
        Constants\Installation::C_MIN_LENGTH => self::V_MIN_LENGTH,
        Constants\Installation::C_NULL => self::V_NULL,
        Constants\Installation::C_RANGE => self::V_RANGE,
        Constants\Installation::C_REG_EXP => self::V_REG_EXP,
        Constants\Installation::C_REQUIRED => self::V_REQUIRED,
        Constants\Installation::C_STRING => self::V_STRING,
        Constants\Installation::C_SUBNET => self::V_SUBNET,
        Constants\Installation::C_SUBSTRING_DELIMITER_COMA => self::V_SUBSTRING_DELIMITER_COMA,
        Constants\Installation::C_SUBSTRING_QUOTED => self::V_SUBSTRING_QUOTED,
    ];

    /**
     * When this validation constraint failed, we should finish check value (eg. Required)
     * @var string[]
     */
    protected array $exclusiveConstraints = [
        Constants\Installation::C_REQUIRED,
    ];
    /**
     * Flat config option key.
     * @var string|null
     */
    protected ?string $optionKey = null;
    /** @var bool */
    private bool $isInputValueValidation = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate input value.
     * @param mixed $value
     * @param array $constraints
     * @return bool
     * @throws OptionValueCheckerExistenceException
     */
    public function validate(mixed $value, array $constraints): bool
    {
        $this->initResultStatusCollector();

        foreach ($constraints as $constraintType => $constraintArguments) {
            $success = $this->validateConcrete($value, $constraintType, $constraintArguments);
            if (!$success) {
                $errorCode = $this->getErrorCode($constraintType);
                $errorMessage = $this->buildExtendedErrorMessage($errorCode, $constraintType, $constraintArguments);
                $this->getResultStatusCollector()->addError($errorCode, $errorMessage);
                if (in_array($constraintType, $this->getExclusiveConstraints(), true)) {
                    break;
                }
            }
        }
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return ResultStatus[]
     */
    public function errorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string[]
     */
    public function getExclusiveConstraints(): array
    {
        return $this->exclusiveConstraints;
    }

    /**
     * @param string[] $constraints
     * @return static
     * @noinspection PhpUnused
     */
    public function setExclusiveConstraints(array $constraints): static
    {
        $this->exclusiveConstraints = ArrayCast::makeStringArray($constraints);
        return $this;
    }

    /**
     * @param bool $enable
     * @return static
     * @noinspection PhpUnused
     */
    public function enableInputValueValidation(bool $enable): static
    {
        $this->isInputValueValidation = $enable;
        return $this;
    }

    /**
     * @param mixed $value
     * @param string $constraintType
     * @param mixed $constraintArguments
     * @return bool
     * @throws OptionValueCheckerExistenceException
     */
    protected function validateConcrete(mixed $value, string $constraintType, mixed $constraintArguments): bool
    {
        $inputValueChecker = $this->createInputValueChecker();
        if (
            $inputValueChecker->hasChecker($constraintType)
            && $this->isInputValueValidation()
        ) {
            return $inputValueChecker->isValid($value, $constraintType, $constraintArguments);
        }

        try {
            return $this->createOptionValueChecker()->isValid($value, $constraintType, $constraintArguments);
        } catch (OptionValueCheckerExistenceException $e) {
            if ($inputValueChecker->hasChecker($constraintType)) {
                return true;
            }
            throw $e;
        }
    }

    /**
     * Build extended error message with input arguments from validation method.
     * @param int $errorCode
     * @param string $constraintType
     * @param mixed $constraintArguments
     * @return string
     */
    protected function buildExtendedErrorMessage(int $errorCode, string $constraintType, mixed $constraintArguments): string
    {
        $message = $this->getResultStatusCollector()->getErrorMessageByCodeAmongAll($errorCode);
        if (isset($constraintArguments)) {
            if ($constraintType === Constants\Installation::C_KNOWN_SET) {
                $message = sprintf($message, implode(', ', $constraintArguments));
            } else {
                $message = is_array($constraintArguments)
                    ? sprintf($message, ...$constraintArguments)
                    : sprintf($message, $constraintArguments);
            }
        }
        return $message;
    }

    /**
     * @param string $constraintType
     * @return int
     */
    protected function getErrorCode(string $constraintType): int
    {
        $code = 0;
        if (array_key_exists($constraintType, self::ERROR_CODES_MAP)) {
            $code = self::ERROR_CODES_MAP[$constraintType];
        }
        return $code;
    }

    /**
     * @return void
     */
    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::V_BOOL => 'The value must be of type "boolean".',
            self::V_FLOAT => 'The value must be of type "float".',
            self::V_FLOAT_POSITIVE => 'The value must be of type "float" and positive.',
            self::V_FLOAT_POSITIVE_OR_ZERO => 'The value must be of type "float" and positive or zero.',
            self::V_HEX_STRING => 'The value must be a valid HEX encoded string.',
            self::V_OCT_STRING => 'The value must be a valid octal number',
            self::V_INT => 'The value must be of type "Integer".',
            self::V_INT_POSITIVE => 'The value must be of type "Integer" and positive.',
            self::V_INT_POSITIVE_OR_ZERO => 'The value must be of type "Integer" and positive or zero.',
            self::V_IS_ARRAY => 'The value must be of type "array".',
            self::V_KNOWN_SET => 'The value can accept only following values: %s.',
            self::V_MAX_LENGTH => 'The value must be no more than %d characters length.',
            self::V_MIN_LENGTH => 'The value must be at least %d characters length.',
            self::V_NULL => 'The value must be of type "NULL" or empty.',
            self::V_RANGE => 'The value must be from %d to %d.',
            self::V_REG_EXP => 'The value must matches with regular expression: %s.',
            self::V_REQUIRED => 'This option is required.',
            self::V_STRING => 'The value must be of type "string".',
            self::V_SUBNET => 'Invalid subnet',
            self::V_SUBSTRING_DELIMITER_COMA => 'You must use "," (coma symbol) as substring delimiter.',
            self::V_SUBSTRING_QUOTED => 'Substrings in string (if they are not a numeric values) must be quoted.',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }

    /**
     * @return bool
     */
    private function isInputValueValidation(): bool
    {
        return $this->isInputValueValidation;
    }
}
