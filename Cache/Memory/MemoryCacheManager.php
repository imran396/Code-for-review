<?php
/**
 * General manager for caching in memory
 * It implement PSR-16 interface except $ttl argument, that is set in _configuration/core.php
 * zend-cache doesn't allow set individual TTL value for each cached value
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

namespace Sam\Cache\Memory;

use DateInterval;
use Exception;
use Psr\SimpleCache\CacheInterface;
use Sam\Cache\Memory\Backend\AdvancedArrayPsr16Cache;
use Sam\Cache\Memory\Backend\MemoryCacheBackend;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Class MemoryCacheManager
 * @package Sam\Cache
 */
class MemoryCacheManager extends CustomizableClass implements CacheInterface
{
    use ConfigRepositoryAwareTrait;

    /** @var bool|null */
    protected ?bool $isEnabled = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        if ($this->isEnabled === null) {
            $this->isEnabled = (bool)$this->cfg()->get('core->cache->memory->enabled');
        }
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return static
     */
    public function enable(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return AdvancedArrayPsr16Cache
     */
    public function getCache(): AdvancedArrayPsr16Cache
    {
        return $this->getBackend()->getCache();
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        if ($this->isEnabled()) {
            return $this->getCache()->clear();
        }
        return false;
    }

    /**
     * Clear expired data
     * @return string[]
     */
    public function clearExpired(): array
    {
        $invalidatedKeys = [];
        if ($this->isEnabled()) {
            $success = $this->getCache()->prune();
            $invalidatedKeys = $success ? $this->getCache()->getLastInvalidatedKeys() : [];
            // log_trace('Invalidated memory cache keys: ' . json_encode($invalidatedKeys));
        }
        return $invalidatedKeys;
    }

    /**
     * Normalize key to conform key format rules of symfony cache implementation
     * @param string $key
     * @return string
     */
    public function normalizeKey(string $key): string
    {
        $chars = preg_quote(ItemInterface::RESERVED_CHARACTERS, '/');
        $normalizedKey = preg_replace("/[{$chars}]/", '_', $key);
        if ($normalizedKey !== $key) {
            log_trace('Cache key changed after normalization' . composeSuffix(['input key' => $key, 'normalized' => $normalizedKey]));
        }
        return $normalizedKey;
    }

    /**
     * Normalize array of keys
     * @param array $keys
     * @return array
     */
    public function normalizeKeys(array $keys): array
    {
        foreach ($keys as $i => $key) {
            $keys[$i] = $this->normalizeKey($key);
        }
        return $keys;
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)
    {
        $result = $default;
        if (!$this->isEnabled()) {
            return $result;
        }

        $key = $this->normalizeKey($key);

        try {
            $data = $this->getCache()->get($key, $default);
            if ($data !== null) {
                $result = $data;
                // if (strpos($key, 'AuctionCache-AuctionId') !== false) {
                // ll($key . ' loaded from memory cache' . ($result === null ? ', but NULL result' : ''));
                // }
            }
        } catch (Exception $e) {
            $this->logException($e, $key);
        }
        return $result;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store.
     * @param mixed $value The value of the item to store, must be serializable.
     * @param null|int|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     * the driver supports TTL then the library may set a default value
     * for it or let the driver take care of that.
     * @return bool True on success and false on failure.
     */
    public function set($key, $value, $ttl = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $key = $this->normalizeKey($key);

        try {
            $success = $this->getCache()->set($key, $value, $ttl);
            if (!$success) {
                log_error('Unable to persist data in memory cache' . composeSuffix(['key' => $key]));
            }
            // if (strpos($key, 'AuctionParameters-AccountId') !== false) {
            //     ll($key . ($success ? '' : ' NOT') . ' saved in memory cache');
            // }
            return $success;
        } catch (Exception $e) {
            $this->logException($e, $key);
        }
        return false;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete($key): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $key = $this->normalizeKey($key);

        try {
            $success = $this->getCache()->delete($key);
            if (!$success) {
                log_error('Unable to delete item from memory cache' . composeSuffix(['key' => $key]));
            }
            // if (strpos($key, 'AuctionParameters-AccountId') !== false) {
            //     ll($key . ($success ? '' : ' NOT') . ' removed from memory cache');
            // }
            return $success;
        } catch (Exception $e) {
            $this->logException($e, $key);
        }
        return false;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable|array $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null): iterable
    {
        if (!$this->isEnabled()) {
            return [];
        }

        $keys = $this->normalizeKeys($keys);

        $results = [];
        try {
            $results = $this->getCache()->getMultiple($keys);
        } catch (Exception $e) {
            $this->logException($e, $keys);
        }
        foreach ($keys as $key) {
            if (!array_key_exists($key, $results)) {
                $results[$key] = $default;
            }
        }
        return $results;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array|iterable $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     * the driver supports TTL then the library may set a default value
     * for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function setMultiple($values, $ttl = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        try {
            $this->getCache()->setMultiple($values);
            return true;
        } catch (Exception $e) {
            $this->logException($e, array_keys($values));
        }
        return false;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable|array $keys A list of string-based keys to be deleted.
     * @return bool True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $keys = $this->normalizeKeys($keys);

        try {
            $this->getCache()->deleteMultiple($keys);
            return true;
        } catch (Exception $e) {
            $this->logException($e, $keys);
        }
        return false;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     * @return bool
     */
    public function has($key): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $key = $this->normalizeKey($key);

        try {
            $isFound = $this->getCache()->has($key);
            // ll($key . ' checked for existence. Is found: ' . (int)$isFound);
            return $isFound;
        } catch (Exception $e) {
            $this->logException($e, $key);
        }
        return false;
    }

    /**
     * Load record from memory cache. If absent, then load by passed function
     * @param string $key
     * @param callable $loadFn function loads entity from db
     * @param int|null $ttl
     * @return mixed
     */
    public function load(string $key, callable $loadFn, int $ttl = null): mixed
    {
        $result = null;
        if ($this->has($key)) {
            $result = $this->get($key);
        }
        // Don't do else, because cache may be invalidated between has() and get() above
        if (!$this->has($key)) {
            $result = $loadFn();
            $this->set($key, $result, $ttl);
        }
        return $result;
    }

    /**
     * @param Exception $e
     * @param string|string[] $key
     */
    protected function logException(Exception $e, string|array $key): void
    {
        $keyInfo = is_array($key) ? ['Keys' => implode(', ', $key)] : ['Key' => $key];
        log_error(composeLogData([$keyInfo, 'Error' => $e->getCode() . ' - ' . $e->getMessage()]));
    }

    /**
     * MemoryCacheBackend is singleton object, we don't store it in this object state
     * @return MemoryCacheBackend
     */
    protected function getBackend(): MemoryCacheBackend
    {
        return MemoryCacheBackend::getInstance();
    }
}
