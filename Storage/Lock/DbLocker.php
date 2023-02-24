<?php
/**
 * SAM-5652: Move db locking logic and apply trait
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 28, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Lock;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DbLocker
 * @package Sam\Storage\Lock
 */
class DbLocker extends CustomizableClass
{
    use DbConnectionTrait;

    private static array $lockNestingLevel = [];
    protected ?string $installGuid = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get lock with passed name and definite attempts count.
     * Every attempt takes $timeout seconds.
     * Return false - if name is locked by another client.
     * @param string $lockName
     * @param int $maxAttempts
     * @param int $timeout
     * @return bool
     */
    public function getLock(string $lockName, int $maxAttempts = 5, int $timeout = 1): bool
    {
        $fullLockName = $this->buildFullLockName($lockName);
        $lockNameHash = $this->hashLockName($fullLockName);
        if (array_key_exists($fullLockName, self::$lockNestingLevel)) {
            self::$lockNestingLevel[$fullLockName]++;
            log_debug(
                'Lock: nesting level increased'
                . composeSuffix([
                    'lock name' => $fullLockName,
                    'hash' => $lockNameHash,
                    'level' => self::$lockNestingLevel[$fullLockName]
                ])
            );
            return true;
        }

        $attempt = 0;
        do {
            $attempt++;
            $query = "SELECT GET_LOCK('{$lockNameHash}', {$timeout}) AS `is_free_lock`";
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $isFreeLock = $row['is_free_lock'];
            if (
                !$isFreeLock
                && $attempt === $maxAttempts
            ) {
                log_debug(
                    'Lock: Cannot lock. Lock is not free'
                    . composeSuffix([
                        'lock name' => $fullLockName,
                        'hash' => $lockNameHash,
                        'isFreshLock' => $isFreeLock
                    ])
                );
                return false;
            }
        } while (!$isFreeLock);

        self::$lockNestingLevel[$fullLockName] = 1;
        log_debug(
            'Lock: success'
            . composeSuffix([
                'lock name' => $fullLockName,
                'hash' => $lockNameHash,
                'isFreshLock' => $isFreeLock
            ])
        );
        return true;
    }

    /**
     * Release lock with the name $lockName
     * Returns true - if the lock was released, false - if the lock was not established by this thread or if the named lock did not exist.
     * @param string $lockName
     * @return bool
     */
    public function releaseLock(string $lockName): bool
    {
        $fullLockName = $this->buildFullLockName($lockName);
        $lockNameHash = $this->hashLockName($fullLockName);
        if (!array_key_exists($fullLockName, self::$lockNestingLevel)) {
            log_warning(
                'Release lock: lock is not exist'
                . composeSuffix([
                    'lock name' => $fullLockName,
                    'hash' => $lockNameHash,
                ])
            );
            return false;
        }

        self::$lockNestingLevel[$fullLockName]--;
        if (self::$lockNestingLevel[$fullLockName] > 0) {
            log_debug(
                'Release lock: lock decreased'
                . composeSuffix([
                    'lock name' => $fullLockName,
                    'hash' => $lockNameHash,
                    'level' => self::$lockNestingLevel[$fullLockName]
                ])
            );
        } else {
            unset(self::$lockNestingLevel[$fullLockName]);
            $query = "SELECT RELEASE_LOCK('{$lockNameHash}') AS `is_released`";
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            log_debug(
                'Release lock: ' . ($row['is_released'] ? 'released' : 'not released')
                . composeSuffix([
                    'lock name' => $fullLockName,
                    'hash' => $lockNameHash,
                    'isReleased' => $row['is_released']
                ])
            );
            return (bool)$row['is_released'];
        }

        return true;
    }

    /**
     * @param string $lockName
     * @return string
     */
    private function buildFullLockName(string $lockName): string
    {
        $installGuid = $this->getInstallGuid();
        return "{$lockName}, Install GUID: {$installGuid}";
    }

    /**
     * Lock name length cannot exceed 64 characters for GET_LOCK() mysql function.
     * thus we hash it with algorithm that results with respective length.
     * @param string $lockName
     * @return string
     */
    private function hashLockName(string $lockName): string
    {
        return hash("sha512/256", $lockName);
    }

    /**
     * Create and set a Guid based on the installation database name
     * @return string
     */
    protected function getInstallGuid(): string
    {
        if ($this->installGuid === null) {
            $this->installGuid = $this->getDbName();
        }
        return $this->installGuid;
    }
}
