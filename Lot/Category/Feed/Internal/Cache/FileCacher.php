<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Cache;

use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

/**
 * Class Cache
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
class FileCacher extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use CurrentDateTrait;

    /**
     * @var string
     */
    protected string $key;
    /**
     * @var int
     */
    protected int $ttl;
    /**
     * @var bool
     */
    protected bool $isProfilingEnabled;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $requestData, int $ttl, bool $enableProfiling = false): static
    {
        $this->key = $this->makeCacheKey($requestData);
        $this->ttl = $ttl;
        $this->isProfilingEnabled = $enableProfiling;

        $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('feed')
            ->setDefaultTtl($ttl);
        return $this;
    }

    public function isFresh(): bool
    {
        $modificationTime = $this->getModificationTime();
        if (!$modificationTime) {
            log_debug('No time modified ');
            return false;
        }

        if ($this->getCurrentDateUtc()->getTimestamp() > $modificationTime + $this->ttl) {
            log_debug('Cached data expired');
            return false;
        }

        return true;
    }

    public function getModificationTime(): int
    {
        $modificationTime = $this->getFilesystemCacheManager()->getFileMtime($this->key);
        return $modificationTime ?: 0;
    }

    /**
     * @param string $data serializable value
     */
    public function set(string $data): void
    {
        if ($this->ttl > 0) {
            $ts = microtime(true);
            $this->getFilesystemCacheManager()->set($this->key, $data);
            if ($this->isProfilingEnabled) {
                log_debug(composeSuffix(['save to cache' => ((microtime(true) - $ts) * 1000) . 'ms']));
            }
        }
    }

    public function get(): ?string
    {
        $ts = microtime(true);
        $result = $this->getFilesystemCacheManager()->get($this->key);
        if ($this->isProfilingEnabled) {
            log_debug(composeSuffix(['read from cache' => ((microtime(true) - $ts) * 1000) . 'ms']));
        }
        return $result;
    }

    /**
     * Generate a CacheKey out of the POST or GET request
     * @param array $requestData
     * @return string cache key
     */
    protected function makeCacheKey(array $requestData): string
    {
        return md5(strtolower(trim(var_export($requestData, true))));
    }
}
