<?php

namespace Sam\Observer\BidTransaction;

use BidTransaction;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\BidTransaction\Internal\AuctionCacheInvalidator;
use Sam\Observer\BidTransaction\Internal\AuctionLotCacheUpdater;
use Sam\Observer\BidTransaction\Internal\UserAccountStatisticUpdaterForBidTransaction;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class BidTransactionObserver
 * @package Sam\Observer\BidTransaction
 */
class BidTransactionObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of BidTransactionObserver
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
        if (!$subject instanceof BidTransaction) {
            log_warning('Subject not instance of BidTransaction: ' . get_class($subject));
            return;
        }
        $handlers = [
            AuctionCacheInvalidator::new(),
            AuctionLotCacheUpdater::new(),
            UserAccountStatisticUpdaterForBidTransaction::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
