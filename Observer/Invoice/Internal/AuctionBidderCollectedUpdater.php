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

namespace Sam\Observer\Invoice\Internal;

use Invoice;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\Outstanding\Calculator;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Update auction_bidder.collected value for winning bidder
 *
 * Class AuctionBidderCollectedUpdater
 * @package Sam\Observer\Invoice
 * @internal
 */
class AuctionBidderCollectedUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionBidderLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
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
        $this->updateCollected($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->updateCollected($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var Invoice $invoice */
        $invoice = $subject->getEntity();
        $invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        return $subject->isPropertyModified('InvoiceStatusId')
            && (
                $invoice->isPaid()
                || $invoiceStatusPureChecker->isPaid($subject->getOldPropertyValue('InvoiceStatusId'))
            );
    }

    protected function updateCollected(EntityObserverSubject $subject): void
    {
        /** @var Invoice $invoice */
        $invoice = $subject->getEntity();
        $invoiceItemRows = $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(
            ['ii.auction_id', 'ii.winning_bidder_id'],
            $invoice->Id
        );
        $auctionBidders = [];    // cache loaded auction bidders (all items in invoice should have the same bidder)
        $auctionBidderLoader = $this->getAuctionBidderLoader();
        foreach ($invoiceItemRows as $row) {
            $auctionId = (int)$row['auction_id'];
            $winningUserId = (int)$row['winning_bidder_id'];
            $key = sprintf('%d-%d', $auctionId, $winningUserId);
            if (empty($auctionBidders[$key])) {
                $auctionBidder = $auctionBidderLoader->load($winningUserId, $auctionId);
                // Auction may be unset, hence AuctionBidder not exists
                if ($auctionBidder) {
                    $auctionBidders[$key] = $auctionBidder;
                }
            }
        }
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        foreach ($auctionBidders as $auctionBidder) {
            Calculator::new()->refreshCollected($auctionBidder, $editorUserId);
        }
    }
}
