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

use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for working with native PHP session
 *
 * Class PhpSessionStorage
 * @package Sam\Infrastructure\Storage
 */
class PhpSessionStorage extends CustomizableClass implements PhpSessionStorageInterface
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if session value exist
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->assertCanRead();
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Read value from the sesssion
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $this->assertCanRead();
        $value = $_SESSION[$key] ?? null;
        return $value;
    }

    /**
     * Put value to the session
     *
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, mixed $value): void
    {
        $this->assertCanWrite();
        $_SESSION[$key] = $value;
    }

    /**
     * Delete value from the session
     *
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->assertCanWrite();
        unset($_SESSION[$key]);
    }

    /**
     * Read all session data
     *
     * @return array
     */
    public function all(): array
    {
        $this->assertCanRead();
        return $_SESSION;
    }

    /**
     * Detect native PHP session id
     *
     * @return string
     */
    public function getSessionId(): string
    {
        if (session_id()) {
            return session_id();
        }
        return '';
    }

    /**
     * Detect if a session was started and not closed
     *
     * @return bool
     */
    public function isSessionActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Detect if a session was started
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        $isApplicable = isset($_SESSION);
        return $isApplicable;
    }

    protected function assertCanRead(): void
    {
        if (!$this->isAvailable()) {
            throw new CouldNotAccessPhpSession('Cannot read php session');
        }
    }

    protected function assertCanWrite(): void
    {
        if (!$this->isSessionActive()) {
            throw new CouldNotAccessPhpSession('Cannot write to php session');
        }
    }
}
