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
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Save\Date\LotBulkGroupLotDateUpdaterCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class BulkGroupLotDateUpdater
 * @package Sam\Observer\AuctionLotItem
 * @internal
 */
class BulkGroupLotDateUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use LotBulkGroupLotDateUpdaterCreateTrait;
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
        $this->updateBulkGroupLotDate($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->updateBulkGroupLotDate($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();

        if (
            $auctionLot->isDeleted()
            || !$auctionLot->hasPiecemealRole()
        ) {
            return false;
        }
        return $subject->isPropertyModified('AuctionId')
            || $subject->isPropertyModified('LotItemId')
            || $subject->isPropertyModified('LotStatusId')
            || $subject->isPropertyModified('StartDate')
            || $subject->isPropertyModified('EndDate')
            || ($subject->isPropertyModified('CurrentBidId')
                && $this->isTimed($auctionLot));
    }

    /**
     * Is subject lot from timed auction?
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    protected function isTimed(AuctionLotItem $auctionLot): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        $isTimed = $auction && $auction->isTimed();
        return $isTimed;
    }

    protected function updateBulkGroupLotDate(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createLotBulkGroupLotDateUpdater()->updateByPiecemealAuctionLot($auctionLot, $editorUserId);
    }
}
