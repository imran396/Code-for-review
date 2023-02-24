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

use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Normalizer
 * @package
 */
class WebNormalizer extends NormalizerBase
{
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $systemAccountId): WebNormalizer
    {
        $this->getNumberFormatter()->construct($systemAccountId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isBoolable(string $value): bool
    {
        return in_array($value, ['', 'on']);
    }

    /**
     * @inheritDoc
     */
    public function isInt(string $value): bool
    {
        return parent::isInt($this->removeFormat($value));
    }

    /**
     * @inheritDoc
     */
    public function isIntPositive(string $value): bool
    {
        return parent::isIntPositive($this->removeFormat($value));
    }

    /**
     * @inheritDoc
     */
    public function isIntPositiveOrZero(string $value): bool
    {
        return parent::isIntPositiveOrZero($this->removeFormat($value));
    }

    /**
     * @inheritDoc
     */
    public function toInt(string $value): int
    {
        return (int)$this->removeFormat($value);
    }

    /**
     * @inheritDoc
     */
    public function toFloat(string $value): float
    {
        return (float)$this->removeFormat($value);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function removeFormat(string $value): string
    {
        return $this->getNumberFormatter()->removeFormat($value);
    }
}
