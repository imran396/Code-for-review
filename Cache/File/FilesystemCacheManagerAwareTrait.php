<?php

namespace Sam\Cache\File;

/**
 * Trait FilesystemCacheManagerAwareTrait
 * @package Sam\Cache
 */
trait FilesystemCacheManagerAwareTrait
{
    /**
     * @var FilesystemCacheManager|null
     */
    protected ?FilesystemCacheManager $filesystemCacheManager = null;

    /**
     * @return FilesystemCacheManager
     */
    protected function getFilesystemCacheManager(): FilesystemCacheManager
    {
        if ($this->filesystemCacheManager === null) {
            $this->filesystemCacheManager = FilesystemCacheManager::new();
        }
        return $this->filesystemCacheManager;
    }

    /**
     * @param FilesystemCacheManager $filesystemCacheManager
     * @return static
     * @internal
     */
    public function setFilesystemCacheManager(FilesystemCacheManager $filesystemCacheManager): static
    {
        $this->filesystemCacheManager = $filesystemCacheManager;
        return $this;
    }
}
