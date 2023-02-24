<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
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

namespace Sam\EntityMaker\Auction\Lock\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Lock\Common\Enum\LockStatus;

/**
 * Class LockingResult
 * @package Sam\EntityMaker\User\Lock
 */
class LockingResult extends CustomizableClass
{
    public readonly string $lockerClass;
    public readonly AuctionMakerConfigDto $configDto;
    public readonly LockStatus $lockStatus;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $lockerClass, AuctionMakerConfigDto $configDto, ?LockStatus $lockStatus): static
    {
        $this->lockerClass = $lockerClass;
        $this->configDto = $configDto;
        $this->lockStatus = $lockStatus ?? LockStatus::NOT_LOCKED;
        return $this;
    }

    public function locked(string $lockerClass, AuctionMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::LOCKED);
    }

    public function notLocked(string $lockerClass, AuctionMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::NOT_LOCKED);
    }

    public function canNotLock(string $lockerClass, AuctionMakerConfigDto $configDto): static
    {
        return $this->construct($lockerClass, $configDto, LockStatus::CAN_NOT_LOCK);
    }

    public function isSuccess(): bool
    {
        return $this->lockStatus !== LockStatus::CAN_NOT_LOCK;
    }

    public function isLocked(): bool
    {
        return $this->lockStatus === LockStatus::LOCKED;
    }

    public function isNotLocked(): bool
    {
        return $this->lockStatus === LockStatus::NOT_LOCKED;
    }

    public function isCanNotLock(): bool
    {
        return $this->lockStatus === LockStatus::CAN_NOT_LOCK;
    }
}
