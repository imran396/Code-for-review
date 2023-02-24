<?php

namespace Sam\Observer\SettingAuction;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\SettingAuction\Internal\AuditTrailLogger;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SettingAuction;
use SplObserver;
use SplSubject;

/**
 * Class SettingAuctionObserver
 * @package Sam\Observer\SettingAuction
 */
class SettingAuctionObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of SettingAuctionObserver
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * SplObserver update method
     * @param SplSubject $subject
     * @see SplObserver::update()
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof SettingAuction) {
            log_warning('Subject not instance of ' . composeLogData(['SettingAuction' => get_class($subject)]));
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new(),
            AuditTrailLogger::new(),
            LotSeoUrlInvalidationObserverHandler::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
