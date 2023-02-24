<?php

namespace Sam\Observer\SamTaxCountryStates;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SamTaxCountryStates;
use SplSubject;

/**
 * Observer for SamTaxCountryStates
 *
 * Class AuctionCacheObserver
 * @package Sam\Observer\SamTaxCountryStates
 */
class SamTaxCountryStatesObserver extends CustomizableClass implements \SplObserver
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
        if (!$subject instanceof SamTaxCountryStates) {
            log_warning('Subject not instance of SamTaxCountryStates: ' . get_class($subject));
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
