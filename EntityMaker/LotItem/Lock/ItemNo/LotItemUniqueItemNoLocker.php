<?php
/**
 * This service checks input and detects if system must lock LotItem editing operation.
 * We define lock scope bounded by account of lot items.
 * We aren't able to protect by item#, because of item# auto-assignment feature, where item# is generated.
 * We must lock by account, because we want to avoid race condition when new item# is generated, i.e. at the moment between item# suggestion and next operation (locking/saving).
 *
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\ItemNo;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\LotItemUniqueItemNoLockRequirementCheckingInput;
use Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect\LotItemUniqueItemNoLockRequirementCheckerCreateTrait;
use Sam\EntityMaker\LotItem\Lock\Common\LockingResult;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class LotItemUniqueItemNoLocker
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueItemNoLocker extends CustomizableClass
{
    use DbLockerCreateTrait;
    use LotItemUniqueItemNoLockRequirementCheckerCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
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

        $input = LotItemUniqueItemNoLockRequirementCheckingInput::new()->construct(
            Cast::toInt($inputDto->id),
            (string)$inputDto->itemNum,
            (string)$inputDto->itemNumExt,
            (string)$inputDto->itemFullNum,
            isset($inputDto->itemNum),
            isset($inputDto->itemNumExt),
            isset($inputDto->itemFullNum)
        );
        $requirementCheckingResult = $this->createLotItemUniqueItemNoLockRequirementChecker()->check($input);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            return $this->acquireLock($configDto);
        }

        log_trace($requirementCheckingResult->message());
        return LockingResult::new()->notLocked(self::class, $configDto);
    }

    public function unlock(LotItemMakerConfigDto $configDto): LotItemMakerConfigDto
    {
        if (!isset($configDto->dbLocks[self::class])) {
            return $configDto;
        }

        $this->createDbLocker()->releaseLock($configDto->dbLocks[self::class]);
        unset($configDto->dbLocks[self::class]);
        return $configDto;
    }

    /**
     * @param LotItemMakerConfigDto $configDto
     * @return LockingResult
     */
    protected function acquireLock(LotItemMakerConfigDto $configDto): LockingResult
    {
        $lockName = $this->makeLockName($configDto->serviceAccountId);
        $isLocked = $this->createDbLocker()->getLock($lockName);
        if (!$isLocked) {
            return LockingResult::new()->canNotLock(self::class, $configDto);
        }

        $configDto->dbLocks[self::class] = $lockName;
        return LockingResult::new()->locked(self::class, $configDto);
    }

    /**
     * @param int $accountId
     * @return string
     */
    protected function makeLockName(int $accountId): string
    {
        return sprintf(Constants\DbLock::LOT_ITEM_BY_ACCOUNT_ID_TPL, $accountId);
    }
}
