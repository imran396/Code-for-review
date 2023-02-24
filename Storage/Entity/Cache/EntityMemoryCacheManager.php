<?php
/**
 * General manager for caching in memory
 * It implement PSR-16 interface except $ttl argument, that is set in _configuration/core.php
 * zend-cache doesn't allow set individual TTL value for each cached value
 *
 * SAM-4879: Memory Cache Management
 * SAM-4032: Apply Zend\Cache
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

use Sam\Cache\Memory\MemoryCacheManager;
use Sam\Core\Filter\Conformity\FilterConformityCheckerAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;

/**
 * Class EntityMemoryCacheManager
 * @package Sam\Storage\Entity\Cache
 */
class EntityMemoryCacheManager extends MemoryCacheManager
{
    use FilterConformityCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $entityKey
     * @param callable $loadFn
     * @param FilterDescriptor[] $filterDescriptors
     * @return mixed
     */
    public function loadWithFilterConformityCheck(string $entityKey, callable $loadFn, array $filterDescriptors): mixed
    {
        $filterKey = $this->makeEntityFilterCacheKey($entityKey);
        $cachedFilterDescriptors = $this->get($filterKey);
        $isMet = $this->getFilterConformityChecker()->conform($filterDescriptors, $cachedFilterDescriptors);
        if ($isMet) {
            $result = $this->load($entityKey, $loadFn);
        } else {
            $result = $loadFn();
            // ll($entityKey . ' loaded from db' . ($result === null ? ', but NULL result' : ''));
            $this->set($entityKey, $result);
            $this->set($filterKey, $filterDescriptors);
        }
        return $result;
    }

    /**
     * @param string $entityKey
     * @param callable $loadFn
     * @param FilterDescriptor[] $filterDescription
     * @return bool
     */
    public function existWithFilterConformityCheck(string $entityKey, callable $loadFn, array $filterDescription): bool
    {
        $existenceKey = $this->makeEntityExistenceCacheKey($entityKey);
        $isFound = (bool)$this->loadWithFilterConformityCheck($existenceKey, $loadFn, $filterDescription);
        return $isFound;
    }

    /**
     * @param string $entityKey
     */
    public function deleteEntity(string $entityKey): void
    {
        $this->delete($entityKey);
        $filterKey = $this->makeEntityFilterCacheKey($entityKey);
        $this->delete($filterKey);
        $existenceKey = $this->makeEntityExistenceCacheKey($entityKey);
        $this->delete($existenceKey);
    }

    /**
     * @param string $cacheKey
     * @param mixed $arguments
     * @return string
     */
    public function makeEntityCacheKey(string $cacheKey, mixed $arguments = null): string
    {
        $entityKey = vsprintf($this->normalizeKey($cacheKey), (array)$arguments);
        return $entityKey;
    }

    /**
     * @param string $entityKey
     * @return string
     */
    public function makeEntityFilterCacheKey(string $entityKey): string
    {
        $filterKey = $this->normalizeKey($entityKey . '-Filter');
        return $filterKey;
    }

    /**
     * @param string $entityKey
     * @return string
     */
    public function makeEntityExistenceCacheKey(string $entityKey): string
    {
        $filterKey = $this->normalizeKey($entityKey . '-Exist');
        return $filterKey;
    }
}
