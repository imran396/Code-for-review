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

use LotItem;
use Sam\AuctionLot\Order\Reorder\Auction\AuctionLotMultipleAuctionReordererCreateTrait;
use Sam\AuctionLot\Order\Reorder\Auction\Load\DataLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal\ObserverHandlerHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;

/**
 * If ordering related fields changed, add lot's auctions for reordering
 *
 * Class MultipleAuctionReorderLotItemObserverHandler
 * @package Sam\AuctionLot\Order\Reorder\Auction\Observe
 */
class MultipleAuctionReorderLotItemObserverHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLotMultipleAuctionReordererCreateTrait;
    use DataLoaderAwareTrait;
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
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $this->triggerReordering($lotItem, true);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $this->triggerReordering($lotItem, false);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['Id', 'ItemNum', 'ItemNumExt']);
    }

    /**
     * @param LotItem $lotItem
     * @param bool $isItemNew
     */
    protected function triggerReordering(LotItem $lotItem, bool $isItemNew): void
    {
        $reorderer = $this->createAuctionLotMultipleAuctionReorderer();
        $auctions = $this->getDataLoader()->loadAuctionsByLotItemId($lotItem->Id);

        foreach ($auctions as $auction) {
            if ($reorderer->isAuctionQueued($auction)) {
                continue;
            }

            $orderByItemNum = $this->createObserverHandlerHelper()
                ->hasAuctionLotOrderType($auction, Constants\Auction::LOT_ORDER_BY_ITEM_NUMBER);
            $orderByCustomField = $isItemNew
                && $this->createObserverHandlerHelper()
                    ->hasAuctionLotOrderType($auction, Constants\Auction::LOT_ORDER_BY_CUST_FIELD);
            if ($orderByItemNum || $orderByCustomField) {
                $reorderer->addAuctionToQueue($auction);
            }
        }
    }
}
