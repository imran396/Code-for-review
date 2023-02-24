<?php

namespace Sam\Observer\AuctionBidder;

use AuctionBidder;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\AuctionBidder\Internal\AuctionCacheInvalidator;
use Sam\Observer\AuctionBidder\Internal\UserAccountStatisticUpdaterForAuctionBidder;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionBidderObserver
 * @package Sam\Observer\AuctionBidder
 */
class AuctionBidderObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionBidderObserver
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
        if (!$subject instanceof AuctionBidder) {
            log_warning('Subject not instance of AuctionBidder: ' . get_class($subject));
            return;
        }

        $handlers = [
            AuctionCacheInvalidator::new(),
            UserAccountStatisticUpdaterForAuctionBidder::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
