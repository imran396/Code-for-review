<?php
/**
 * Observer for AuctionLotItem
 */

namespace Sam\Observer\AuctionLotItem;

use AuctionLotItem;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\MultipleAuctionReorderAuctionLotObserverHandler;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\AuctionLotItem\Internal\AuctionCacheInvalidator;
use Sam\Observer\AuctionLotItem\Internal\AuctionLotCacheUpdater;
use Sam\Observer\AuctionLotItem\Internal\AuctionLotCountProcessor;
use Sam\Observer\AuctionLotItem\Internal\BulkGroupLotDateUpdater;
use Sam\Observer\AuctionLotItem\Internal\LotBidderNotifier;
use Sam\Observer\AuctionLotItem\Internal\LotStatusChangeLogger;
use Sam\Observer\AuctionLotItem\Internal\SitemapCacheInvalidator;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionLotItemObserver
 * @package Sam\Observer\AuctionLotItem
 */
class AuctionLotItemObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionLotItemObserver
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof AuctionLotItem) {
            log_warning('Subject not instance of AuctionLotItem: ' . get_class($subject));
            return;
        }

        $handlers = [
            AuctionCacheInvalidator::new(),
            AuctionLotCacheUpdater::new(),
            AuctionLotCountProcessor::new(),
            BulkGroupLotDateUpdater::new(),
            LotBidderNotifier::new(),
            LotSeoUrlInvalidationObserverHandler::new(),
            LotStatusChangeLogger::new(),
            MultipleAuctionReorderAuctionLotObserverHandler::new(),
            SitemapCacheInvalidator::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
