<?php
/**
 * Type cast helper for individual variables.
 * Call ArrayHelper's cast methods for type transformations of array values
 *
 * SAM-4944: Defensive development approach
 * SAM-4825: Strict type related adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * TODO: modify when php7.4: self::$intCaster ??= IntCaster::new();
 */

namespace Sam\Core\Data\TypeCast;

/**
 * Class Cast
 * @package Sam\Core\Data\TypeCast
 */
class Cast
{
    protected static ?IntCaster $intCaster = null;
    protected static ?FloatCaster $floatCaster = null;
    protected static ?StringCaster $stringCaster = null;
    protected static ?BoolCaster $boolCaster = null;

    /**
     * Cast value to integer type
     * @param mixed $input
     * @param string|int[]|null $filter - pass array of known set for filtering by integer acceptable values
     * @return int|null
     */
    public static function toInt(
        mixed $input,
        string|array|null $filter = null
    ): ?int {
        self::$intCaster = self::$intCaster ?? IntCaster::new();
        $knownSet = null;
        if (is_array($filter)) {
            $knownSet = $filter;
            $filter = self::$intCaster->getDefaultFilter();
        }
        $output = self::$intCaster->toInt($input, $filter, $knownSet);
        return $output;
    }

    /**
     * Cast value to float type
     * @param mixed $input
     * @param string|float[]|null $filter - pass array of known set for filtering by float acceptable values
     * @return float|null
     */
    public static function toFloat(
        mixed $input,
        string|array|null $filter = null
    ): ?float {
        self::$floatCaster = self::$floatCaster ?? FloatCaster::new();
        $knownSet = null;
        if (is_array($filter)) {
            $knownSet = $filter;
            $filter = self::$floatCaster->getDefaultFilter();
        }
        $output = self::$floatCaster->toFloat($input, $filter, $knownSet);
        return $output;
    }

    /**
     * Cast value to string type.
     * ATTENTION: we apply F_STRING_TRIM filter by default, because it is most common desired behavior.
     * We don't convert array to json string, array is not expected value for string normalization.
     * @param mixed $input
     * @param string|string[]|null $filter - pass array of known set for filtering by string acceptable values
     * @return string|null
     */
    public static function toString(
        mixed $input,
        string|array|null $filter = null
    ): ?string {
        self::$stringCaster = self::$stringCaster ?? StringCaster::new();
        $knownSet = null;
        if (is_array($filter)) {
            $knownSet = $filter;
            $filter = self::$stringCaster->getDefaultFilter();
        }
        $output = self::$stringCaster->toString($input, $filter, $knownSet);
        return $output;
    }

    /**
     * Cast value to boolean type
     * @param mixed $input
     * @param string|bool[]|null $filter null means apply default filter
     * @return bool|null
     */
    public static function toBool(
        mixed $input,
        string|array|null $filter = null
    ): ?bool {
        self::$boolCaster = self::$boolCaster ?? BoolCaster::new();
        $output = self::$boolCaster->toBool($input, $filter);
        return $output;
    }
}
