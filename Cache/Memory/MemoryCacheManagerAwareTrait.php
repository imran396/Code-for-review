<?php
/**
 * Trait adds logic for memory caching
 *
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

namespace Sam\Cache\Memory;

/**
 * Trait MemoryCacheManagerAwareTrait
 * @package Sam\Cache
 */
trait MemoryCacheManagerAwareTrait
{
    protected ?MemoryCacheManager $memoryCacheManager = null;

    /**
     * @return MemoryCacheManager
     */
    protected function getMemoryCacheManager(): MemoryCacheManager
    {
        if ($this->memoryCacheManager === null) {
            $this->memoryCacheManager = MemoryCacheManager::new();
        }
        return $this->memoryCacheManager;
    }

    /**
     * @param MemoryCacheManager $memoryCacheManager
     * @return static
     * @internal
     */
    public function setMemoryCacheManager(MemoryCacheManager $memoryCacheManager): static
    {
        $this->memoryCacheManager = $memoryCacheManager;
        return $this;
    }
}
