<?php
/**
 * Observer for AuctionLotItemCache
 */

namespace Sam\Observer\AuctionLotItemCache;

use AuctionLotItemCache;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\AuctionLotItemCache\Internal\AuctionCacheInvalidator;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionLotItemCacheObserver
 * @package Sam\Observer\AuctionLotItemCache
 */
class AuctionLotItemCacheObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionLotItemCacheObserver
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
        if (!$subject instanceof AuctionLotItemCache) {
            log_warning('Subject not instance of ' . composeLogData(['AuctionLotItemCache' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuctionCacheInvalidator::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
