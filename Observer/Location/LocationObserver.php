<?php

namespace Sam\Observer\Location;

use Location;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class LocationObserver
 * @package Sam\Observer\Location
 */
class LocationObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AbsenteeBidObserver
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
        if (!$subject instanceof Location) {
            log_warning(composeLogData(['Subject not instance of Location' => get_class($subject)]));
            return;
        }
        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
