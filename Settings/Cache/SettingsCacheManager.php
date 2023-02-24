<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Cache;

use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Repository\SettingsRepositoryProviderCreateTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class SettingsCacheManager
 * @package Sam\Settings\Cache
 */
class SettingsCacheManager extends CustomizableClass
{
    use EntityMemoryCacheManagerAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use SettingsRepositoryProviderCreateTrait;

    /**
     * Keep parameters alive in file cache for 1 mins.
     * @var int
     */
    protected int $fileCacheTtl = 60;

    /**
     * Keep parameters alive in memory cache for 5 seconds.
     * @var int
     */
    protected int $memoryCacheTtl = 5;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->getFilesystemCacheManager()
            ->setNamespace('system_parameter')
            ->setExtension(FilesystemCacheManager::EXT_TXT);
        return $this;
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @param int $accountId
     * @return T|null
     */
    public function get(string $className, int $accountId): ?object
    {
        $fileCacheKey = $this->makeFileCacheKey($className, $accountId);
        $memoryCacheKey = $this->makeMemoryCacheKey($className, $accountId);
        /**
         * Load data from memory. It may be stale.
         */
        $entityInMemory = $this->getEntityMemoryCacheManager()->get($memoryCacheKey);
        /**
         * Check presence of file cache.
         */
        if ($entityInMemory) {
            if (!$this->getFilesystemCacheManager()->has($fileCacheKey)) {
                /**
                 * Absence of file cache means it was dropped by parallel process, because of state update in DB.
                 * This means, we should reload memory cache as well.
                 */
                return null;
            }
            return $entityInMemory;
        }

        $entityInFile = $this->getFilesystemCacheManager()->get($fileCacheKey);
        if ($entityInFile) {
            // Save to memory for quick access
            $this->getEntityMemoryCacheManager()->set($memoryCacheKey, $entityInFile, $this->memoryCacheTtl);
        }
        return $entityInFile;
    }

    public function set(string $className, int $accountId, object $entity): void
    {
        // TODO: detect TTL externally
        $memoryCacheKey = $this->makeMemoryCacheKey($className, $accountId);
        $this->getEntityMemoryCacheManager()->set($memoryCacheKey, $entity, $this->memoryCacheTtl);
        $fileCacheKey = $this->makeFileCacheKey($className, $accountId);
        $this->getFilesystemCacheManager()->set($fileCacheKey, $entity, $this->fileCacheTtl);
    }

    public function delete(string $className, int $accountId): void
    {
        $this->getEntityMemoryCacheManager()->deleteEntity($this->makeMemoryCacheKey($className, $accountId));
        $this->getFilesystemCacheManager()->delete($this->makeFileCacheKey($className, $accountId));
    }

    public function clear(int $accountId): void
    {
        foreach ($this->createSettingsRepositoryProvider()->getSettingsEntityClassNames() as $className) {
            $this->delete($className, $accountId);
        }
    }

    public function setFileCacheTtl(int $fileCacheTtl): static
    {
        $this->fileCacheTtl = $fileCacheTtl;
        return $this;
    }

    public function setMemoryCacheTtl(int $memoryCacheTtl): static
    {
        $this->memoryCacheTtl = $memoryCacheTtl;
        return $this;
    }

    /**
     * @param string $className
     * @param int $accountId
     * @return string
     */
    protected function makeMemoryCacheKey(string $className, int $accountId): string
    {
        return $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::SETTINGS_OBJECT_ACCOUNT_ID, [$className, $accountId]);
    }

    /**
     * @param string $className
     * @param int $accountId
     * @return string
     */
    protected function makeFileCacheKey(string $className, int $accountId): string
    {
        return sprintf(Constants\FileCache::SETTINGS_OBJECT_ACCOUNT_ID, $className, $accountId);
    }
}
