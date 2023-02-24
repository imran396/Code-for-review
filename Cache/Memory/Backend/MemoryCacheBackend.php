<?php
/**
 * Singleton object for storing memory cached data
 *
 * SAM-5188: Apply symfony memory cache
 * SAM-4879: Memory Cache Management
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         $Id$
 * @since           Aug 4, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Cache\Memory\Backend;

use Sam\Core\Service\Singleton;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class MemoryCacheManager
 * @package Sam\Cache
 */
class MemoryCacheBackend extends Singleton
{
    use ConfigRepositoryAwareTrait;

    protected ?AdvancedArrayPsr16Cache $cache = null;
    protected ?int $defaultTtl = null;
    protected int|string|null $memoryLimit = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * @return AdvancedArrayPsr16Cache
     */
    public function getCache(): AdvancedArrayPsr16Cache
    {
        if ($this->cache === null) {
            $this->cache = new AdvancedArrayPsr16Cache($this->getDefaultTtl());
            $memoryLimit = $this->getMemoryLimit();
            if ($memoryLimit) {
                $this->cache->setMemoryLimit($memoryLimit);
            }
        }
        return $this->cache;
    }

    /**
     * @param AdvancedArrayPsr16Cache $cache
     * @return static
     */
    public function setCache(AdvancedArrayPsr16Cache $cache): static
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultTtl(): int
    {
        if ($this->defaultTtl === null) {
            $this->defaultTtl = $this->cfg()->get('core->cache->memory->adapter->options->ttl');
        }
        return $this->defaultTtl;
    }

    /**
     * @param int $ttl
     * @return static
     */
    public function setDefaultTtl(int $ttl): static
    {
        $this->defaultTtl = $ttl;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getMemoryLimit(): int|string|null
    {
        if ($this->memoryLimit === null) {
            $this->memoryLimit = $this->cfg()->get('core->cache->memory->adapter->options->memoryLimit');
        }
        return $this->memoryLimit;
    }

    /**
     * @param int|string|null $memoryLimit
     * @return static
     */
    public function setMemoryLimit(int|string|null $memoryLimit): static
    {
        $this->memoryLimit = $memoryLimit;
        return $this;
    }
}
