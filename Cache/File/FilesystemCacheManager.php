<?php
/**
 * General manager for file caching.
 * Note, that we don't have any garbage collection logic. We do that with help of shell script.
 *
 * SAM-4024: Replace QCache with FilesystemCacheManager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         $Id$
 * @since           Jul 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Cache\File;

use DateInterval;
use Exception;
use LogicException;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FolderManagerAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Core\Constants;
use Sam\File\Manage\FileManagerInterface;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class FilesystemCacheManager
 * @package Sam\Cache
 */
class FilesystemCacheManager extends CustomizableClass implements CacheInterface
{
    use ConfigRepositoryAwareTrait;
    use FolderManagerAwareTrait;
    use SupportLoggerAwareTrait;

    public const EXT_PHP = 'php';
    public const EXT_TXT = 'txt';

    /** @var FileManagerInterface|null */
    protected ?FileManagerInterface $reader = null;
    /** @var FileManagerInterface|null */
    protected ?FileManagerInterface $writer = null;
    /** @var string */
    protected string $namespace = '';
    /** @var string */
    protected string $extension = '';
    /** @var string */
    protected string $cacheDirectory = '';
    /** @var string */
    protected string $key = '';
    /** @var bool */
    protected bool $enabledGzip = false;
    /** @var int|null */
    protected ?int $gzipLevel = null;
    /** @var string */
    protected string $cacheBasePath = '';
    /** @var string */
    protected string $filenameTransformation = '';
    /** @var int|null */
    protected ?int $defaultTtl = null;
    /** @var int|null */
    protected ?int $permissions = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- CacheInterface implementation ---

    /**
     * Fetches a value from the cache.
     *
     * @param string $key The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $result = $default;
        if (!$this->validateKey($key)) {
            return $result;
        }

        $this->setKey($key);

        try {
            if ($this->getReader()->exist($this->getRelativeFilePath())) {
                $data = $this->read($this->getRelativeFilePath());

                if (
                    $data === false
                    || !is_array($data)
                    || !isset($data['time'], $data['data'])
                ) {
                    $this->getReader()->delete($this->getRelativeFilePath());
                    throw new LogicException("Cannot unserialize cache file, cache file deleted.");
                }

                if (isset($data['ttl'])) {
                    //If we set TTL in set() method
                    if ((time() - $data['time']) < $data['ttl']) {
                        $result = $data['data'];
                    } else {
                        $this->getReader()->delete($this->getRelativeFilePath());
                    }
                } else {
                    //Or use config lifetime if TTL is not set in set() method
                    if ((time() - $data['time']) < $this->getDefaultTtl()) {
                        $result = $data['data'];
                    } else {
                        $this->getReader()->delete($this->getRelativeFilePath());
                    }
                }
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
     * @param int|null $ttl Seconds. Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     */
    public function set($key, $value, $ttl = null): bool
    {
        if (!$this->validateKey($key)) {
            return false;
        }

        $this->setKey($key);
        $cacheDir = $this->getCacheDirectory() . '/' . $this->getNamespace();
        if ($this->enabledGzip) {
            $value = gzencode($value, $this->getGzipLevel());
            $this->enabledGzip = false;
        }
        $storeData = [
            "time" => time(),
            "ttl" => $ttl,
            "data" => $value,
        ];

        $isChmod = false;
        if (!is_dir($cacheDir)) {
            $oldMask = umask(0);
            $isChmod = @mkdir($cacheDir);
            umask($oldMask);
            if (!$isChmod && !is_dir($cacheDir)) {
                $this->getSupportLogger()->error(sprintf('Directory "%s" was not created', $cacheDir));
                return false;
            }
        }
        if ($isChmod) {
            $this->getFolderManager()->chmodRecursively($cacheDir, $this->getPermissions());
        }

        try {
            $this->write($storeData, $this->getRelativeFilePath());
            return true;
        } catch (FileException $e) {
            $this->logException($e, $key);
            return false;
        }
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key): bool
    {
        if (!$this->validateKey($key)) {
            return false;
        }

        $this->setKey($key);

        try {
            $this->getWriter()->delete($this->getRelativeFilePath());
            return true;
        } catch (FileException $e) {
            $this->logException($e, $key);
            return false;
        }
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        $cacheDir = $this->getCacheDirectory() . '/' . $this->getNamespace();
        return $this->getFolderManager()->clearDir($cacheDir);
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return array|null A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null): ?array
    {
        $result = $default;
        if (!is_array($keys)) {
            throw new InvalidArgumentException('$values variable is not array');
        }
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null): bool
    {
        if (!is_array($values)) {
            throw new InvalidArgumentException('$values variable is not array');
        }
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys): bool
    {
        if (!is_array($keys)) {
            throw new InvalidArgumentException('$keys variable is not array');
        }
        foreach ($keys as $key) {
            if (!$this->validateKey($key)) {
                return false;
            }
            $this->setKey($key);
            try {
                $this->getWriter()->delete($this->getRelativeFilePath());
            } catch (FileException $e) {
                $this->logException($e, $key);
                return false;
            }
        }
        return true;
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
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has($key): bool
    {
        if (!$this->validateKey($key)) {
            throw new InvalidArgumentException('$keys variable is not array');
        }
        $this->setKey($key);
        try {
            $fileExist = $this->getReader()->exist($this->getRelativeFilePath());
            return $fileExist;
        } catch (FileException $e) {
            $this->logException($e, $key);
            return false;
        }
    }

    /**
     * @param string $key
     * @param string|null $extension
     * @return int|null
     */
    public function getFileMtime(string $key, ?string $extension = null): ?int
    {
        $this->setKey($key);
        if ($extension) {
            $this->setExtension($extension);
        }
        $fileMTime = null;
        try {
            $fileMTime = $this->getReader()->getMTime($this->getRelativeFilePath());
        } catch (FileException $e) {
            $this->logException($e);
        }
        return $fileMTime;
    }

    // --- public setters ---

    /**
     * @param FileManagerInterface $reader
     * @return static
     */
    public function setReader(FileManagerInterface $reader): static
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * @param FileManagerInterface $writer
     * @return static
     */
    public function setWriter(FileManagerInterface $writer): static
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @param string $namespace
     * @return static
     */
    public function setNamespace(string $namespace): static
    {
        $this->namespace = trim($namespace);
        return $this;
    }

    /**
     * @param string $extension
     * @return static
     */
    public function setExtension(string $extension): static
    {
        $this->extension = trim($extension);
        return $this;
    }

    /**
     * @param string $cacheBasePath
     * @return static
     */
    public function setCacheBasePath(string $cacheBasePath): static
    {
        $this->cacheBasePath = $cacheBasePath;
        return $this;
    }

    /**
     * @param string $path
     * @return static
     */
    public function setCacheDirectory(string $path): static
    {
        $this->cacheDirectory = trim($path);
        return $this;
    }

    /**
     * @param string $filenameTransformation
     * @return static
     */
    public function setFilenameTransformation(string $filenameTransformation): static
    {
        $this->filenameTransformation = $filenameTransformation;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableGzip(bool $enabled): static
    {
        $this->enabledGzip = $enabled;
        return $this;
    }

    /**
     * @param int $level
     * @return static
     */
    public function setGzipLevel(int $level): static
    {
        $this->gzipLevel = $level;
        return $this;
    }

    /**
     * @param int $ttl
     * @return static
     */
    public function setDefaultTtl(int $ttl): static
    {
        $this->defaultTtl = $ttl;
        return $this;
    }

    /**
     * @param int $permissions
     * @return static
     */
    public function setPermissions(int $permissions): static
    {
        $this->permissions = $permissions;
        return $this;
    }

    // --- protected ---

    /**
     * @return FileManagerInterface
     */
    protected function getReader(): FileManagerInterface
    {
        if ($this->reader === null) {
            $this->reader = LocalFileManager::new()
                ->setSupportLogger($this->getSupportLogger());
        }
        return $this->reader;
    }

    /**
     * @return FileManagerInterface
     */
    protected function getWriter(): FileManagerInterface
    {
        if ($this->writer === null) {
            $this->writer = LocalFileManager::new()
                ->setSupportLogger($this->getSupportLogger());
        }
        return $this->writer;
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        if (!$this->namespace) {
            throw new InvalidArgumentException('Namespace not set');
        }
        return $this->namespace;
    }

    /**
     * @return string
     */
    protected function getExtension(): string
    {
        if (!$this->extension) {
            $this->extension = self::EXT_TXT;
        }
        return $this->extension;
    }

    /**
     * @return string
     */
    protected function getCacheBasePath(): string
    {
        if (!$this->cacheBasePath) {
            $this->cacheBasePath = $this->cfg()->get('core->cache->filesystem->path');
        }
        return $this->cacheBasePath;
    }

    /**
     * @return string
     */
    protected function getCacheDirectory(): string
    {
        if (!$this->cacheDirectory) {
            $this->cacheDirectory = path()->sysRoot() . $this->getCacheBasePath();
        }
        return $this->cacheDirectory;
    }

    /**
     * @return string
     */
    protected function getFilenameTransformation(): string
    {
        if (!$this->filenameTransformation) {
            $this->filenameTransformation = $this->cfg()->get('core->cache->filesystem->filenameTransformation');
        }
        return $this->filenameTransformation;
    }

    /**
     * @param string $key
     * @return static
     */
    protected function setKey(string $key): static
    {
        $transformation = $this->getFilenameTransformation();
        $key = trim($key);
        if ($transformation === Constants\FileCache::FT_MD5) {
            $key = md5($key);
        } else { // Constants\FileCache::FT_PLAIN
            $key = preg_replace('/\W+/', '_', $key);
        }
        $this->key = $key;
        return $this;
    }

    /**
     * @return int
     */
    protected function getGzipLevel(): int
    {
        if ($this->gzipLevel === null) {
            $this->gzipLevel = $this->cfg()->get('core->cache->filesystem->gzipLevel');
        }
        return $this->gzipLevel;
    }

    /**
     * @return int
     */
    protected function getDefaultTtl(): int
    {
        if ($this->defaultTtl === null) {
            $this->defaultTtl = $this->cfg()->get('core->cache->filesystem->ttl');
        }
        return $this->defaultTtl;
    }

    /**
     * @return int|null null will lead to assigning default directory permissions
     */
    protected function getPermissions(): ?int
    {
        return $this->permissions;
    }

    /**
     * @param string $filePath
     * @return mixed
     * @throws FileException
     */
    private function read(string $filePath): mixed
    {
        if ($this->getExtension() === self::EXT_PHP) {
            $data = @include(path()->sysRoot() . '/' . $filePath);
        } else {
            $file = $this->getReader()->read($this->getRelativeFilePath());
            $data = @unserialize($file);
        }
        return $data;
    }

    /**
     * @param array $storeData
     * @param string $filePath
     * @throws FileException
     */
    private function write(array $storeData, string $filePath): void
    {
        if ($this->getExtension() === self::EXT_PHP) {
            $data = '<?php return ' . var_export($storeData, true) . ";\n";
        } else {
            $data = serialize($storeData);
        }
        $this->getWriter()->write($data, $filePath);
    }

    /**
     * @return string
     */
    protected function getRelativeFilePath(): string
    {
        $filename = sprintf(
            "%s/%s/%s.%s",
            $this->getCacheBasePath(),
            $this->getNamespace(),
            $this->key,
            $this->getExtension()
        );
        return $filename;
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function validateKey(string $key): bool
    {
        if ($key === '') {
            $this->getSupportLogger()->debug('$key `' . $key . '` is empty');
            return false;
        }
        return true;
    }

    /**
     * @param Exception $e
     * @param string|string[]|null $key
     * @return void
     */
    protected function logException(Exception $e, string|array|null $key = null): void
    {
        $keyInfo = '';
        if ($key) {
            $keyInfo = is_array($key) ? 'Keys: ' . implode(', ', $key) . ', ' : 'Key: ' . $key . ', ';
        }
        $this->getSupportLogger()->debug("{$keyInfo}Problem: {$e->getCode()} - {$e->getMessage()}", null, 2);
    }
}
