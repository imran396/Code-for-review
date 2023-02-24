<?php
/**
 * File caching of feed content
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 29, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Feed;

use InvalidArgumentException;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\File\FileSizeRenderer;
use Sam\Infrastructure\Net\HttpCacheManagerCreateTrait;

/**
 * Class CacheManager
 * @package Sam\Details
 */
class CacheManager extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use HttpCacheManagerCreateTrait;

    protected ?string $cacheKey = null;
    /**
     * Live time in seconds of cached feed content
     */
    protected ?int $cacheTimeout = null;
    protected bool $isProfiling = false;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function setCacheTimeout(int $timeout): static
    {
        $this->cacheTimeout = $timeout;
        return $this;
    }

    public function enableProfiling(bool $enabled): static
    {
        $this->isProfiling = $enabled;
        return $this;
    }

    public function sendCached(): bool
    {
        if (!$this->cacheTimeout) {
            return false;
        }

        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('feed');

        $modificationTime = $cacheManager->getFileMtime($this->getCacheKey());
        if (!$modificationTime) {
            log_debug('No time modified ');
            return false;
        }

        $tsStart = time();
        if ($tsStart > $modificationTime + $this->cacheTimeout) {
            log_debug('Cached data expired');
            return false;
        }

        log_debug('doConditionalGet');
        $this->createHttpCacheManager()->sendHeadersAndExitIfNotModified($modificationTime);

        $ts = microtime(true);
        $output = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('feed')
            ->get($this->getCacheKey());
        if ($output) {
            echo $output;
        }

        $message = $output !== null
            ? FileSizeRenderer::new()->renderHumanReadableSize((int)$output)
            : 'nothing';
        log_debug("{$message} from cache");
        if ($this->isProfiling) {
            log_debug(composeSuffix(['read from cache' => ((microtime(true) - $ts) * 1000) . 'ms']));
        }
        return $output !== null;
    }

    public function saveCache(string $content): void
    {
        $ts = microtime(true);
        if ($this->cacheTimeout > 0) {
            $this->getFilesystemCacheManager()
                ->setExtension('txt')
                ->setNamespace('feed')
                ->set($this->getCacheKey(), $content);
        }
        if ($this->isProfiling) {
            log_debug(composeSuffix(['save to cache' => ((microtime(true) - $ts) * 1000) . 'ms']));
        }
    }

    /**
     * Generate a CacheKey out of the POST or GET request
     * @return string cache key
     */
    public function getCacheKey(): string
    {
        // if (!$this->cacheKey) {
        //     // // TODO: should be based on options
        //     // $requestParams = isset($_SERVER['REQUEST_METHOD'])
        //     //     && strtolower($_SERVER['REQUEST_METHOD']) === 'post'
        //     //     ? $_POST : $_GET;
        //     // $this->cacheKey = md5(trim(strtolower(var_export($requestParams, true))));
        //     $this->cacheKey =
        // }
        if (!$this->cacheKey) {
            throw new InvalidArgumentException("Cache Key unknown");
        }
        return $this->cacheKey;
    }

    public function setCacheKey(string $cacheKey): static
    {
        $this->cacheKey = trim($cacheKey);
        return $this;
    }
}
