<?php
/**
 * General helper for qform web components
 *
 * SAM-4075: Helper methods for server request parameters filtering
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Apr, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

use InvalidArgumentException;
use Sam\Application\Index\Base\Exception\UnexpectedValueOfRequestParameter;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class RequestParamFetcher
 * @package Sam\Application\RequestParam
 */
class RequestParamFetcher extends CustomizableClass
{
    use ServerRequestAwareTrait;

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return static
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Checks if param names exists among params
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $params = $this->getParameters();
        $has = array_key_exists($name, $params);
        return $has;
    }

    /**
     * Check if not empty value exists among params by name
     * @param string $name
     * @return bool
     */
    public function hasFilled(string $name): bool
    {
        return $this->has($name)
            && $this->internalGet($name) !== ''
            && $this->internalGet($name) !== null;
    }

    /**
     * Checks if any of param names exists among (get/post) url params
     * @param string[] $names
     * @return bool
     */
    public function hasAny(array $names): bool
    {
        $params = array_keys($this->getParameters());
        $hasAny = count(array_intersect($names, $params)) > 0;
        return $hasAny;
    }

    /**
     * Return param value as is
     * @param string $name
     * @return array|string|null
     */
    protected function get(string $name): array|string|null
    {
        if (!$this->has($name)) {
            return null;
        }

        return $this->internalGet($name);
    }

    /**
     * Return param value as array. It can be passed in url as "arr[]=1&arr[]=2" and as "arr=1,2"
     * Return empty array [], when empty string is passed as parameter value, not [0 => '']
     * @param string $name
     * @return array
     */
    public function getArray(string $name): array
    {
        if (!$this->has($name)) {
            return [];
        }

        return $this->internalGetArray($name);
    }

    /**
     * Return param value as single-dimension array with integer values only.
     * We cut first dimension from multiple-dimension input array. We don't flatten it.
     * It drops null values from result array. See, unit test.
     * @param string $name
     * @return int[]
     */
    public function getArrayOfInt(string $name): array
    {
        $arr = $this->getMultiArrayOfInt($name, Constants\Type::F_INT);
        $arr = ArrayHelper::toFirstDimension($arr);
        return $arr;
    }

    /**
     * Return param value as single-dimension array with integer positive values only.
     * We cut first dimension from multiple-dimension input array. We don't flatten it.
     * It drops null values from result array. See, unit test.
     * @param string $name
     * @return int[]
     */
    public function getArrayOfIntPositive(string $name): array
    {
        $arr = $this->getMultiArrayOfInt($name);
        $arr = ArrayHelper::toFirstDimension($arr);
        return $arr;
    }

    /**
     * Return param value as array with integer values only.
     * By default, it returns an array of integer positive values.
     * Use this method when you expect multiple array in input.
     * It drops null values from result array. See, unit test.
     * @param string $name
     * @param string $filter additional filters. We can filter by:
     * @return int[]
     * @see Constants\Type::F_INT_POSITIVE // filter by 'Integer positive'
     * @see Constants\Type::F_INT_POSITIVE_OR_ZERO // filter by 'Integer positive or zero'.
     * @see Constants\Type::F_INT // filter by 'Integer'
     */
    public function getMultiArrayOfInt(string $name, string $filter = Constants\Type::F_INT_POSITIVE): array
    {
        if (!$this->has($name)) {
            return [];
        }

        $arr = $this->internalGetArray($name);
        $result = ArrayCast::castInt($arr, $filter, [ArrayCast::OP_KEEP_NULLS => false]);
        if (count(ArrayHelper::flattenArray($arr)) !== count(ArrayHelper::flattenArray($result))) {
            $this->handleUnexpectedValue($name, $arr, 'Array of ' . $filter);
        }
        return $result;
    }

    /**
     * Get param value as array with values that should exist in known set
     * @param string $name
     * @param array $set array of available values for param
     * @return array
     */
    public function getArrayOfKnownSet(string $name, array $set): array
    {
        if (!$this->has($name)) {
            return [];
        }

        $result = [];
        $arr = $this->internalGetArray($name);
        foreach ($arr as $value) {
            /** @noinspection TypeUnsafeArraySearchInspection */
            if (in_array($value, $set)) {
                $result[] = $value;
            }
        }
        if (count($arr) !== count($result)) {
            $constraint = sprintf('Array of known set (%s)', composeLogData($set));
            $this->handleUnexpectedValue($name, $arr, $constraint);
        }
        return $result;
    }

    /**
     * @param string $name
     * @return bool|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getBool(string $name): ?bool
    {
        if (!$this->has($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toString($input); // Don't convert null to "". Null means unexpected input and should cause exception.

        if (in_array($normalized, ["false", "", "0"], true)) {
            return false;
        }

        if (in_array($normalized, ["true", "on", "1"], true)) {
            return true;
        }

        $this->handleUnexpectedValue($name, $input, Constants\Type::F_BOOL);
    }

    /**
     * Return param value, if it has integer type, else return default value
     * @param string $name
     * @return int|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getInt(string $name): ?int
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toInt($input);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_INT);
        }
        return $normalized;
    }

    /**
     * Return param value, if it is positive integer, else return default value
     * @param string $name
     * @return int|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getIntPositive(string $name): ?int
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toInt($input, Constants\Type::F_INT_POSITIVE);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_INT_POSITIVE);
        }
        return $normalized;
    }

    /**
     * Return param value, if it is positive integer or zero
     * @param string $name
     * @return int|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getIntPositiveOrZero(string $name): ?int
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toInt($input, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        }
        return $normalized;
    }

    /**
     * Return param value, if it has float type, else return default value
     * @param string $name
     * @return float|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getFloat(string $name): ?float
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toFloat($input);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_FLOAT);
        }
        return $normalized;
    }

    /**
     * Return param value, if it is positive float, else return default value
     * @param string $name
     * @return float|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getFloatPositive(string $name): ?float
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toFloat($input, Constants\Type::F_FLOAT_POSITIVE);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_FLOAT_POSITIVE);
        }
        return $normalized;
    }

    /**
     * Return param value, if it is positive float or zero
     * @param string $name
     * @return float|null
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getFloatPositiveOrZero(string $name): ?float
    {
        if (!$this->hasFilled($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toFloat($input, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        }
        return $normalized;
    }

    /**
     * Return param value as string.
     * Use instead of get(), when you want filter out arrays passed in param.
     * @param string $name
     * @return string
     * @throws UnexpectedValueOfRequestParameter
     */
    public function getString(string $name): string
    {
        if (!$this->hasFilled($name)) {
            return '';
        }

        $input = $this->internalGet($name);
        $normalized = Cast::toString($input);
        if ($normalized === null) {
            $this->handleUnexpectedValue($name, $input, Constants\Type::F_STRING);
        }
        return $normalized;
    }

    /**
     * Get param value, if it exists in known set of values
     * @param string $name
     * @param array $set array of available values for param
     * @return array|string|null
     */
    public function getForKnownSet(string $name, array $set): array|string|null
    {
        if (!$this->has($name)) {
            return null;
        }

        $input = $this->internalGet($name);
        /** @noinspection TypeUnsafeArraySearchInspection */
        if (!in_array($input, $set)) {
            return null;
        }

        return $input;
    }

    /**
     * @param string $name
     * @return string|array|null
     */
    private function internalGet(string $name): string|array|null
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException("Parameter \"{$name}\" not found");
        }

        $params = $this->getParameters();
        $value = $params[$name];
        return $value;
    }

    /**
     * @param string $name
     * @return array
     */
    private function internalGetArray(string $name): array
    {
        $val = $this->internalGet($name);
        if (is_array($val)) {
            $arr = $val;
        } elseif ((string)$val === '') {
            $arr = [];
        } else {
            $arr = explode(',', $val);
        }
        return $arr;
    }

    /**
     * @param string $name
     * @param $value
     * @param string $constraint
     */
    protected function handleUnexpectedValue(string $name, $value, string $constraint): never
    {
        throw UnexpectedValueOfRequestParameter::withClarification($name, $value, $constraint);
    }
}
