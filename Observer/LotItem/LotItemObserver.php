<?php

namespace Sam\Observer\LotItem;

use LotItem;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\MultipleAuctionReorderLotItemObserverHandler;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\LotItem\Internal\AuctionBidderSpentRefresher;
use Sam\Observer\LotItem\Internal\AuctionCacheInvalidator;
use Sam\Observer\LotItem\Internal\AuctionLotCacheUpdater;
use Sam\Observer\LotItem\Internal\AuctionLotCountUpdater;
use Sam\Observer\LotItem\Internal\ChangesAgreementProcessor;
use Sam\Observer\LotItem\Internal\LotBidderNotifier;
use Sam\Observer\LotItem\Internal\SearchIndexUpdater;
use Sam\Observer\LotItem\Internal\SitemapCacheInvalidator;
use Sam\Observer\LotItem\Internal\UserAccountStatisticUpdaterForLotItem;
use SplObserver;
use SplSubject;


/**
 * Class LotItemObserver
 * @package Sam\Observer\LotItem
 */
class LotItemObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of LotItemObserver
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
        if (!$subject instanceof LotItem) {
            log_warning(composeLogData(['Subject not instance of LotItem' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuctionBidderSpentRefresher::new(),
            AuctionCacheInvalidator::new(),
            AuctionLotCacheUpdater::new(),
            AuctionLotCountUpdater::new(),
            ChangesAgreementProcessor::new(),
            LotBidderNotifier::new(),
            LotSeoUrlInvalidationObserverHandler::new(),
            MultipleAuctionReorderLotItemObserverHandler::new(),
            SearchIndexUpdater::new(),
            SitemapCacheInvalidator::new(),
            UserAccountStatisticUpdaterForLotItem::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
