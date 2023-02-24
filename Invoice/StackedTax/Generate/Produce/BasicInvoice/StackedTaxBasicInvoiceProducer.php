<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\BasicInvoice;

use Invoice;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Common\InvoiceNo\InvoiceNoAdviserAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

class StackedTaxBasicInvoiceProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use InvoiceNoAdviserAwareTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;

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
     * @param int $editorUserId
     * @param int $accountId
     * @param int $winningUserId
     * @param string $taxCountry
     * @param bool $isReadOnlyDb
     * @return Invoice
     */
    public function createPersisted(
        int $editorUserId,
        int $accountId,
        int $winningUserId,
        string $taxCountry,
        bool $isReadOnlyDb = false
    ): Invoice {
        $invoice = $this->createInvoice($accountId, $winningUserId, $taxCountry, $isReadOnlyDb);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $invoiceUserProducer = $this->getInvoiceUserProducer();
        $invoiceUserProducer->createPersistedInvoiceUserAndInitByWinningUser($invoice->Id, $editorUserId, $isReadOnlyDb);
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
        string $taxCountry,
        bool $isReadOnlyDb = false
    ): Invoice {
        $invoice = $this->createEntityFactory()->invoice();
        $invoice->AccountId = $accountId;
        $invoice->BidderId = $winningUserId;
        $invoice->TaxCountry = $taxCountry;
        // TODO: don't save bidder# per invoice, but per ii in invoice_item.bidder_num
        // $invoice->BidderNumber = $this->detectBidderNum($accountId, $winningUserId, $auctionId, $isReadOnlyDb);
        $invoice->InvoiceNo = $this->getInvoiceNoAdviser()->suggest($accountId, $isReadOnlyDb);
        // TODO: we can think, how we want to user this field
        // $invoice->SalesTax = $this->detectSalesTax($accountId, $winningUserId, $auctionId, $isReadOnlyDb);
        $invoice->toTaxSchemaTaxDesignation();
        $invoice->toOpen();
        return $invoice;
    }
}
