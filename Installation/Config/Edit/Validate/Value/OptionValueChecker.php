<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-09, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value;

use Sam\Core\Constants;
use Sam\Core\Ip\Validate\SubnetValidator;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class OptionValueChecker contains clean validation methods only. OptionValueValidator used methods from this class.
 * @package Sam\Installation\Config
 */
class OptionValueChecker extends AbstractValueChecker
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * (@inheritDoc)
     */
    public function isValid(mixed $value, string $constraintType, mixed $constraintArguments = null): bool
    {
        if ($value === null && $constraintType !== Constants\Installation::C_REQUIRED) {
            return true;
        }

        return parent::isValid($value, $constraintType, $constraintArguments);
    }

    /**
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function existValue(mixed $value): bool
    {
        $is = true;
        if ($value === null || $value === '') {
            $is = false;
        }
        return $is;
    }

    /**
     * @param array $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function isArray(mixed $value): bool
    {
        $is = is_array($value);
        return $is;
    }

    /**
     * Validation: is value a "string" type
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function string(mixed $value): bool
    {
        $is = is_string($value);
        return $is;
    }

    /**
     * Validation: is value a "NULL" type
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function null(mixed $value): bool
    {
        $is = false;
        if (
            empty($value)
            && (
                is_string($value)
                || is_array($value)
            )
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Checks is value in pre defined values set($knownSets)
     * @param array|mixed $values
     * @param array $knownSets
     * @param bool $strict
     * @return bool
     * @noinspection PhpUnused
     */
    public function knownSet(mixed $values, array $knownSets, bool $strict = false): bool
    {
        $values = is_array($values) ? $values : [$values];
        foreach ($values as $value) {
            if (!in_array($value, $knownSets, $strict)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function hexString(string $value): bool
    {
        $is = !empty($value) ? ctype_xdigit($value) : false;
        return $is;
    }

    /**
     * @param string $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function octString(string $value): bool
    {
        return (bool)preg_match('/^-?[0-7]+$/', $value);
    }

    /**
     * Validate for minimum string length
     * @param string $value
     * @param int $length
     * @return bool
     * @noinspection PhpUnused
     */
    public function minLength(string $value, int $length): bool
    {
        $is = false;
        if (
            !empty($value)
            && strlen($value) >= $length
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Validate for maximum string length
     * @param string $value
     * @param int $length
     * @return bool
     * @noinspection PhpUnused
     */
    public function maxLength(string $value, int $length): bool
    {
        $is = false;
        if (
            !empty($value)
            && strlen($value) <= $length
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Validate is data in range.
     * @param string|int|float $value
     * @param int $min
     * @param int $max
     * @return bool
     * @noinspection PhpUnused
     */
    public function range(string|int|float $value, int $min, int $max): bool
    {
        $is = false;
        if (
            !empty($value)
            && is_numeric($value)
            && ((float)$value >= $min && (float)$value <= $max)
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Validate string to matches with regular expression.
     * @param string $value
     * @param string $regExp regular expression pattern
     * @return bool
     * @noinspection PhpUnused
     */
    public function regExp(string $value, string $regExp): bool
    {
        $is = false;
        if (
            !empty($value)
            && preg_match($regExp, $value)
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function subnet(mixed $value): bool
    {
        if (!$this->isArray($value)) {
            return false;
        }

        $is = false;
        $c = 0;
        foreach ($value ?: [] as $subnet) {
            $isCurrentValid = SubnetValidator::new()->validate($subnet);
            if ($isCurrentValid) {
                $c++;
            }
        }
        if ($c === count($value)) {
            $is = true;
        }
        return $is;
    }

    /**
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function isBool(mixed $value): bool
    {
        return is_bool($value);
    }

    /**
     * (@inheritDoc)
     */
    protected function getCheckerMap(): array
    {
        $numberValidator = NumberValidator::new();
        return [
            Constants\Installation::C_REQUIRED => [$this, 'existValue'],
            Constants\Installation::C_FLOAT => [$numberValidator, 'isReal'],
            Constants\Installation::C_FLOAT_POSITIVE => [$numberValidator, 'isRealPositive'],
            Constants\Installation::C_FLOAT_POSITIVE_OR_ZERO => [$numberValidator, 'isRealPositiveOrZero'],
            Constants\Installation::C_INT => [$numberValidator, 'isInt'],
            Constants\Installation::C_INT_POSITIVE => [$numberValidator, 'isIntPositive'],
            Constants\Installation::C_INT_POSITIVE_OR_ZERO => [$this, 'isIntPositiveOrZero'],
            Constants\Installation::C_KNOWN_SET => [$this, 'knownSet'],
            Constants\Installation::C_HEX_STRING => [$this, 'hexString'],
            Constants\Installation::C_OCT_STRING => [$this, 'octString'],
            Constants\Installation::C_IS_ARRAY => [$this, 'isArray'],
            Constants\Installation::C_MAX_LENGTH => [$this, 'maxLength'],
            Constants\Installation::C_MIN_LENGTH => [$this, 'minLength'],
            Constants\Installation::C_NULL => [$this, 'null'],
            Constants\Installation::C_RANGE => [$this, 'range'],
            Constants\Installation::C_REG_EXP => [$this, 'regExp'],
            Constants\Installation::C_STRING => [$this, 'string'],
            Constants\Installation::C_SUBNET => [$this, 'subnet'],
            Constants\Installation::C_BOOL => [$this, 'isBool']
        ];
    }
}
