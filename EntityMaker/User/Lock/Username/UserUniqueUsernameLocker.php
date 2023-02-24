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

namespace Sam\EntityMaker\User\Lock\Username;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\Common\LockingResult;
use Sam\EntityMaker\User\Lock\Username\Internal\Detect\UniqueUsernameLockRequirementCheckerCreateTrait;
use Sam\User\Lock\UserModificationLockerCreateTrait;

/**
 * Class UserUniqueUsernameLocker
 * @package Sam\EntityMaker\User\Lock\Username
 */
class UserUniqueUsernameLocker extends CustomizableClass
{
    use UniqueUsernameLockRequirementCheckerCreateTrait;
    use UserModificationLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto,
    ): LockingResult {
        if (isset($configDto->dbLocks[self::class])) {
            log_trace('Lock already exists');
            return LockingResult::new()->locked(self::class, $configDto);
        }

        $username = Cast::toString($inputDto->username);
        $userId = Cast::toInt($inputDto->id);
        $requirementCheckingResult = $this->createUniqueUsernameLockRequirementChecker()->check($userId, $username);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            $isLocked = $this->createUserModificationLocker()->lock();
            if (!$isLocked) {
                return LockingResult::new()->canNotLock(self::class, $configDto);
            }
            $configDto->dbLocks[self::class] = $username;
            return LockingResult::new()->locked(self::class, $configDto);
        }

        log_trace($requirementCheckingResult->message());
        return LockingResult::new()->notLocked(self::class, $configDto);
    }

    public function unlock(UserMakerConfigDto $configDto): UserMakerConfigDto
    {
        if (!isset($configDto->dbLocks[self::class])) {
            return $configDto;
        }
        $this->createUserModificationLocker()->unlock();
        unset($configDto->dbLocks[self::class]);
        return $configDto;
    }
}
