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
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionLotCacheUpdater
 * @package Sam\Observer\AuctionLotItem\Internal
 */
class AuctionLotCacheUpdater extends CustomizableClass implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
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
        $this->do($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->do($subject);
    }

    protected function do(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $isTimed = $this->isTimed($auctionLot);

        $isCurrentBidModified = $subject->isPropertyModified('CurrentBidId');
        if ((
                $isCurrentBidModified
                && $isTimed
            )
            || $subject->isPropertyModified('AuctionId')
            || $subject->isPropertyModified('LotItemId')
            || $subject->isPropertyModified('LotStatusId')
            || $subject->isPropertyModified('StartDate')
            || $subject->isPropertyModified('EndDate')
        ) {
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
        }

        $isBulkMasterIdModified = $subject->isPropertyModified('BulkMasterId');
        if ((
                $isCurrentBidModified
                || $isBulkMasterIdModified
            )
            && $isTimed
            && $auctionLot->hasPiecemealRole()
        ) {
            /**
             * If piecemeal member of lot bulk group has current bid changed
             * or its current lot bulk group is changed or dropped,
             * then we have to refresh its cache
             */
            $masterAuctionLot = $this->getAuctionLotLoader()->loadById($auctionLot->BulkMasterId);
            if (!$masterAuctionLot) {
                log_error(
                    "Available lot bulk group master auction lot not found, when want to refresh its cache"
                    . composeSuffix(['master ali' => $auctionLot->BulkMasterId])
                );
            }
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($masterAuctionLot, $editorUserId);
        }

        if (
            $isBulkMasterIdModified
            && $isTimed
        ) {
            /**
             * Piecemeal lot is not member of some lot bulk group,
             * thus we have to refresh cache of old group master lot
             */
            $oldMasterAuctionLotId = (int)$subject->getOldPropertyValue('BulkMasterId');
            $oldMasterAuctionLot = $this->getAuctionLotLoader()->loadById($oldMasterAuctionLotId);
            if ($oldMasterAuctionLot) {
                $editorUserId = $this->getUserLoader()->loadSystemUserId();
                $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($oldMasterAuctionLot, $editorUserId);
            } elseif ($oldMasterAuctionLotId > 0) {
                $logData = [
                    'piecemeal ali' => $auctionLot->Id,
                    'old master ali' => $oldMasterAuctionLotId,
                    'new master ali' => $auctionLot->BulkMasterId
                ];
                log_debug("Available auction lot not found for modified BulkMasterId value of piecemeal lot" . composeSuffix($logData));
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();

        if ($auctionLot->isDeleted()) {
            return false;
        }
        return $subject->isModified();
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
}
