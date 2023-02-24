<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/23/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\Normalize;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\File\FilePathHelperAwareTrait;

/**
 * Class NormalizerBase
 * @package Sam\Core\Data\Normalize
 */
abstract class NormalizerBase extends CustomizableClass implements NormalizerInterface
{
    use FilePathHelperAwareTrait;

    /**
     * @inheritDoc
     */
    public function isBoolable(string $value): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isInt(string $value): bool
    {
        return NumberValidator::new()->isInt($value);
    }

    /**
     * @inheritDoc
     */
    public function isFloat(string $value): bool
    {
        return NumberValidator::new()->isReal($value);
    }

    /**
     * @inheritDoc
     */
    public function isFloatPositive(string $value): bool
    {
        return NumberValidator::new()->isRealPositive($value);
    }

    /**
     * @inheritDoc
     */
    public function isIntPositive(string $value): bool
    {
        return NumberValidator::new()->isIntPositive($value);
    }

    /**
     * @inheritDoc
     */
    public function isIntPositiveOrZero(string $value): bool
    {
        return NumberValidator::new()->isIntPositiveOrZero($value);
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
    public function toBool(string $value): bool
    {
        if (!$this->isBoolable($value)) {
            throw new \LogicException(
                'Value does not correspond bool type for web input'
                . composeSuffix(['value' => $value])
            );
        }
        return (bool)$value;
    }

    /**
     * @inheritDoc
     */
    public function toInt(string $value): int
    {
        return (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function toFloat(string $value): float
    {
        return (float)$value;
    }

    /**
     * @inheritDoc
     */
    public function toFilename(string $value): string
    {
        return $this->getFilePathHelper()->toFilename($value);
    }

    /**
     * @inheritDoc
     */
    public function toList(mixed $value): array
    {
        if (!$this->isList($value)) {
            throw new \LogicException(
                'Value does not correspond array type'
                . composeSuffix(['value' => $value])
            );
        }

        if ($value === '') {
            return [];
        }

        return $value;
    }
}

