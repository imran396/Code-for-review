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

interface PhpSessionStorageInterface
{
    /**
     * Checks if value exist in storage
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Read value from storage
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Put value to storage
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, mixed $value): void;

    /**
     * Delete value from storage
     * @param string $key
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Read all data from storage
     * @return array
     */
    public function all(): array;

    /**
     * Detect native PHP session id
     *
     * @return string
     */
    public function getSessionId(): string;

    /**
     * Detect if a session was started and not closed
     *
     * @return bool
     */
    public function isSessionActive(): bool;

    /**
     * Check, if storage available for read/write operations.
     * @return bool
     */
    public function isAvailable(): bool;
}
