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

namespace Sam\Storage\Entity\Cache;

/**
 * Trait EntityMemoryCacheManagerAwareTrait
 * @package Sam\Storage\Entity\Cache
 */
trait EntityMemoryCacheManagerAwareTrait
{
    protected ?EntityMemoryCacheManager $entityMemoryCacheManager = null;

    /**
     * @return EntityMemoryCacheManager
     */
    protected function getEntityMemoryCacheManager(): EntityMemoryCacheManager
    {
        if ($this->entityMemoryCacheManager === null) {
            $this->entityMemoryCacheManager = EntityMemoryCacheManager::new();
        }
        return $this->entityMemoryCacheManager;
    }

    /**
     * @param EntityMemoryCacheManager $entityMemoryCacheManager
     * @return static
     * @internal
     */
    public function setEntityMemoryCacheManager(EntityMemoryCacheManager $entityMemoryCacheManager): static
    {
        $this->entityMemoryCacheManager = $entityMemoryCacheManager;
        return $this;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableEntityMemoryCacheManager(bool $enable): static
    {
        $this->getEntityMemoryCacheManager()->enable($enable);
        return $this;
    }
}
