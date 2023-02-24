<?php
/**
 * SAM-1196: Only start PHP session for pages that actually need the session
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\UserDataStorage;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\CookieStorageCreateTrait;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;

/**
 * We use that class to store variables and arrays in some persistent storage.
 * It would use cookie session as storage, when user is not logged (visitor), otherwise it stores in regular session
 *
 * Class UserDataStorage
 * @package Sam\Application\UserDataStorage
 */
class UserDataStorage extends CustomizableClass
{
    use CookieHelperCreateTrait;
    use CookieStorageCreateTrait;
    use PhpSessionStorageCreateTrait;

    private const STORAGE_PREFIX = 'st_';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save variable or array in storage
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        $storageKey = $this->makeStorageKey($key);
        $value = is_array($value) ? json_encode($value) : $value;
        if ($this->createCookieHelper()->isAuthenticated()) {
            $this->createPhpSessionStorage()->set($storageKey, $value);
            $storage = 'regular session';
        } else {
            $this->createCookieStorage()->set($storageKey, $value);
            $storage = 'cookie session';
        }
        log_debug(
            'Save to storage' . composeSuffix(['storage' => $storage])
            . composeLogData(['Key' => $key, 'Value' => $value])
        );
    }

    /**
     * Get variable or array from storage
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $storageKey = $this->makeStorageKey($key);
        if (
            $this->createCookieHelper()->isAuthenticated()
            && $this->createPhpSessionStorage()->isAvailable()
        ) {
            $stored = $this->createPhpSessionStorage()->get($storageKey);
        } else {
            $stored = $this->createCookieStorage()->get($storageKey);
        }

        $result = null;
        if ($stored !== null) {
            $result = json_decode($stored, true);
            if (
                $result === false
                || $result === null
            ) {
                $result = $stored;
            }
        }
        return $result ?? $default;
    }

    /**
     * Remove variable from storage (removed from session and cookie)
     *
     * @param string $key
     */
    public function remove(string $key): void
    {
        $storageKey = $this->makeStorageKey($key);

        $phpSessionStorage = $this->createPhpSessionStorage();
        if (
            $phpSessionStorage->isAvailable()
            && $phpSessionStorage->isSessionActive()
        ) {
            $phpSessionStorage->delete($storageKey);
        }

        $cookieStorage = $this->createCookieStorage();
        if ($cookieStorage->isAvailable()) {
            $cookieStorage->remove($storageKey);
        }
    }

    /**
     * Remove variables saved to storage
     */
    public function removeAll(): void
    {
        $cookieStorage = $this->createCookieStorage();
        if ($cookieStorage->isAvailable()) {
            $keys = array_keys($cookieStorage->all());
            foreach ($keys as $key) {
                if (str_starts_with($key, self::STORAGE_PREFIX)) {
                    $cookieStorage->remove($key);
                }
            }
        }

        $phpSessionStorage = $this->createPhpSessionStorage();
        if (
            $phpSessionStorage->isAvailable()
            && $phpSessionStorage->isSessionActive()
        ) {
            $keys = array_keys($phpSessionStorage->all());
            foreach ($keys as $key) {
                if (str_starts_with($key, self::STORAGE_PREFIX)) {
                    $phpSessionStorage->delete($key);
                }
            }
        }
    }

    /**
     * @param string $key
     * @return string
     */
    protected function makeStorageKey(string $key): string
    {
        return self::STORAGE_PREFIX . $key;
    }
}
