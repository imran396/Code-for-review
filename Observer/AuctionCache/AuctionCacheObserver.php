<?php
/**
 * Observer for AuctionLotItemCache
 */

namespace Sam\Observer\AuctionCache;

use AuctionCache;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\AuctionCache\Internal\AuctionEndDateUpdater;
use Sam\Observer\AuctionCache\Internal\GoogleCalendar;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionCacheObserver
 * @package Sam\Observer\AuctionCache
 */
class AuctionCacheObserver extends CustomizableClass implements SplObserver
{
    use AuctionLoaderAwareTrait;
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionCacheObserver
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
        if (!$subject instanceof AuctionCache) {
            log_warning('Subject not instance of AuctionCache: ' . get_class($subject));
            return;
        }
        /** @var AuctionCache $auctionCache */
        $auctionCache = $subject;
        $auction = $this->getAuctionLoader()->load($auctionCache->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found when processing post-save for AuctionCache"
                . composeSuffix(['a' => $auctionCache->AuctionId])
            );
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new(),
            AuctionEndDateUpdater::new(),
            GoogleCalendar::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
