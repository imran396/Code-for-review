<?php

namespace Sam\Observer\AuctionAuctioneer;

/*
 * Observer for AuctionLotItemCache
 */

use AuctionAuctioneer;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionCacheObserver
 * @package Sam\Observer\AuctionCache
 */
class AuctionAuctioneerObserver extends CustomizableClass implements SplObserver
{
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
        if (!$subject instanceof AuctionAuctioneer) {
            log_warning('Subject not instance of AuctionAuctioneer: ' . get_class($subject));
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
