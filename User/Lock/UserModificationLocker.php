<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Lock;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class UserModificationLocker
 * @package Sam\User
 */
class UserModificationLocker extends CustomizableClass
{
    use DbLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(int $maxAttempts = 5, int $timeout = 1): bool
    {
        $isLocked = $this->createDbLocker()->getLock(Constants\DbLock::USER_MODIFICATION_LOCK, $maxAttempts, $timeout);
        if (!$isLocked) {
            log_error("Cannot lock user modification");
        }
        return $isLocked;
    }

    public function unlock(): bool
    {
        return $this->createDbLocker()->releaseLock(Constants\DbLock::USER_MODIFICATION_LOCK);
    }

    public function getLockName(): string
    {
        return Constants\DbLock::USER_MODIFICATION_LOCK;
    }

}
