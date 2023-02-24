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

namespace Sam\Observer\InvoiceItem\Internal;

use InvoiceItem;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\Outstanding\Calculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Update auction_bidder.collected value for winning bidder
 *
 * Class AuctionBidderCollectedUpdater
 * @package Sam\Observer\InvoiceItem
 * @internal
 */
class AuctionBidderCollectedUpdater extends CustomizableClass
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
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['HammerPrice', 'WinningBidderId', 'AuctionId', 'Active', 'Release']);
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function update(EntityObserverSubject $subject): void
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = $subject->getEntity();
        $auctionId = $invoiceItem->AuctionId;
        $userId = $invoiceItem->WinningBidderId;
        $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId);
        if ($auctionBidder) {
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            Calculator::new()->refreshCollected($auctionBidder, $editorUserId);
        }
    }
}
