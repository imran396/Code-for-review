<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\AtomicFill\Save;

use AuctionLotItem;
use RuntimeException;
use Sam\AuctionLot\LotNo\Fill\LotNoAutoFillerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Lock\DbLockerCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Auto-fill auction lot No. avoiding duplication in case of running a concurrent request
 *
 * Class AuctionLotNoAtomicProcessor
 * @package Sam\AuctionLot\LotNo\Storage
 */
class AuctionLotNoAtomicSaver extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use DbLockerCreateTrait;
    use LotNoAutoFillerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lockName
     */
    private function getLock(string $lockName): void
    {
        $isFreeLock = $this->createDbLocker()->getLock($lockName);
        if (!$isFreeLock) {
            throw new RuntimeException("Attempts limit exceeded, when trying to get free lock ({$lockName}). Please, try again.");
        }
    }

    /**
     * Fill lot No. and persist. Uses auction level lock
     *
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function fillAndSave(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $lockName = $this->makeLockName($auctionLot);
        $this->getLock($lockName);
        $this->getLotNoAutoFiller()->fill($auctionLot);
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        $this->releaseLock($lockName);
    }

    /**
     * @param string $lockName
     */
    private function releaseLock(string $lockName): void
    {
        $isLockReleased = $this->createDbLocker()->releaseLock($lockName);
        if (!$isLockReleased) {
            throw new RuntimeException("Lock ({$lockName}) was not released");
        }
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    private function makeLockName(AuctionLotItem $auctionLot): string
    {
        return sprintf(Constants\DbLock::AUCTION_LOT_FOR_LOT_NO_GENERATION_BY_AUCTION_ID_TPL, $auctionLot->AuctionId);
    }
}
