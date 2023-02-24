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

namespace Sam\EntityMaker\Auction\Lock\SaleNo;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Auction\Lock\Common\LockingResult;
use Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect\AuctionUniqueSaleNoLockRequirementCheckerCreateTrait;
use Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect\AuctionUniqueSaleNoLockRequirementCheckerInput;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class AuctionUniqueSaleNoLocker
 * @package Sam\EntityMaker\Auction\Lock\SaleNo
 */
class AuctionUniqueSaleNoLocker extends CustomizableClass
{
    use AuctionUniqueSaleNoLockRequirementCheckerCreateTrait;
    use DbLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): LockingResult {
        if (isset($configDto->dbLocks[self::class])) {
            log_trace('Lock already exists');
            return LockingResult::new()->locked(self::class, $configDto);
        }
        $input = AuctionUniqueSaleNoLockRequirementCheckerInput::new()->construct(
            Cast::toInt($inputDto->id),
            Cast::toString($inputDto->saleNum),
            Cast::toString($inputDto->saleNumExt),
            Cast::toString($inputDto->saleFullNo),
            isset($inputDto->saleNum),
            isset($inputDto->saleNumExt),
            isset($inputDto->saleFullNo),
        );
        $requirementCheckingResult = $this->createAuctionUniqueSaleNoLockRequirementChecker()->check($input);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            return $this->acquireLock($configDto);
        }

        log_trace($requirementCheckingResult->message());
        return LockingResult::new()->notLocked(self::class, $configDto);
    }

    public function unlock(AuctionMakerConfigDto $configDto): AuctionMakerConfigDto
    {
        if (!isset($configDto->dbLocks[self::class])) {
            return $configDto;
        }

        $this->createDbLocker()->releaseLock($configDto->dbLocks[self::class]);
        unset($configDto->dbLocks[self::class]);
        return $configDto;
    }

    protected function acquireLock(AuctionMakerConfigDto $configDto): LockingResult
    {
        $lockName = $this->makeLockName($configDto->serviceAccountId);
        $isLocked = $this->createDbLocker()->getLock($lockName);
        if (!$isLocked) {
            return LockingResult::new()->canNotLock(self::class, $configDto);
        }

        $configDto->dbLocks[self::class] = $lockName;
        return LockingResult::new()->locked(self::class, $configDto);
    }

    protected function makeLockName(int $accountId): string
    {
        return sprintf(Constants\DbLock::AUCTION_BY_ACCOUNT_ID_TPL, $accountId);
    }
}
