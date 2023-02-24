<?php
/**
 * This service checks input and detects if system must lock AuctionLot editing operation.
 * We define lock scope bounded by account of auction lots.
 * We aren't able to protect by lot#, because of lot# auto-assignment feature, where lot# is generated.
 * We must lock by account, because we want to avoid race condition when new lot# is generated, i.e. at the moment between lot# suggestion and next operation (locking/saving).
 *
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
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

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\AuctionLotUniqueLotNoLockRequirementCheckingInput;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\AuctionLotUniqueLotNoLockRequirementCheckerCreateTrait;
use Sam\EntityMaker\AuctionLot\Lock\Common\LockingResult;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class AuctionLotUniqueLotNoLocker
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotUniqueLotNoLocker extends CustomizableClass
{
    use DbLockerCreateTrait;
    use AuctionLotUniqueLotNoLockRequirementCheckerCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return LockingResult
     */
    public function lock(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto
    ): LockingResult {
        if (isset($configDto->dbLocks[self::class])) {
            log_trace('Lock already exists');
            return LockingResult::new()->locked(self::class, $configDto);
        }

        $input = AuctionLotUniqueLotNoLockRequirementCheckingInput::new()->construct(
            Cast::toInt($inputDto->id),
            (string)$inputDto->lotNum,
            (string)$inputDto->lotNumExt,
            (string)$inputDto->lotNumPrefix,
            (string)$inputDto->lotFullNum,
            isset($inputDto->lotNum),
            isset($inputDto->lotNumExt),
            isset($inputDto->lotNumPrefix),
            isset($inputDto->lotFullNum)
        );
        $requirementCheckingResult = $this->createAuctionLotUniqueLotNoLockRequirementChecker()->check($input);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            return $this->acquireLock($configDto);
        }

        log_trace($requirementCheckingResult->message());
        return LockingResult::new()->notLocked(self::class, $configDto);
    }

    public function unlock(AuctionLotMakerConfigDto $configDto): AuctionLotMakerConfigDto
    {
        if (!isset($configDto->dbLocks[self::class])) {
            return $configDto;
        }

        $this->createDbLocker()->releaseLock($configDto->dbLocks[self::class]);
        unset($configDto->dbLocks[self::class]);
        return $configDto;
    }

    /**
     * @param AuctionLotMakerConfigDto $configDto
     * @return LockingResult
     */
    protected function acquireLock(AuctionLotMakerConfigDto $configDto): LockingResult
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
        return sprintf(Constants\DbLock::AUCTION_LOT_BY_ACCOUNT_ID_TPL, $accountId);
    }
}
