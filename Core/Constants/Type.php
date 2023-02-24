<?php

namespace Sam\Core\Constants;

/**
 * Class PhpCore
 * @package Sam\Core\Constants
 */
class Type
{
    // type names from gettype(). Adjust if gettype() result will changed
    public const T_BOOL = 'boolean';
    public const T_INTEGER = 'integer';
    public const T_FLOAT = 'double';   // in php for historical reasons "double" is returned in case of a float
    public const T_STRING = 'string';
    public const T_ARRAY = 'array';
    public const T_OBJECT = 'object';
    public const T_RESOURCE = 'resource';
    public const T_NULL = 'NULL';
    public const T_UNKNOWN_TYPE = 'unknown type';

    /**
     * Filtering is disabled, we apply regular type casting.
     * "null" value will be cast to respective type.
     */
    public const F_DISABLED = 'Disabled';
    /**
     * Default filtering for toInt()
     * Filtering by integer number, else "null" value returned.
     * "null" input results to "null" too.
     */
    public const F_INT = 'Int';
    /**
     * Filtering by positive integer number.
     * Zero "0" isn't positive number.
     */
    public const F_INT_POSITIVE = 'IntPositive';
    /**
     * Filtering by positive integer number or zero.
     */
    public const F_INT_POSITIVE_OR_ZERO = 'IntPositiveOrZero';
    /**
     * Default filtering for toFloat()
     * Filtering by real number, else "null" value returned.
     */
    public const F_FLOAT = 'Float';
    /**
     * Filtering by positive real number
     * Zero "0.0" isn't among positive number set.
     */
    public const F_FLOAT_POSITIVE = 'FloatPositive';
    /**
     * Filtering by positive real number or zero
     */
    public const F_FLOAT_POSITIVE_OR_ZERO = 'FloatPositiveOrZero';
    /**
     * Filtering by value, that can be converted to bool
     * It relies to boolean type variables and
     * in case of string,integer,float types, we consider as boolable values,
     * that can be casted to integer 0, 1.
     */
    public const F_BOOL = 'Bool';
    /**
     * Filtering by value, that can be converted to string (int, float, bool, string).
     * For "null" value it produces "null".
     * For array it produces json encoded string.
     */
    public const F_STRING = 'String';
    /**
     * Filtering by "String" filtering and trim() result, if not null.
     */
    public const F_STRING_TRIM = 'StringTrim';
    /**
     * Apply url filtering to string type value
     */
    public const F_URL = 'Url';

    /** @var string[] */
    public const FILTER_BOOLEANS = [self::F_BOOL];
    /** @var string[] */
    public const FILTER_FLOATS = [self::F_FLOAT, self::F_FLOAT_POSITIVE, self::F_FLOAT_POSITIVE_OR_ZERO];
    /** @var string[] */
    public const FILTER_INTEGERS = [self::F_INT, self::F_INT_POSITIVE, self::F_INT_POSITIVE_OR_ZERO];
    /** @var string[] */
    public const FILTER_STRINGS = [self::F_STRING, self::F_STRING_TRIM, self::F_URL];
    /** @var string[][] */
    public const TYPES_TO_FILTERS = [
        self::T_BOOL => self::FILTER_BOOLEANS,
        self::T_FLOAT => self::FILTER_FLOATS,
        self::T_INTEGER => self::FILTER_INTEGERS,
        self::T_STRING => self::FILTER_STRINGS,
    ];
}
