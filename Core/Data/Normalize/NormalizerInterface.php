<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\Normalize;

/**
 * This interface describes methods for checking if an input string can be converted to PHP type
 * and methods for converting to PHP type
 *
 * Interface NormalizerInterface
 * @package Sam\Core\Data\Normalize
 */
interface NormalizerInterface
{
    /**
     * Check if string can be converted to bool
     *
     * @param string $value
     * @return bool
     */
    public function isBoolable(string $value): bool;

    /**
     * Check if string can be converted to int
     *
     * @param string $value
     * @return bool
     */
    public function isInt(string $value): bool;

    /**
     * Check if string can be converted to int that is greater than 0
     *
     * @param string $value
     * @return bool
     */
    public function isIntPositive(string $value): bool;

    /**
     * Check if string can be converted to int that is greater than or equal to 0
     *
     * @param string $value
     * @return bool
     */
    public function isIntPositiveOrZero(string $value): bool;

    /**
     * Check if string can be converted to float
     *
     * @param string $value
     * @return bool
     */
    public function isFloat(string $value): bool;

    /**
     * Check if string can be converted to float that is greater than 0
     *
     * @param string $value
     * @return bool
     */
    public function isFloatPositive(string $value): bool;

    /**
     * Check if string can be converted to array
     *
     * @param mixed $value
     * @return bool
     */
    public function isList(mixed $value): bool;

    /**
     * Convert string to bool
     *
     * @param string $value
     * @return bool
     */
    public function toBool(string $value): bool;

    /**
     * Convert string to int
     *
     * @param string $value
     * @return int
     */
    public function toInt(string $value): int;

    /**
     * Convert string to float
     *
     * @param string $value
     * @return float
     */
    public function toFloat(string $value): float;

    /**
     * Convert string to file name
     *
     * @param string $value
     * @return string
     */
    public function toFilename(string $value): string;

    /**
     * Convert string to array
     *
     * @param mixed $value
     * @return array
     */
    public function toList(mixed $value): array;
}
