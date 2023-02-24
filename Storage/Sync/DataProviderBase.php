<?php
/**
 * Parent class for data sync providers (currently only for Auction data provider)
 * SAM-2978: Improve auction / lot data sync scripts
 *
 * @copyright  2018 Bidpath, Inc.
 * @author
 * @package    com.swb.sam2
 * @version    $Id$
 * @since
 * @copyright  Copyright 2018 by Bidpath, Inc. All rights reserved.
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Storage\Sync;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Database\SimpleMysqliDatabase;
use Sam\Storage\Database\SimpleMysqliDatabaseAwareTrait;

/**
 * Class DataProviderBase
 * @package Sam\Storage\Sync
 */
abstract class DataProviderBase extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SimpleMysqliDatabaseAwareTrait;

    protected array $auctionData = [];
    protected ?FilesystemCacheManager $cacheManager = null;
    protected ?SimpleMysqliDatabase $db;
    protected bool $isProfiling = false;
    protected ?int $ttl = null;
    protected ?string $key = null;
    protected ?string $cacheNamespace = null;

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
        $this->db = $this->getSimpleMysqliDatabase()->construct($this->isProfiling);
        parent::initInstance();
        return $this;
    }

    /**
     * Run the thing
     */
    abstract public function run();

    /**
     * Render the results array to the browser
     *
     */
    protected function render(): void
    {
        $tmpTS = microtime(true);
        $output = json_encode($this->auctionData);
        if ($this->isProfiling()) {
            error_log('json encode: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }

        $tmpTS = microtime(true);
        echo $output;
        if ($this->isProfiling()) {
            error_log('send: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }

        if (!$this->ttl) {
            // Don't cache, when lifetime is undefined or 0
            return;
        }
        $this->getCacheManager()->set($this->getCacheKey(), $output, $this->ttl);
    }

    /**
     * Setter for Profile attribute
     * @param bool $isProfiling
     * @return static
     */
    public function enableProfiling(bool $isProfiling): static
    {
        $this->isProfiling = $isProfiling;
        return $this;
    }

    /**
     * Getter for Profile attribute
     * @return bool
     */
    public function isProfiling(): bool
    {
        return $this->isProfiling;
    }

    /**
     * @param FilesystemCacheManager $cacheManager
     * @return static
     */
    public function setCacheManager(FilesystemCacheManager $cacheManager): static
    {
        $this->cacheManager = $cacheManager;
        return $this;
    }

    /**
     * @return FilesystemCacheManager
     */
    public function getCacheManager(): FilesystemCacheManager
    {
        if ($this->cacheManager === null) {
            $this->cacheManager = $this->getFilesystemCacheManager()
                ->setExtension('json')
                ->setNamespace($this->cacheNamespace);
        }
        return $this->cacheManager;
    }

    /**
     * Hook for pre iteration processing
     *
     * @param array $rows query row result
     * @param array $data current result data array
     * @return array $data
     * @noinspection PhpUnusedParameterInspection
     */
    protected function onPreIteration(array $rows, array $data): array
    {
        return $data;
    }

    /**
     * Hook for post iteration processing
     *
     * @param array $rows query row result
     * @param array $data current result data array
     * @return array $data
     * @noinspection PhpUnusedParameterInspection
     */
    protected function onPostIteration(array $rows, array $data): array
    {
        return $data;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        if (!isset($this->key)) {
            if ($this->getServerRequestReader()->isPost()) {
                $params = $this->getParamFetcherForPost()->getParameters();
            } else {
                $params = $this->getParamFetcherForGet()->getParameters();
            }
            $this->key = md5(strtolower(trim(var_export($params, true))));
        }
        return $this->key;
    }
}
