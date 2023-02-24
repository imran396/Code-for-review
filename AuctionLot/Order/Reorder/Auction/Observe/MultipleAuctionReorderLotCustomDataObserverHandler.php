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

use LotItemCustData;
use Sam\AuctionLot\Order\Reorder\Auction\AuctionLotMultipleAuctionReordererCreateTrait;
use Sam\AuctionLot\Order\Reorder\Auction\Load\DataLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal\ObserverHandlerHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Core\Constants;

/**
 * If ordering related custom field data changed, add auction for reordering
 *
 * Class LotCustomDataMultipleAuctionReorderObserverHandler
 * @package Sam\AuctionLot\Order\Reorder\Auction\Observe
 */
class MultipleAuctionReorderLotCustomDataObserverHandler extends CustomizableClass
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
        $this->triggerReordering($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->triggerReordering($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->getEntity() instanceof LotItemCustData
            && $subject->isAnyPropertyModified(['Numeric', 'Text']);
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function triggerReordering(EntityObserverSubject $subject): void
    {
        /** @var LotItemCustData $lotCustData */
        $lotCustData = $subject->getEntity();
        $reorderer = $this->createAuctionLotMultipleAuctionReorderer();
        $auctions = $this->getDataLoader()->loadAuctionsByLotItemId($lotCustData->LotItemId);
        foreach ($auctions as $auction) {
            if (
                !$reorderer->isAuctionQueued($auction)
                && $this->createObserverHandlerHelper()->hasAuctionLotOrderType($auction, Constants\Auction::LOT_ORDER_BY_CUST_FIELD)
                && $this->createObserverHandlerHelper()->hasAuctionLotOrderCustFieldId($auction, $lotCustData->LotItemCustFieldId)
            ) {
                $reorderer->addAuctionToQueue($auction);
            }
        }
    }
}
