<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Produce\BasicInvoice;

use Invoice;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Common\InvoiceNo\InvoiceNoAdviserAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Reseller\ResellerHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

class LegacyBasicInvoiceProducer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use InvoiceNoAdviserAwareTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use ResellerHelperAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generates single invoice
     * @param int $winningUserId
     * @param int $accountId
     * @param int $editorUserId
     * @param int|null $auctionId null means that bidder isn't an approved reseller when certified as auction bidder and invoice bidder number is unknown
     * @param bool $isReadOnlyDb
     * @return Invoice
     */
    public function createPersisted(
        int $editorUserId,
        int $accountId,
        int $winningUserId,
        ?int $auctionId = null,
        bool $isReadOnlyDb = false
    ): Invoice {
        $invoice = $this->createInvoice($accountId, $winningUserId, $auctionId, $isReadOnlyDb);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $invoiceUserProducer = $this->getInvoiceUserProducer();
        $invoiceUserProducer->createPersistedInvoiceUserBillingAndInitByWinningUser($invoice->Id, $editorUserId, $isReadOnlyDb);
        $invoiceUserProducer->createPersistedInvoiceUserShippingAndInitByWinningUser($invoice->Id, $editorUserId, $isReadOnlyDb);

        //add the default payment options
        $invoicePaymentMethodManager = $this->getInvoicePaymentMethodManager();
        $approvedMethods = $invoicePaymentMethodManager->detectApprovedPaymentMethods($invoice->AccountId);
        $invoicePaymentMethodManager->savePaymentMethods($invoice->Id, $approvedMethods, $editorUserId);

        return $invoice;
    }

    protected function createInvoice(
        int $accountId,
        int $winningUserId,
        ?int $auctionId = null,
        bool $isReadOnlyDb = false
    ): Invoice {
        $invoice = $this->createEntityFactory()->invoice();
        $invoice->AccountId = $accountId;
        $invoice->BidderId = $winningUserId;
        $invoice->BidderNumber = $this->detectBidderNum($accountId, $winningUserId, $auctionId, $isReadOnlyDb);
        $invoice->InvoiceNo = $this->getInvoiceNoAdviser()->suggest($accountId, $isReadOnlyDb);
        $invoice->SalesTax = $this->detectSalesTax($accountId, $winningUserId, $auctionId, $isReadOnlyDb);
        $invoice->toOpen();
        return $invoice;
    }

    /**
     * Determine percentage value of sales tax rate.
     */
    protected function detectSalesTax(int $accountId, int $winningUserId, ?int $auctionId, bool $isReadOnlyDb = false): float
    {
        $isValidReseller = $this->getResellerHelper()->isValidReseller($winningUserId, $auctionId, $isReadOnlyDb);
        $salesTaxPercent = $isValidReseller
            ? 0. // no sales tax for valid reseller
            : (float)$this->getSettingsManager()->get(Constants\Setting::SALES_TAX, $accountId);
        return $salesTaxPercent;
    }

    protected function detectBidderNum(int $accountId, int $winningUserId, ?int $auctionId, bool $isReadOnlyDb = false): string
    {
        $isMultipleSale = (bool)$this->getSettingsManager()->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
        $auctionBidder = $this->getAuctionBidderLoader()->load($winningUserId, $auctionId, $isReadOnlyDb);
        if (
            $isMultipleSale
            || !$auctionBidder
        ) {
            return '';
        }

        return (string)$auctionBidder->BidderNum;
    }
}
