<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\Normalize;

use LogicException;

/**
 * This class implements methods for converting CLI input to PHP type
 *
 * Class CliNormalizer
 * @package Sam\Core\Data\Normalize
 */
class CliNormalizer extends NormalizerBase
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
     * @inheritDoc
     */
    public function isBoolable(string $value): bool
    {
        $value = strtolower($value);
        return in_array($value, ['true', 'false', '1', '0', 1, 0], true);
    }

    /**
     * @inheritDoc
     */
    public function toBool(string $value): bool
    {
        if (!$this->isBoolable($value)) {
            throw new LogicException(
                'Value does not correspond boolean type for CLI input'
                . composeSuffix(['value' => $value])
            );
        }

        $value = strtolower($value);
        if (in_array($value, [1, '1', 'true'], true)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isList(mixed $value): bool
    {
        return is_array($value) || is_string($value);
    }

    /**
     * @inheritDoc
     */
    public function toList(mixed $value): array
    {
        if (!$this->isList($value)) {
            throw new LogicException(
                'Value does not correspond array type for CLI input'
                . composeSuffix(['value' => $value])
            );
        }

        if ($value === '') {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $list = explode(',', $value);
        $list = array_map('trim', $list);
        return $list;
    }
}
