<?php
/**
 * Store state of visitor connection session data in files.
 * It is based on native session id, thus php session still should be started, we want to identify anonymous users some way.
 * Address session data items individually by key and separately by files,
 * because we want to avoid native php session way, that stores all state in single file.
 *
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         $Id$
 * @since           Sep 29, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Cache\File\FileSession;

use Sam\Cache\File\FilesystemCacheManager;

/**
 * Class FileSessionCacher
 */
class FileSessionCacher extends FilesystemCacheManager
{
    /** @var string */
    protected string $extension = 'txt';
    /** @var string */
    protected string $key = self::EXT_TXT;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Determine namespace, currently by native php session id.
     * Because we want to cover anonymous users as well.
     * @return string
     */
    protected function detectNamespace(): string
    {
        $namespace = null;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $namespace = session_id();
        }
        if (!$namespace) {
            throw new \RuntimeException("Cannot find namespace by session id, because native PHP session has not been started");
        }
        return $namespace;
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        if (!$this->namespace) {
            $this->namespace = $this->detectNamespace();
        }
        return $this->namespace;
    }

    /**
     * @return string
     */
    protected function getCacheBasePath(): string
    {
        if (!$this->cacheBasePath) {
            $this->cacheBasePath = $this->cfg()->get('core->cache->sessionFile->path');
        }
        return $this->cacheBasePath;
    }

    /**
     * @return string
     */
    protected function getFilenameTransformation(): string
    {
        if (!$this->filenameTransformation) {
            $this->filenameTransformation = $this->cfg()->get('core->cache->sessionFile->filenameTransformation');
        }
        return $this->filenameTransformation;
    }

    /**
     * @return int
     */
    protected function getGzipLevel(): int
    {
        if ($this->gzipLevel === null) {
            $this->gzipLevel = $this->cfg()->get('core->cache->sessionFile->gzipLevel');
        }
        return $this->gzipLevel;
    }

    /**
     * @return int
     */
    protected function getDefaultTtl(): int
    {
        if ($this->defaultTtl === null) {
            $this->defaultTtl = $this->cfg()->get('core->cache->sessionFile->ttl');
        }
        return $this->defaultTtl;
    }
}
