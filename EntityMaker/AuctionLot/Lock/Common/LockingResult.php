<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
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

namespace Sam\EntityMaker\AuctionLot\Lock\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Lock\Common\Enum\LockStatus;

/**
 * Class LockingResult
 * @package Sam\EntityMaker\AuctionLot
 */
class LockingResult extends CustomizableClass
{
    public readonly string $lockerClass;
    public readonly AuctionLotMakerConfigDto $configDto;
    public readonly LockStatus $lockStatus;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $lockerClass, AuctionLotMakerConfigDto $configDto, ?LockStatus $lockStatus): static
    {
        $this->lockerClass = $lockerClass;
        $this->configDto = $configDto;
        $this->lockStatus = $lockStatus ?? LockStatus::NOT_LOCKED;
        return $this;
    }

    public function locked(string $lockerClass, AuctionLotMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::LOCKED);
    }

    public function notLocked(string $lockerClass, AuctionLotMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::NOT_LOCKED);
    }

    public function canNotLock(string $lockerClass, AuctionLotMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::CAN_NOT_LOCK);
    }

    public function isSuccess(): bool
    {
        return $this->lockStatus !== LockStatus::CAN_NOT_LOCK;
    }
}
