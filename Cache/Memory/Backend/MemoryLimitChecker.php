<?php
/** @noinspection PhpMissingBreakStatementInspection */

/**
 * Serve memory limit checking, normalization, store
 *
 * SAM-5188: Apply symfony memory cache
 * SAM-4879: Memory Cache Management
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Cache\Memory\Backend;

use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;

/**
 * Class MemoryLimitManager
 * @package Sam\Cache\Memory
 */
class MemoryLimitChecker extends CustomizableClass
{
    /** @var int|null */
    protected int|null $memoryLimit = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function getMemoryLimit(): int
    {
        if ($this->memoryLimit === null) {
            // By default use half of PHP's memory limit if possible
            $memoryLimitIni = ini_get('memory_limit');
            $memoryLimit = $this->normalizeMemoryLimit($memoryLimitIni);
            if ($memoryLimit >= 0) {
                $this->memoryLimit = (int)($memoryLimit / 2);
            } else {
                // disable memory limit
                $this->memoryLimit = 0;
            }
        }
        return $this->memoryLimit;
    }

    /**
     * @param int|string $memoryLimit
     * @return static
     */
    public function setMemoryLimit(int|string $memoryLimit): static
    {
        $this->memoryLimit = $this->normalizeMemoryLimit($memoryLimit);
        return $this;
    }

    /**
     * Has space available to store items?
     *
     * @return bool
     */
    public function hasAvailableSpace(): bool
    {
        $limit = $this->getMemoryLimit();

        // check memory limit disabled
        if ($limit <= 0) {
            return true;
        }

        $has = ($limit - (float)memory_get_usage(true) > 0);
        // ll(composeSuffix(['limit' => $limit , 'memory_get_usage' => (float)memory_get_usage(true), 'has' => $has, 'memory_get_peak_usage' => (float)memory_get_peak_usage(true)]));
        return $has;
    }

    /**
     * Normalized a given value of memory limit into the number of bytes
     *
     * @param string|int $value
     * @return int
     * @throws InvalidArgumentException
     */
    protected function normalizeMemoryLimit(int|string $value): int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }

        if (!preg_match('/(-?\d+)\s*(\w*)/', $value, $matches)) {
            throw new InvalidArgumentException("Invalid memory limit '{$value}'");
        }

        $value = (int)$matches[1];
        if ($value <= 0) {
            return 0;
        }

        switch (strtoupper($matches[2])) {
            case 'G':
                $value *= 1024;
            // no break

            case 'M':
                $value *= 1024;
            // no break

            case 'K':
                $value *= 1024;
            // no break
        }
        return $value;
    }
}
