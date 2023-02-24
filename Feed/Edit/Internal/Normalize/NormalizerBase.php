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

namespace Sam\Feed\Edit\Internal\Normalize;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\File\FilePathHelperAwareTrait;

/**
 * Class NormalizerBase
 * @package
 */
abstract class NormalizerBase extends CustomizableClass
{
    use FilePathHelperAwareTrait;

    /**
     * @param string $value
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function isBoolable(string $value): bool
    {
        return true;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isInt(string $value): bool
    {
        return NumberValidator::new()->isInt($value);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isIntPositive(string $value): bool
    {
        return NumberValidator::new()->isIntPositive($value);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isIntPositiveOrZero(string $value): bool
    {
        return NumberValidator::new()->isIntPositiveOrZero($value);
    }

    /**
     * @param string $value
     * @return bool
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
     * @param string $value
     * @return int
     */
    public function toInt(string $value): int
    {
        return (int)$value;
    }

    /**
     * @param string $value
     * @return float
     */
    public function toFloat(string $value): float
    {
        return (float)$value;
    }

    /**
     * @param string $value
     * @return string
     */
    public function toFilename(string $value): string
    {
        return $this->getFilePathHelper()->toFilename($value);
    }
}
