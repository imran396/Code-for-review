<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Observer\LotItem\Internal\Load\DataLoaderCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionLotCacheUpdater
 * @package Sam\Observer\LotItem
 * @internal
 */
class AuctionLotCacheUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use DataLoaderCreateTrait;
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
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        if ($subject->isPropertyModified('StartingBid')) {
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->createAuctionLotCacheUpdater()->refreshForLotItem($lotItem->Id, $editorUserId);
        }
        if ($subject->isPropertyModified('ReservePrice')) {
            $this->updateAuctionLotBulkMasterCache($lotItem);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['ReservePrice', 'StartingBid']);
    }

    /**
     * @param LotItem $lotItem
     */
    protected function updateAuctionLotBulkMasterCache(LotItem $lotItem): void
    {
        $auctionIds = $this->createDataLoader()->loadLotItemAuctionIds($lotItem);
        if ($auctionIds) {
            foreach ($auctionIds as $auctionId) {
                $auctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $auctionId);
                if (!$auctionLot) {
                    log_error(
                        "Available auction lot item cannot be found, when processing bulk suggested reserve price"
                        . composeSuffix(['li' => $lotItem->Id, 'a' => $auctionId])
                    );
                    continue;
                }

                if ($auctionLot->hasPiecemealRole()) {
                    $auctionLotBulkMaster = $this->getAuctionLotLoader()->loadById($auctionLot->BulkMasterId);
                    if ($auctionLotBulkMaster) {
                        $editorUserId = $this->getUserLoader()->loadSystemUserId();
                        $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLotBulkMaster, $editorUserId);
                    } else {
                        log_error(
                            "Available auction lot item not found, when want to refresh its cache"
                            . composeSuffix(['master ali' => $auctionLot->BulkMasterId, 'piecemeal ali' => $auctionLot->Id])
                        );
                    }
                }
            }
        }
    }
}
