<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Lock\Common\Enum\LockStatus;

/**
 * Class LockingResult
 * @package Sam\EntityMaker\LotItem
 */
class LockingResult extends CustomizableClass
{
    public readonly string $lockerClass;
    public readonly LotItemMakerConfigDto $configDto;
    public readonly LockStatus $lockStatus;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $lockerClass, LotItemMakerConfigDto $configDto, ?LockStatus $lockStatus): static
    {
        $this->lockerClass = $lockerClass;
        $this->configDto = $configDto;
        $this->lockStatus = $lockStatus ?? LockStatus::NOT_LOCKED;
        return $this;
    }

    public function locked(string $lockerClass, LotItemMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::LOCKED);
    }

    public function notLocked(string $lockerClass, LotItemMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::NOT_LOCKED);
    }

    public function canNotLock(string $lockerClass, LotItemMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::CAN_NOT_LOCK);
    }

    public function isSuccess(): bool
    {
        return $this->lockStatus !== LockStatus::CAN_NOT_LOCK;
    }
}
