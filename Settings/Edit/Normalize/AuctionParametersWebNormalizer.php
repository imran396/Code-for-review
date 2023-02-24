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

use LogicException;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class AuctionParametersWebNormalizer
 * @package Sam\Settings\Edit\Normalize
 */
class AuctionParametersWebNormalizer extends AuctionParametersNormalizerBase
{
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function isInteger(string $value): bool
    {
        $value = $this->getNumberFormatter()->removeFormat($value);
        return is_numeric($value) && !str_contains($value, '.');
    }

    /**
     * @inheritDoc
     */
    public function toInteger(string $value): int
    {
        if (!$this->isInteger($value)) {
            throw new LogicException(
                'Value does not correspond integer type for web input'
                . composeSuffix(['value' => $value])
            );
        }
        $value = $this->getNumberFormatter()->removeFormat($value);
        return (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function isFloat(string $value): bool
    {
        $value = $this->getNumberFormatter()->removeFormat($value);
        return is_numeric($value);
    }

    /**
     * @inheritDoc
     */
    public function toFloat(string $value): float
    {
        if (!$this->isFloat($value)) {
            throw new LogicException(
                'Value does not correspond float type for web input'
                . composeSuffix(['value' => $value])
            );
        }
        $value = $this->getNumberFormatter()->removeFormat($value);
        return (float)$value;
    }

    /**
     * @inheritDoc
     */
    public function isBoolean(string $value): bool
    {
        return in_array($value, [1, '1', 'on', 0, '0', 'off', null], true);
    }

    /**
     * @inheritDoc
     */
    public function toBoolean(string $value): bool
    {
        if (!$this->isBoolean($value)) {
            throw new LogicException(
                'Value does not correspond boolean type for web input'
                . composeSuffix(['value' => $value])
            );
        }

        if (in_array($value, [1, '1', 'on'], true)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isList(mixed $value): bool
    {
        return is_array($value);
    }

    /**
     * @inheritDoc
     */
    public function toList(mixed $value): array
    {
        if (!$this->isList($value)) {
            throw new LogicException(
                'Value does not correspond array type for web input'
                . composeSuffix(['value' => $value])
            );
        }

        if ($value === '') {
            return [];
        }

        return $value;
    }
}
