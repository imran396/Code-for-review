<?php

namespace Sam\Observer\LotItemCustData;

use LotItemCustData;
use Sam\AuctionLot\Order\Reorder\Auction\Observe\MultipleAuctionReorderLotCustomDataObserverHandler;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class LotItemCustDataObserver
 * @package Sam\Observer
 */
class LotItemCustDataObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of LotItemObserver
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
        if (!$subject instanceof LotItemCustData) {
            log_warning(composeLogData(['Subject not instance of LotItemCustData' => get_class($subject)]));
            return;
        }

        $handlers = [
            LotSeoUrlInvalidationObserverHandler::new(),
            MultipleAuctionReorderLotCustomDataObserverHandler::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
