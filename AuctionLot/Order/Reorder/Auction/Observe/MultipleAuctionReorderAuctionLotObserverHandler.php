<?php
/**
 * SAM-5658: Multiple Auction Reorderer for lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction\Observe;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\Auction\AuctionLotMultipleAuctionReordererCreateTrait;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal\ObserverHandlerHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;

/**
 * Tracks changes of the auction lot items and triggers a reordering of the linked auction's lots if changes might affect their order.
 * The real reordering does not take place, but only the auction is marked that it needs updating.
 *
 * Class AuctionLotMultipleAuctionReorderObserverHandler
 * @package Sam\AuctionLot\Order\Reorder\Auction\Observe
 */
class MultipleAuctionReorderAuctionLotObserverHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use AuctionLotMultipleAuctionReordererCreateTrait;
    use ObserverHandlerHelperCreateTrait;

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
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $this->triggerReordering($auctionLot);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $this->triggerReordering($auctionLot);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->getEntity() instanceof AuctionLotItem
            && $subject->isAnyPropertyModified(['Id', 'LotNumPrefix', 'LotNum', 'LotNumExt']);
    }

    /**
     * @param AuctionLotItem $auctionLot
     */
    protected function triggerReordering(AuctionLotItem $auctionLot): void
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            $logInfo = composeSuffix(['a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id]);
            log_error("Available auction not found, when checking auction lot in ordering manager{$logInfo}");
            return;
        }
        $isOrderingByLotNum = $this->createObserverHandlerHelper()->hasAuctionLotOrderType(
            $auction,
            Constants\Auction::LOT_ORDER_BY_LOT_NUMBER
        );
        if ($isOrderingByLotNum) {
            $this->createAuctionLotMultipleAuctionReorderer()->addAuctionToQueue($auction);
        }
    }
}
