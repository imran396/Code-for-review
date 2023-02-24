<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\BidTransaction\Internal;

use BidTransaction;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionLotCacheUpdater
 * @package Sam\Observer\BidTransaction\Internal
 */
class AuctionLotCacheUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->updateAuctionLotCache($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->updateAuctionLotCache($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        if (!$subject->isAnyPropertyModified(['Deleted', 'Bid', 'MaxBid', 'UserId', 'AuctionId', 'BidStatus'])) {
            return false;
        }
        /** @var BidTransaction $bidTransaction */
        $bidTransaction = $subject->getEntity();
        $auctionLot = $this->getAuctionLotLoader()->load($bidTransaction->LotItemId, $bidTransaction->AuctionId);
        $auction = $this->getAuctionLoader()->clear()->load($bidTransaction->AuctionId);

        return $auctionLot
            && $auction
            && $auction->isTimed()
            && ($subject->isPropertyModified('Deleted')  // recount bid
                || $bidTransaction->Id === $auctionLot->CurrentBidId); // changes related to current bid (when we place new bid, it is not called. That is for backend edit transaction)
    }

    protected function updateAuctionLotCache(EntityObserverSubject $subject): void
    {
        /** @var BidTransaction $bidTransaction */
        $bidTransaction = $subject->getEntity();
        $auctionLot = $this->getAuctionLotLoader()->load($bidTransaction->LotItemId, $bidTransaction->AuctionId);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
    }
}
