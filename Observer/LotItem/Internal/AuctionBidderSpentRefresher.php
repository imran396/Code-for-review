<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\Outstanding\Calculator as OutstandingCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Update auction_bidder.spent value for related bidders
 *
 * Class AuctionBidderSpentRefresher
 * @package Sam\Observer\LotItem
 * @internal
 */
class AuctionBidderSpentRefresher extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionBidderLoaderAwareTrait;
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
        $this->refresh($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->refresh($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['WinningBidderId', 'AuctionId', 'HammerPrice']);
    }

    protected function refresh(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $outstandingCalculator = OutstandingCalculator::new();
        $auctionBidderLoader = $this->getAuctionBidderLoader();
        $auctionBidder = $auctionBidderLoader->load(
            $lotItem->WinningBidderId,
            $lotItem->AuctionId,
            true
        );
        if ($auctionBidder) {
            $auctionBidder->Reload();
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $outstandingCalculator->refreshSpent($auctionBidder, $editorUserId);
        }
        if ($subject->isPropertyModified('AuctionId')) {
            $auctionBidder = $auctionBidderLoader->load(
                $lotItem->WinningBidderId,
                $subject->getOldPropertyValue('AuctionId'),
                true
            );
            if ($auctionBidder) {
                $editorUserId = $this->getUserLoader()->loadSystemUserId();
                $outstandingCalculator->refreshSpent($auctionBidder, $editorUserId);
            }
            if ($subject->isPropertyModified('WinningBidderId')) {
                $auctionBidder = $auctionBidderLoader->load(
                    $subject->getOldPropertyValue('WinningBidderId'),
                    $subject->getOldPropertyValue('AuctionId'),
                    true
                );
                if ($auctionBidder) {
                    $editorUserId = $this->getUserLoader()->loadSystemUserId();
                    $outstandingCalculator->refreshSpent($auctionBidder, $editorUserId);
                }
            }
        }
        if ($subject->isPropertyModified('WinningBidderId')) {
            $auctionBidder = $auctionBidderLoader->load(
                $subject->getOldPropertyValue('WinningBidderId'),
                $lotItem->AuctionId,
                true
            );
            if ($auctionBidder) {
                $editorUserId = $this->getUserLoader()->loadSystemUserId();
                $outstandingCalculator->refreshSpent($auctionBidder, $editorUserId);
            }
        }
    }
}
