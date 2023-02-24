<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\Consignor;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Lock\Common\LockingResult;
use Sam\EntityMaker\LotItem\Lock\Consignor\Internal\Detect\LotItemUniqueConsignorLockRequirementCheckerCreateTrait;
use Sam\User\Lock\UserModificationLockerCreateTrait;

/**
 * Class LotItemUniqueConsignorLocker
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueConsignorLocker extends CustomizableClass
{
    use LotItemUniqueConsignorLockRequirementCheckerCreateTrait;
    use UserModificationLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return LockingResult
     */
    public function lock(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): LockingResult {
        if (isset($configDto->dbLocks[self::class])) {
            log_trace('Lock already exists');
            return LockingResult::new()->locked(self::class, $configDto);
        }

        $consignorName = Cast::toString($inputDto->consignorName);
        $requirementCheckingResult = $this->createLotItemUniqueConsignorLockRequirementChecker()->check($configDto->mode, $consignorName);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            $isLocked = $this->createUserModificationLocker()->lock();
            if (!$isLocked) {
                return LockingResult::new()->canNotLock(self::class, $configDto);
            }
            $configDto->dbLocks[self::class] = Constants\DbLock::USER_MODIFICATION_LOCK;
            return LockingResult::new()->locked(self::class, $configDto);
        }

        log_trace($requirementCheckingResult->message());
        return LockingResult::new()->notLocked(self::class, $configDto);
    }

    public function unlock(LotItemMakerConfigDto $configDto): LotItemMakerConfigDto
    {
        if (!isset($configDto->dbLocks[self::class])) {
            return $configDto;
        }

        $this->createUserModificationLocker()->unlock();
        unset($configDto->dbLocks[self::class]);
        return $configDto;
    }
}
