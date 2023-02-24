<?php

namespace Sam\Observer\TermsAndConditions;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;
use TermsAndConditions;

/**
 * Class TermsAndCondtionsObserver
 * @package Sam\Observer\TermsAndConditions
 */
class TermsAndConditionsObserver extends CustomizableClass implements SplObserver
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
     * @param SplSubject|TermsAndConditions $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof TermsAndConditions) {
            log_warning(composeLogData(['Subject not instance of TermsAndConditions' => get_class($subject)]));
            return;
        }
        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
