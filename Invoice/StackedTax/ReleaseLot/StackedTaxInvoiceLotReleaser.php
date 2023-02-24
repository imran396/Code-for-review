<?php
/**
 * SAM-10903: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the Release Lot action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\ReleaseLot;

use AuctionLotItem;
use InvoiceItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceItem;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Validate\State\LotStateDetectorCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

class StackedTaxInvoiceLotReleaser extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotStateDetectorCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function release(int $invoiceItemId, int $editorUserId): void
    {
        $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemId);

        if (!$invoiceItem) {
            throw CouldNotFindInvoiceItem::withId($invoiceItemId);
        }

        $this->wipeOutInvoiceItem($invoiceItem, $editorUserId);
        $this->wipeOutLotSoldInfo($invoiceItem->LotItemId, $editorUserId);
        $this->getStackedTaxInvoiceSummaryCalculator()->recalculateAndSave($invoiceItem->InvoiceId, $editorUserId);
    }

    public function wipeOutLotSoldInfo(?int $lotItemId, int $editorUserId): void
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if ($lotItem) {
            $this->resetAuctionLotStatus($lotItem->Id, $lotItem->AuctionId, $editorUserId);
            $lotItem->wipeOutSoldInfo();
            $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        }
    }

    protected function wipeOutInvoiceItem(InvoiceItem $invoiceItem, int $editorUserId): void
    {
        $invoiceItem->BpTaxAmount = 0.;
        $invoiceItem->BuyersPremium = 0.;
        $invoiceItem->HammerPrice = 0.;
        $invoiceItem->HpTaxAmount = 0.;
        $invoiceItem->Subtotal = 0.;
        $invoiceItem->WinningBidderId = null;
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
    }

    protected function resetAuctionLotStatus(int $lotItemId, ?int $auctionId, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if ($auctionLot) {
            if ($this->isLotEnded($auctionLot)) {
                $auctionLot->toUnsold();
            } else {
                $auctionLot->toActive();
            }
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }

    public function isLotEnded(AuctionLotItem $auctionLot): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if ($auction) {
            return $auction->isTimed()
                ? $this->createLotStateDetector()->isLotEnded($auctionLot)
                : $auction->isClosed();
        }
        return false;
    }
}
