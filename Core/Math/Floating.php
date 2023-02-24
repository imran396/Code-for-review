<?php
/**
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 02, 2015
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Math;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class to compare floats rounding to precision
 *
 * Class Floating
 * @package Sam\Core\Math
 */
class Floating
{
    private static ?int $precision = null;

    /**
     * Compare two floats for equality
     * Wrapper for _eq($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x = $y
     */
    public static function eq(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $isEqual = round($x, $precision) === round($y, $precision);
        return $isEqual;
    }

    /**
     * Compare whether two floats are not equal
     * Wrapper for _neq($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x != $y
     */
    public static function neq(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $notEqual = round($x, $precision) !== round($y, $precision);
        return $notEqual;
    }

    /**
     * Compare whether a float is greater than the other
     * Wrapper for _gt($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x > $y
     */
    public static function gt(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $isGreater = round($x, $precision) > round($y, $precision);
        return $isGreater;
    }

    /**
     * Compare whether a float is less than the other
     * Wrapper for _lt($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x < $y
     */
    public static function lt(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $isLess = round($x, $precision) < round($y, $precision);
        return $isLess;
    }

    /**
     * Compare whether one float is greater or equal than the other
     * Wrapper for _gteq($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x >= $y
     */
    public static function gteq(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $isGreaterOrEqual = round($x, $precision) >= round($y, $precision);
        return $isGreaterOrEqual;
    }

    /**
     * Compare whether on float is less or equal than the other
     * Wrapper for _lteq($x, $y)
     *
     * @param float|int|string|null $x
     * @param float|int|string|null $y
     * @param int|null $precision
     * @return bool $x <= $y
     */
    public static function lteq(float|int|string|null $x, float|int|string|null $y, ?int $precision = null): bool
    {
        $x = (float)$x;
        $y = (float)$y;
        $precision = $precision ?? self::getPrecision();
        $isLowerOrEqual = round($x, $precision) <= round($y, $precision);
        return $isLowerOrEqual;
    }

    /**
     * Check if value is between min and max values inclusively (ie. can be equal to any value).
     * @param float|int|string|null $value
     * @param float|int|string|null $min
     * @param float|int|string|null $max
     * @param int|null $precision
     * @return bool
     */
    public static function between(float|int|string|null $value, float|int|string|null $min, float|int|string|null $max, ?int $precision = null): bool
    {
        $precision = $precision ?? self::getPrecision();
        $value = round((float)$value, $precision);
        $min = round((float)$min, $precision);
        $max = round((float)$max, $precision);
        return self::lteq($min, $value) && self::lteq($value, $max);
    }

    /**
     * Round according internal precision for calculation and comparison.
     * @param float|int|string|null $value
     * @return float
     */
    public static function roundCalc(float|int|string|null $value): float
    {
        return round($value, self::getPrecision());
    }

    /**
     * Round value for final purpose of rendering or output to external services.
     * @param float|int|string|null $value
     * @return float
     */
    public static function roundOutput(float|int|string|null $value): float
    {
        return round((float)$value, 2);
    }

    /**
     * Set precision for comparisons
     *
     * @param int|null $precision
     */
    public static function setPrecision(?int $precision): void
    {
        self::$precision = Cast::toInt($precision, Constants\Type::F_INT_POSITIVE);
    }

    /**
     * @return int - float precision on which we compare
     */
    private static function getPrecision(): int
    {
        if (self::$precision === null) {
            self::$precision = ConfigRepository::getInstance()->get('core->general->floatPrecisionCompare');
        }
        return self::$precision;
    }
}
