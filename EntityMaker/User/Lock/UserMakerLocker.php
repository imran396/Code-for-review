<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\CustomerNo\UserUniqueCustomerNoLockerCreateTrait;
use Sam\EntityMaker\User\Lock\Email\UserUniqueEmailLockerCreateTrait;
use Sam\EntityMaker\User\Lock\UserMakerLockingResult as Result;
use Sam\EntityMaker\User\Lock\Username\UserUniqueUsernameLockerCreateTrait;

/**
 * Class UserMakerLocker
 * @package Sam\EntityMaker\User\Lock
 */
class UserMakerLocker extends CustomizableClass
{
    use UserUniqueCustomerNoLockerCreateTrait;
    use UserUniqueEmailLockerCreateTrait;
    use UserUniqueUsernameLockerCreateTrait;

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
        UserMakerConfigDto $configDto
    ): Result {
        $result = Result::new();

        $usernameLockingResult = $this->createUserUniqueUsernameLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($usernameLockingResult);
        if ($usernameLockingResult->isLocked()) {
            // Do not continue other User constraint checking, because entity is already locked.
            return $result;
        }

        $emailLockingResult = $this->createUserUniqueEmailLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($emailLockingResult);
        if ($emailLockingResult->isLocked()) {
            // Do not continue other User constraint checking, because entity is already locked.
            return $result;
        }

        $customerNoLockingResult = $this->createUserUniqueCustomerNoLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($customerNoLockingResult);

        return $result;
    }

    public function unlock(UserMakerConfigDto $configDto): UserMakerConfigDto
    {
        $configDto = $this->createUserUniqueUsernameLocker()->unlock($configDto);
        $configDto = $this->createUserUniqueCustomerNoLocker()->unlock($configDto);
        return $configDto;
    }
}
