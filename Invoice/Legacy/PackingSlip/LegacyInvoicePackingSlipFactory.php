<?php
/**
 * Packing slip documents factory
 *
 * SAM-4556: Apply \Sam\Invoice\Legacy\PackingSlip namespace
 * SAM-1779: Mossgreen - Packing Slip
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id$
 * @since         Dec 18, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\PackingSlip;

use InvalidArgumentException;
use Invoice;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class LegacyInvoicePackingSlipFactory extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use DateHelperAwareTrait;
    use InvoiceAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceUserLoaderAwareTrait;
    use LocationLoaderAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Get instance
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // We allow to view invoices with deleted user (invoice.bidder)
        $this->getUserLoader()->clear();
        return $this;
    }

    /**
     * Create and output pdf document
     * @param string $outputMode
     */
    public function createPdf(string $outputMode): void
    {
        $errorMessage = '';
        $invoice = $this->getInvoice();
        if ($invoice->isDeleted()) {
            $errorMessage = 'Invoice has been deleted.';
        }

        if ($errorMessage !== '') {
            log_error($errorMessage);
            echo($errorMessage);
            return;
        }

        $pdf = new LegacyInvoicePackingSlipTcpdf($invoice);
        $pdf->setCssFileRootPath(path()->docRoot() . '/admin/css/packing-slip-pdf.css');
        $addressBillingInfo = $this->getAddressInfo($this->getInvoiceUserBillingOrCreate());
        $pdf->setBillingInfo($addressBillingInfo);
        $addressShippingInfo = $this->getAddressInfo($this->getInvoiceUserShippingOrCreate());
        $pdf->setShippingInfo($addressShippingInfo);
        $pdf->setAuctionInfo($this->getAuctionInfo());
        $pdf->setBuyerInfo($this->getBuyerInfo());
        $pdf->setItemsInfo($this->getItemsInfo());

        $pdf->create();
        $pdf->Output(path()->uploadAuctionImage() . '/packing-slip-' . $invoice->Id . '.pdf', $outputMode);
    }
    /*
        public function createHtml() {}
        public function createDoc() {}
    */

    /** *******************
     * Getters and setters
     */

    /**
     * @return int
     */
    public function getInvoiceId(): int
    {
        $invoiceId = $this->getInvoiceAggregate()->getInvoiceId();
        if (!$invoiceId) {
            throw new InvalidArgumentException("Invoice id not defined");
        }
        return $invoiceId;
    }

    /**
     * @return Invoice
     */
    public function getInvoice(): Invoice
    {
        $invoice = $this->getInvoiceAggregate()->getInvoice(true);
        if (!$invoice) {
            throw new InvalidArgumentException("Invoice not found" . composeSuffix(['i' => $this->getInvoiceId()]));
        }
        return $invoice;
    }

    // Protected/private functionality

    /**
     * Return array with address lines for invoice billing or shipping
     * @param InvoiceUserBilling|InvoiceUserShipping $addressInfo InvoiceUserBilling or InvoiceUserShipping object
     * @return array{'name': string, 'cityAddress': string, 'countryAddress': string}
     */
    protected function getAddressInfo(InvoiceUserBilling|InvoiceUserShipping $addressInfo): array
    {
        $city = $addressInfo->City !== '' ? $addressInfo->City . ', ' : '';
        $state = AddressRenderer::new()->stateName($addressInfo->State, $addressInfo->Country);
        $country = AddressRenderer::new()->countryName($addressInfo->Country);
        $fullName = UserPureRenderer::new()->makeFullName($addressInfo->FirstName, $addressInfo->LastName);
        $cityAddress = $addressInfo->Address;
        $countryAddress = trim($city . $state . ' ' . $addressInfo->Zip . ' ' . $country);
        $addressRow = [
            'name' => $fullName,
            'cityAddress' => $cityAddress,
            'countryAddress' => $countryAddress,
        ];
        return $addressRow;
    }

    /**
     * Return array with auctions info
     * @return array(array('name' => <auction name>, 'code' => <sale no>, 'date' => <start date>,
     *     'location' => <auction location>, 'buyerNo' => <bidder num>))
     */
    protected function getAuctionInfo(): array
    {
        $auctionInfo = [];
        $invoicedAuctionDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($this->getInvoiceId());
        foreach ($invoicedAuctionDtos as $invoicedAuctionDto) {
            $startDate = $this->getDateHelper()->convertUtcToTzById(
                $invoicedAuctionDto->detectSaleDate(),
                $invoicedAuctionDto->auctionTimezoneId
            );
            $dateFormatted = $startDate ? $startDate->format('d F Y') : '';

            $auction = $this->getAuctionLoader()->load($invoicedAuctionDto->auctionId);
            $location = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_AUCTION_INVOICE, $auction, true);

            $address = '';
            if ($location) {
                $address = $this->createAddressFormatter()->format(
                    $location->Country,
                    $location->State,
                    $location->City,
                    $location->Zip,
                    $location->Address,
                    false
                );
            }

            $auctionBidder = $this->getAuctionBidderLoader()->load($this->getInvoice()->BidderId, $invoicedAuctionDto->auctionId);
            $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : null;
            $auctionInfo[] = [
                'name' => $invoicedAuctionDto->makeAuctionName(),
                'code' => $invoicedAuctionDto->makeSaleNo(),
                'date' => $dateFormatted,
                'location' => $address,
                'buyerNo' => $bidderNum,
            ];
        }
        return $auctionInfo;
    }

    /**
     * Return winning bidder info
     * @return array('phone' => <user_info.phone>)
     */
    protected function getBuyerInfo(): array
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getInvoice()->BidderId, true);
        $buyerInfo = ['phone' => $userInfo->Phone];
        return $buyerInfo;
    }

    /**
     * Return array with invoice items info
     * @return array(array('name' => <invoice_item.lot_name>, 'lotNum' => <lot#>,
     *     'qty' => <auction_lot_item.quantity>))
     */
    protected function getItemsInfo(): array
    {
        $invoiceItems = $this->getInvoiceItemLoader()
            ->loadDtos($this->getInvoiceId(), true);
        $itemsInfo = [];
        $lotAmountRenderer = $this->createLotAmountRendererFactory()->createForInvoice($this->getInvoice()->AccountId);
        foreach ($invoiceItems as $invoiceItem) {
            $quantity = Floating::gt($invoiceItem->quantity, 0, $invoiceItem->quantityScale)
                ? $lotAmountRenderer->makeQuantity($invoiceItem->quantity, $invoiceItem->quantityScale)
                : '';
            $itemsInfo[] = [
                'name' => $invoiceItem->makeLotName(),
                'lotNum' => $invoiceItem->makeLotNo(),
                'qty' => $quantity,
            ];
        }
        return $itemsInfo;
    }
}
