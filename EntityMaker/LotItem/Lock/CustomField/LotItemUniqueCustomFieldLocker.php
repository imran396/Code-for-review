<?php
/**
 * SAM-10589: Supply uniqueness of lot item fields: lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\CustomField;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\CustomFieldHelper;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Lock\Common\LockingResult;
use Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect\LotItemUniqueCustomFieldLockRequirementCheckerCreateTrait;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class LotItemUniqueCustomFieldLocker
 * @package Sam\EntityMaker\LotItem\Lock\CustomField
 */
class LotItemUniqueCustomFieldLocker extends CustomizableClass
{
    use DbLockerCreateTrait;
    use LotItemUniqueCustomFieldLockRequirementCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): LockingResult {
        if (isset($configDto->dbLocks[self::class])) {
            log_trace('Lock already exists');
            return LockingResult::new()->locked(self::class, $configDto);
        }

        $customFieldsInput = $this->collectCustomFieldsInput($inputDto);
        $requirementCheckingResult = $this->createLotItemUniqueCustomFieldLockRequirementChecker()->check(Cast::toInt($inputDto->id), $customFieldsInput);
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

    protected function collectCustomFieldsInput(LotItemMakerInputDto $inputDto): array
    {
        return array_filter(
            $inputDto->toArray(),
            static fn(string $key) => str_starts_with($key, CustomFieldHelper::CUSTOM_FIELD_PREFIX),
            ARRAY_FILTER_USE_KEY
        );
    }

    protected function makeLockName(int $accountId): string
    {
        return sprintf(Constants\DbLock::LOT_ITEM_BY_ACCOUNT_ID_TPL, $accountId);
    }
}
