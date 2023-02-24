<?php
/**
 * Enhanced version of ArrayCache adapter:
 * - garbage collection by memory limit check;
 * - prune expired values;
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

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class AdvancedArrayCache
 * @package Sam\Cache
 */
class AdvancedArrayPsr16Cache extends Psr16Cache
{
    protected ?MemoryLimitChecker $memoryLimitChecker = null;
    protected ArrayAdapter $cachePool;
    protected array $lastInvalidatedKeys = [];

    /**
     * @param int $defaultLifetime
     */
    public function __construct(int $defaultLifetime = 0)
    {
        $this->cachePool = new ArrayAdapter($defaultLifetime, false);
        parent::__construct($this->cachePool);
    }

    /**
     * Remove expired values from memory cache.
     * @return bool
     */
    public function prune(): bool
    {
        $invalidated = array_filter(
            $this->cachePool->getValues(),
            function ($key) {
                // checking for existence also removes expired value
                return !$this->cachePool->hasItem($key);
            },
            ARRAY_FILTER_USE_KEY
        );
        $this->lastInvalidatedKeys = array_keys($invalidated);
        return count($this->lastInvalidatedKeys) > 0;
    }

    /**
     * Return invalidated cache keys during previous prune() call
     * @return array
     */
    public function getLastInvalidatedKeys(): array
    {
        return $this->lastInvalidatedKeys;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null): mixed
    {
        $value = parent::get($key, $default);
        // $this->set($key, $value);
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $mlChecker = $this->getMemoryLimitChecker();
        if (!$mlChecker->hasAvailableSpace()) {
            $this->prune();
            if (!$mlChecker->hasAvailableSpace()) { // @phpstan-ignore-line
                $this->clear();
                log_debug(
                    "Memory usage exceeds, cache dropped"
                    . composeSuffix(['limit' => $mlChecker->getMemoryLimit()])
                );
            }
        }

        return parent::setMultiple($values, $ttl);
    }

    /**
     * @return MemoryLimitChecker
     */
    public function getMemoryLimitChecker(): MemoryLimitChecker
    {
        if ($this->memoryLimitChecker === null) {
            $this->memoryLimitChecker = MemoryLimitChecker::new();
        }
        return $this->memoryLimitChecker;
    }

    /**
     * @param MemoryLimitChecker $memoryLimitChecker
     * @return static
     */
    public function setMemoryLimitChecker(MemoryLimitChecker $memoryLimitChecker): static
    {
        $this->memoryLimitChecker = $memoryLimitChecker;
        return $this;
    }

    /**
     * @param int|string $memoryLimit
     * @return static
     */
    public function setMemoryLimit(int|string $memoryLimit): static
    {
        $this->getMemoryLimitChecker()->setMemoryLimit($memoryLimit);
        return $this;
    }
}
