<?php

namespace Sam\Observer\Auction;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\Auction\Internal\AuctionCacheInvalidator;
use Sam\Observer\Auction\Internal\AuctionLotDateInfoCacheUpdater;
use Sam\Observer\Auction\Internal\AuctionLotSitemapCacheInvalidator;
use Sam\Observer\Auction\Internal\AuctionSitemapCacheInvalidator;
use Sam\Observer\Auction\Internal\AuditTrailLogger;
use Sam\Observer\Auction\Internal\CacheInitializer;
use Sam\Observer\Auction\Internal\GoogleCalendar;
use Sam\Observer\Auction\Internal\LotItemAuctionInfoUpdater;
use Sam\Observer\Auction\Internal\SearchIndexUpdater;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionObserver
 * @package Sam\Observer
 */
class AuctionObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionObserver
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
        if (!$subject instanceof Auction) {
            log_warning('Subject not instance of Auction: ' . get_class($subject));
            return;
        }

        $handlers = [
            AuctionCacheInvalidator::new(),
            AuctionDetailsCacheInvalidatorObserverHandler::new(),
            AuctionLotDateInfoCacheUpdater::new(),
            AuctionLotSitemapCacheInvalidator::new(),
            AuctionSitemapCacheInvalidator::new(),
            AuditTrailLogger::new(),
            CacheInitializer::new(),
            GoogleCalendar::new(),
            LotItemAuctionInfoUpdater::new(),
            LotSeoUrlInvalidationObserverHandler::new(),
            SearchIndexUpdater::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
