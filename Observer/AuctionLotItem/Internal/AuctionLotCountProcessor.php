<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AuctionLotItem\Internal;

use AuctionLotItem;
use Sam\Auction\Cache\LotCount\AuctionLotCountCacherAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\AuctionInfo\LotItemAuctionInfoUpdaterAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionLotCountProcessor
 * @package Sam\Observer\AuctionLotItem
 * @internal
 */
class AuctionLotCountProcessor extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use AuctionLotCountCacherAwareTrait;
    use LotItemAuctionInfoUpdaterAwareTrait;
    use LotItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->updateLotCount($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->updateLotCount($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('LotStatusId')
            || $subject->isPropertyModified('AuctionId');
    }

    protected function updateLotCount(EntityObserverSubject $subject): void
    {
        $this->updateTotalLotCount($subject);
        $this->updateActiveLotCount($subject);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getAuctionLotCountCacher()->flush($editorUserId);
    }

    /**
     * 1) Increase/decrease auction lot count depending on properties changes
     * 2) Update sale# info in lot_item.auction_info
     * @param EntityObserverSubject $subject
     */
    protected function updateTotalLotCount(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();

        // Increase/decrease auction lot count depending on properties changes
        $auctionLotCountChangeValue = $this->detectAuctionLotCountChangeValue($subject, Constants\Lot::$inAuctionStatuses);
        if ($auctionLotCountChangeValue !== 0) {
            $this->addTotalCount($auctionLot->AuctionId, $auctionLotCountChangeValue, $auctionLot->LotItemId);
        }

        $shouldDecreaseOldAuctionLotCount = $this->shouldDecreasePreviousActionLotCount($subject, Constants\Lot::$inAuctionStatuses);
        if ($shouldDecreaseOldAuctionLotCount) {
            $auctionIdOld = (int)$subject->getOldPropertyValue('AuctionId');
            $this->addTotalCount($auctionIdOld, -1, $auctionLot->LotItemId);
        }

        // Update auctions info (SAM-2739)
        if (
            $auctionLotCountChangeValue
            || $shouldDecreaseOldAuctionLotCount
        ) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
            if ($lotItem) {
                $editorUserId = $this->getUserLoader()->loadSystemUserId();
                $this->getLotItemAuctionInfoUpdater()->refresh($lotItem, $editorUserId);
            }
        }
    }

    /**
     * Increase/decrease auction lot count depending on properties changes
     * @param EntityObserverSubject $subject
     */
    protected function updateActiveLotCount(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();

        $auctionLotCountChangeValue = $this->detectAuctionLotCountChangeValue($subject, [Constants\Lot::LS_ACTIVE]);
        if ($auctionLotCountChangeValue !== 0) {
            $this->addTotalActiveCount($auctionLot->AuctionId, $auctionLotCountChangeValue, $auctionLot->LotItemId);
        }

        $shouldDecreaseOldAuctionLotCount = $this->shouldDecreasePreviousActionLotCount($subject, [Constants\Lot::LS_ACTIVE]);
        if ($shouldDecreaseOldAuctionLotCount) {
            $auctionIdOld = (int)$subject->getOldPropertyValue('AuctionId');
            $this->addTotalActiveCount($auctionIdOld, -1, $auctionLot->LotItemId);
        }
    }

    /**
     * @param EntityObserverSubject $subject
     * @param array $expectedLotStatuses
     * @return int
     */
    protected function detectAuctionLotCountChangeValue(EntityObserverSubject $subject, array $expectedLotStatuses): int
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $isLotWithExpectedStatus = in_array($auctionLot->LotStatusId, $expectedLotStatuses, true);

        $changeValue = 0;
        if (
            $isLotWithExpectedStatus
            && $subject->isPropertyModified('AuctionId')
        ) {
            $changeValue = 1;
        } elseif ($subject->isPropertyModified('LotStatusId')) {
            $lotStatusIdOld = (int)$subject->getOldPropertyValue('LotStatusId');
            $wasLotWithExpectedStatus = in_array($lotStatusIdOld, $expectedLotStatuses, true);
            if ($isLotWithExpectedStatus) {
                $changeValue++;
            }
            if ($wasLotWithExpectedStatus) {
                $changeValue--;
            }
        }
        return $changeValue;
    }

    /**
     * @param EntityObserverSubject $subject
     * @param array $expectedLotStatuses
     * @return bool
     */
    protected function shouldDecreasePreviousActionLotCount(EntityObserverSubject $subject, array $expectedLotStatuses): bool
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();

        if (
            $subject->isPropertyModified('AuctionId')
            && $subject->getOldPropertyValue('AuctionId')
        ) {
            $previousAuctionLotStatusId = $subject->getOldPropertyValue('LotStatusId') ?? $auctionLot->LotStatusId;
            return in_array((int)$previousAuctionLotStatusId, $expectedLotStatuses, true);
        }

        return false;
    }

    /**
     * @param int|null $auctionId
     * @param int $count
     * @param int $lotItemId
     */
    protected function addTotalActiveCount(?int $auctionId, int $count, int $lotItemId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            $this->getAuctionLotCountCacher()->addTotalActiveCount([$auctionId], $count, $lotItemId);
        }
    }

    /**
     * @param int|null $auctionId
     * @param int $count
     * @param int $lotItemId
     */
    protected function addTotalCount(?int $auctionId, int $count, int $lotItemId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            $this->getAuctionLotCountCacher()->addTotalCount([$auctionId], $count, $lotItemId);
        }
    }
}
