<?php

namespace Sam\Observer\AuctionCustData;

use AuctionCustData;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AuctionCustDataObserver
 * @package Sam\Observer\AuctionCustData
 */
class AuctionCustDataObserver extends CustomizableClass implements SplObserver
{
    use AuctionLoaderAwareTrait;
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AuctionObserver
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
        if (!$subject instanceof AuctionCustData) {
            log_warning('Subject not instance of ' . composeLogData(['AuctionCustData' => get_class($subject)]));
            return;
        }
        $auctionCustomData = $subject;
        $auction = $this->getAuctionLoader()->load($auctionCustomData->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found when processing post-save for AuctionCache"
                . composeSuffix(['a' => $auctionCustomData->AuctionId])
            );
            return;
        }

        $handlers = [
            AuctionDetailsCacheInvalidatorObserverHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
