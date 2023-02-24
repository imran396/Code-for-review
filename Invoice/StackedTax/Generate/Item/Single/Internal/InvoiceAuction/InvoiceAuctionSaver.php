<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceAuction;

use Auction;
use InvoiceAuction;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\InvoiceAuction\InvoiceAuctionWriteRepositoryAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceAuction\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput as Input;

class InvoiceAuctionSaver extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use DataProviderCreateTrait;
    use InvoiceAuctionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create auction snapshot in invoice and saves it in DB, or update existing snapshot of auction.
     *
     * @param Input $input
     * @return InvoiceAuction|null null, when the "Sale Sold" auction of lot item is not defined.
     */
    public function snapshotAuctionPersisted(Input $input): ?InvoiceAuction
    {
        $dataProvider = $this->createDataProvider();
        $auction = $dataProvider->loadAuction($input->lotItem->AuctionId, $input->isReadOnlyDb);
        if (!$auction) {
            return null;
        }

        $invoiceAuction = $this->snapshotAuction($input, $auction);
        $this->getInvoiceAuctionWriteRepository()->saveWithModifier($invoiceAuction, $input->editorUserId);
        return $invoiceAuction;
    }

    /**
     * Create or update auction's existing snapshot information.
     * @param Input $input
     * @param Auction $auction
     * @return InvoiceAuction
     */
    protected function snapshotAuction(Input $input, Auction $auction): InvoiceAuction
    {
        $dataProvider = $this->createDataProvider();
        $timezoneLocation = $dataProvider->loadTimezoneLocation($auction->TimezoneId, $input->isReadOnlyDb);
        $invoiceAuction = $dataProvider->loadInvoiceAuction($input->invoiceId, $auction->Id, $input->isReadOnlyDb)
            ?: $dataProvider->newInvoiceAuction();
        $bidderNumPad = $dataProvider->loadAuctionBidderNumPadded($input->userId, $auction->Id);
        $invoiceAuction->AuctionId = $auction->Id;
        $invoiceAuction->AuctionType = $auction->AuctionType;
        $invoiceAuction->BidderNum = $bidderNumPad;
        $invoiceAuction->EventType = $auction->EventType;
        $invoiceAuction->InvoiceId = $input->invoiceId;
        $invoiceAuction->Name = $this->getAuctionRenderer()->renderName($auction);
        $invoiceAuction->SaleDate = $auction->isTimed() ? $auction->EndDate : $auction->StartClosingDate;
        $invoiceAuction->SaleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $invoiceAuction->TimezoneLocation = $timezoneLocation;
        return $invoiceAuction;
    }
}
