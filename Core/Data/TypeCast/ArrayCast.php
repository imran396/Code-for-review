<?php
/**
 * Helper class for array related functionality
 *
 * SAM-4825: Strict type related adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           7 Jun, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\TypeCast;

use Sam\Core\Constants;

/**
 * Class ArrayHelper
 * @package Sam\Core\Data
 */
class ArrayCast
{
    public const OP_SKIP_KEYS = 'skipKeys'; // string[]
    public const OP_KEEP_NULLS = 'keepNulls'; // bool

    // --- Cast type and normalize values of array ---

    /**
     * Cast multi-dimensional array values to bool type
     * @param array $rows
     * @param string|bool[]|null $filter null - means apply default for boolean type filter; bool[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_SKIP_KEYS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return bool[]
     */
    public static function castBool(array $rows, string|array|null $filter = null, array $options = []): array
    {
        return self::cast(Constants\Type::T_BOOL, $rows, $filter, $options);
    }

    /**
     * Cast multi-dimensional array values to float(aka double,decimal) type
     * @param array $rows
     * @param string|float[]|null $filter null - means apply default for float type filter; float[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return float[]
     */
    public static function castFloat(array $rows, string|array|null $filter = null, array $options = []): array
    {
        return self::cast(Constants\Type::T_FLOAT, $rows, $filter, $options);
    }

    /**
     * Cast multi-dimensional array values to int type
     * @param array $rows
     * @param string|int[]|null $filter null - means apply default for integer filter; int[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return int[]
     */
    public static function castInt(array $rows, string|array|null $filter = null, array $options = []): array
    {
        return self::cast(Constants\Type::T_INTEGER, $rows, $filter, $options);
    }

    /**
     * Cast multi-dimensional array values to string type
     * @param array $rows
     * @param string|string[]|null $filter null - means apply default filter, it is F_STRING_TRIM by default as it is more desired behavior; string[] - filter by known set
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return string[]
     */
    public static function castString(array $rows, string|array|null $filter = null, array $options = []): array
    {
        return self::cast(Constants\Type::T_STRING, $rows, $filter, $options);
    }

    // --- extract column from multi-dimensional array with type casting and normalization

    /**
     * Extracts single column from input multi-dimensional array and casts its values to bool type
     * @param array $rows
     * @param string|int $column
     * @param string|bool[]|null $filter null means apply default for boolean type filter; bool[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return bool[]
     */
    public static function arrayColumnBool(array $rows, string|int $column, string|array|null $filter = null, array $options = []): array
    {
        $row = array_column($rows, $column);
        $row = self::castBool($row, $filter, $options);
        return $row;
    }

    /**
     * Extracts single column from input multi-dimensional array and casts its values to float type
     * @param array $rows
     * @param string|int $column
     * @param string|float[]|null $filter null means apply default for float type filter; float[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return float[]
     */
    public static function arrayColumnFloat(array $rows, string|int $column, string|array|null $filter = null, array $options = []): array
    {
        $row = array_column($rows, $column);
        $row = self::castFloat($row, $filter, $options);
        return $row;
    }

    /**
     * Extracts single column from input multi-dimensional array and casts its values to int type
     * @param array $rows
     * @param string|int $column
     * @param string|int[]|null $filter null - means apply default for integer type filter; int[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return int[]
     */
    public static function arrayColumnInt(array $rows, string|int $column, string|array|null $filter = null, array $options = []): array
    {
        $row = array_column($rows, $column);
        $row = self::castInt($row, $filter, $options);
        return $row;
    }

    /**
     * Extracts single column from input multi-dimensional array and casts its values to string type
     * @param array $rows
     * @param string|int $column
     * @param string|string[]|null $filter null - means apply default for string type filter; string[] - filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting
     *  ArrayCast::OP_KEEP_NULLS => bool, false to filter out null values from result array, true to keep nulls in results
     * ]
     * @return string[]
     */
    public static function arrayColumnString(array $rows, string|int $column, string|array|null $filter = null, array $options = []): array
    {
        $row = array_column($rows, $column);
        $row = self::castString($row, $filter, $options);
        return $row;
    }

    // --- make typed arrays ---

    /**
     * Produce array of boolean values by $input.
     * Default filter F_BOOL skip null values for bool type-casting.
     * @param array|bool|int|null $input
     * @param string|bool[]|null $filter null - means apply default for boolean type filter; bool[] - filter by known set;
     * @return bool[]|null[]
     */
    public static function makeBoolArray(mixed $input, string|array|null $filter = null): array
    {
        $output = self::makeTypedArray(Constants\Type::T_BOOL, $input, $filter);
        return $output;
    }

    /**
     * Produce array of float values by $input.
     * Default filter F_FLOAT skip null values for float type-casting.
     * @param array|float|null $input
     * @param string|float[]|null $filter null - means apply default for float type filter; float[] - filter by known set;
     * @return float[]|null[]
     */
    public static function makeFloatArray(mixed $input, string|array|null $filter = null): array
    {
        $output = self::makeTypedArray(Constants\Type::T_FLOAT, $input, $filter);
        return $output;
    }

    /**
     * Produce array of integer values by $input.
     * Default filter F_INT skip null values for int type-casting.
     * @param array|int|string|null $input
     * @param string|int[]|null $filter null - means apply default for integer type filter; int[] - filter by known set;
     * @return int[]|null[]
     */
    public static function makeIntArray(mixed $input, string|array|null $filter = null): array
    {
        $output = self::makeTypedArray(Constants\Type::T_INTEGER, $input, $filter);
        return $output;
    }

    /**
     * Produce array of string values by $input.
     * Default filter F_STRING skip null values for string type-casting.
     * @param string[]|mixed $input
     * @param string|string[]|null $filter null - means apply default for string type filter; string[] - filter by known set;
     * @return string[]|null[]
     */
    public static function makeStringArray(mixed $input, string|array|null $filter = null): array
    {
        $output = self::makeTypedArray(Constants\Type::T_STRING, $input, $filter);
        return $output;
    }

    // --- protected methods with general functionality ---

    /**
     * Cast multi-dimensional array values to definite type
     * @param string $type
     * @param array $rows
     * @param string|string[]|int[]|float[]|bool[]|null $filter null - means apply default for type filter; string[]|int[]|float[]|bool[] - means filter by known set;
     * @param array $options = [
     *  ArrayCast::OP_KEEP_NULLS => int[]|string[], keys, which values we should skip type-casting.
     *  ArrayCast::OP_KEEP_NULLS => bool (true), false to filter out null values from result array, true to keep nulls in results.
     * ]
     * @return bool[]|int[]|float[]|string[]|null[]
     */
    protected static function cast(
        string $type,
        array $rows,
        string|array|null $filter = null,
        array $options = []
    ): array {
        $skipKeys = $options[self::OP_SKIP_KEYS] ?? [];
        $keepNulls = $options[self::OP_KEEP_NULLS] ?? true;
        foreach ($rows as $i => $value) {
            if (in_array($i, $skipKeys, true)) {
                continue;
            }
            if (is_array($value)) {
                $rows[$i] = self::cast($type, $value, $filter, $options);
            } else {
                if ($type === Constants\Type::T_BOOL) {
                    $rows[$i] = Cast::toBool($value, $filter);
                } elseif ($type === Constants\Type::T_INTEGER) {
                    $rows[$i] = Cast::toInt($value, $filter);
                } elseif ($type === Constants\Type::T_FLOAT) {
                    $rows[$i] = Cast::toFloat($value, $filter);
                } elseif ($type === Constants\Type::T_STRING) {
                    $rows[$i] = Cast::toString($value, $filter);
                }
            }
        }
        if (!$keepNulls) {
            $rows = array_filter(
                $rows,
                static function ($value) {
                    return $value !== null;
                }
            );
        }
        return $rows;
    }

    /**
     * @param string $type
     * @param mixed $input
     * @param string|string[]|int[]|float[]|bool[]|null $filter string - concrete filter name; array - filter by known set;
     * @param array $options
     * @return bool[]|float[]|int[]|string[]|null[]
     */
    public static function makeTypedArray(string $type, mixed $input, string|array|null $filter, array $options = []): array
    {
        $output = [];
        if ($input !== null) {
            $output = self::cast($type, (array)$input, $filter, $options);
        }
        return $output;
    }
}
