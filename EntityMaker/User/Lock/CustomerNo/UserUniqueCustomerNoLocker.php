<?php
/**
 * SAM-10627: Supply uniqueness for user fields: customer#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\CustomerNo;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\Common\LockingResult;
use Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect\UserUniqueCustomerNoLockRequirementCheckerCreateTrait;
use Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect\UserUniqueCustomerNoLockRequirementCheckingInput;
use Sam\User\Lock\UserModificationLockerCreateTrait;

/**
 * Class UserUniqueCustomerNoLocker
 * @package Sam\EntityMaker\User\Lock\CustomerNo
 */
class UserUniqueCustomerNoLocker extends CustomizableClass
{
    use UserUniqueCustomerNoLockRequirementCheckerCreateTrait;
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
            log_trace('Lock already exists' . composeSuffix(['lock name' => $configDto->dbLocks[self::class]]));
            return LockingResult::new()->locked(self::class, $configDto);
        }

        $requirementCheckingInput = UserUniqueCustomerNoLockRequirementCheckingInput::new()->construct(
            Cast::toInt($inputDto->id),
            isset($inputDto->customerNo),
            (string)$inputDto->customerNo
        );

        $requirementCheckingResult = $this->createUserUniqueCustomerNoLockRequirementChecker()->check($requirementCheckingInput);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            $isLocked = $this->createUserModificationLocker()->lock();
            if (!$isLocked) {
                return LockingResult::new()->canNotLock(self::class, $configDto);
            }
            $configDto->dbLocks[self::class] = $this->createUserModificationLocker()->getLockName();
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
