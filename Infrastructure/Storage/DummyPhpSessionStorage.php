<?php
/**
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

namespace Sam\Infrastructure\Storage;

/**
 * This class contains methods for working with array in memory storage
 * @package Sam\Infrastructure\Storage
 */
class DummyPhpSessionStorage implements PhpSessionStorageInterface
{
    protected array $storage = [];

    /**
     * Checks if value exist in array memory storage
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->storage);
    }

    /**
     * Read value from the array memory storage
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $value = $this->storage[$key] ?? null;
        return $value;
    }

    /**
     * Put value to the array memory storage
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, mixed $value): void
    {
        $this->storage[$key] = $value;
    }

    /**
     * Delete value from the array memory storage
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        unset($this->storage[$key]);
    }

    /**
     * Read all data from array memory storage
     * @return array
     */
    public function all(): array
    {
        return $this->storage;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return true;
    }

    public function getSessionId(): string
    {
        return 'session_id';
    }

    public function isSessionActive(): bool
    {
        return true;
    }
}
