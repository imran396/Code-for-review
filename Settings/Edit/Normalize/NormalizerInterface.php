<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Normalize;

/**
 * Interface NormalizerInterface
 * @package Sam\Settings\Edit\Normalize
 */
interface NormalizerInterface
{
    /**
     * @param string $property
     * @param mixed $value
     * @return mixed
     */
    public function normalize(string $property, mixed $value): mixed;

    /**
     * @param string $value
     * @return bool
     */
    public function isInteger(string $value): bool;

    /**
     * @param string $value
     * @return int
     */
    public function toInteger(string $value): int;

    /**
     * @param string $value
     * @return bool
     */
    public function isFloat(string $value): bool;

    /**
     * @param string $value
     * @return float
     */
    public function toFloat(string $value): float;

    /**
     * @param string $value
     * @return bool
     */
    public function isBoolean(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public function toBoolean(string $value): bool;

    /**
     * @param mixed $value
     * @return bool
     */
    public function isList(mixed $value): bool;

    /**
     * @param mixed $value
     * @return array
     */
    public function toList(mixed $value): array;
}
