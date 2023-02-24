<?php
/**
 * The class responsibility is merging two or more selected invoice to one new invoice and delete old selected invoices.
 * It also updates invoice related entities by new merged invoice.
 *
 * SAM-11142: Stacked Tax. Invoice Management pages. Merge invoices
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26-08-2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Merge\Save;

use Invoice;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Invoice\Legacy\Merge\Save\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Merge\Save\StackedTaxInvoiceMergingSaveResult as Result;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAuction\InvoiceAuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;

class StackedTaxInvoiceMergingSaver extends CustomizableClass
{
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use EntityFactoryCreateTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use InvoiceAuctionWriteRepositoryAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentWriteRepositoryAwareTrait;

    // --- Constructor ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        return $this;
    }

    // --- Main method ---

    /**
     * @param Invoice[] $validInvoices
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function save(array $validInvoices, int $editorUserId, bool $isReadOnlyDb = false): Result
    {
        $result = StackedTaxInvoiceMergingSaveResult::new()->construct();
        $invoiceIds = $this->getInvoiceIds($validInvoices);
        $firstInvoice = $validInvoices[0];
        $result = $this->produceMergedInvoiceEntity($validInvoices, $editorUserId, $isReadOnlyDb, $result);
        $approvedMethods = $this->createDataProvider()->loadPaymentMethods($result->mergedInvoice->AccountId);
        $this->getInvoicePaymentMethodManager()->savePaymentMethods($result->mergedInvoice->Id, $approvedMethods, $editorUserId);

        // create for new invoice user billing and shipping info, coping it from source invoice
        $this->getInvoiceUserProducer()
            ->createPersistedInvoiceUserBillingAndInitByInvoice($result->mergedInvoice->Id, $firstInvoice->Id, $editorUserId, $isReadOnlyDb);

        $this->getInvoiceUserProducer()
            ->createPersistedInvoiceUserShippingAndInitByInvoice($result->mergedInvoice->Id, $firstInvoice->Id, $editorUserId, $isReadOnlyDb);

        $result = $this->updateInvoiceAuctions($invoiceIds, $editorUserId, $isReadOnlyDb, $result);
        $result = $this->updateInvoiceExtraCharges($invoiceIds, $editorUserId, $isReadOnlyDb, $result);
        $result = $this->updateInvoiceItems($invoiceIds, $editorUserId, $isReadOnlyDb, $result);
        $result = $this->updatePayments($invoiceIds, $editorUserId, $isReadOnlyDb, $result);
        $result = $this->deleteOldInvoices($validInvoices, $editorUserId, $result);
        $result->addSuccess(Result::OK_MERGED);
        return $result;
    }

    // --- Commands ---

    /**
     * @param Invoice[] $validInvoices
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param Result $result
     * @return Result
     */
    protected function produceMergedInvoiceEntity(array $validInvoices, int $editorUserId, bool $isReadOnlyDb, Result $result): Result
    {
        $firstInvoice = $validInvoices[0];
        $mergedInvoice = $this->createEntityFactory()->invoice();
        $mergedInvoice->toTaxSchemaTaxDesignation();
        $mergedInvoice->AccountId = $firstInvoice->AccountId;
        $mergedInvoice->BidderId = $firstInvoice->BidderId;
        $mergedInvoice->CurrencySign = $firstInvoice->CurrencySign;
        $mergedInvoice->ExRate = $firstInvoice->ExRate;
        $mergedInvoice->InvoiceNo = $this->createDataProvider()->suggestInvoiceNo($firstInvoice->AccountId, $isReadOnlyDb);
        $mergedInvoice->toOpen();
        foreach ($validInvoices as $invoice) {
            $mergedInvoice->BidTotal += $invoice->BidTotal;
            $mergedInvoice->BuyersPremium += $invoice->BuyersPremium;
            $mergedInvoice->ExtraCharges += $invoice->ExtraCharges;
            $mergedInvoice->Note .= $invoice->Note . "\n";
            $mergedInvoice->Tax += $invoice->Tax;
            $mergedInvoice->TotalPayment += $invoice->TotalPayment;
            $mergedInvoice->HpTaxTotal += $invoice->HpTaxTotal;
            $mergedInvoice->BpTaxTotal += $invoice->BpTaxTotal;
            $mergedInvoice->ServicesTaxTotal += $invoice->ServicesTaxTotal;
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($mergedInvoice, $editorUserId);
        $logData = [
            'i' => $mergedInvoice->Id,
            'invoice#' => $mergedInvoice->InvoiceNo,
            'date' => $this->getCurrentDateUtcIso() . 'UTC',
        ];
        log_info('Created invoice on merge ' . composeSuffix($logData));
        $result->mergedInvoice = $mergedInvoice;
        return $result;
    }

    /**
     * @param int[] $invoiceIds
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param Result $result
     * @return Result
     */
    protected function updateInvoiceAuctions(array $invoiceIds, int $editorUserId, bool $isReadOnlyDb, Result $result): Result
    {
        $mergedInvoiceAuctionIds = [];
        $mergedInvoiceAuctions = [];
        $invoiceAuctions = $this->createDataProvider()->loadInvoiceAuctions($invoiceIds, $isReadOnlyDb);
        foreach ($invoiceAuctions as $invoiceAuction) {
            // A merged invoice must contain only one InvoiceAuction record per auction
            if (in_array($invoiceAuction->AuctionId, $mergedInvoiceAuctionIds, true)) {
                continue;
            }
            $invoiceAuction->InvoiceId = $result->mergedInvoice->Id;
            $this->getInvoiceAuctionWriteRepository()->saveWithModifier($invoiceAuction, $editorUserId);
            $mergedInvoiceAuctionIds[] = $invoiceAuction->AuctionId;
            $mergedInvoiceAuctions[] = $invoiceAuction;
        }
        $result->mergedInvoiceAuctions = $mergedInvoiceAuctions;
        return $result;
    }

    /**
     * @param int[] $invoiceIds
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param Result $result
     * @return Result
     */
    protected function updateInvoiceExtraCharges(array $invoiceIds, int $editorUserId, bool $isReadOnlyDb, Result $result): Result
    {
        $extraCharges = $this->createDataProvider()->loadInvoiceAdditionals($invoiceIds, $isReadOnlyDb);
        foreach ($extraCharges as $extraCharge) {
            $extraCharge->InvoiceId = $result->mergedInvoice->Id;
            $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($extraCharge, $editorUserId);
        }
        $result->mergedInvoiceExtraCharges = $extraCharges;
        return $result;
    }

    /**
     * @param int[] $invoiceIds
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param Result $result
     * @return Result
     */
    protected function updateInvoiceItems(array $invoiceIds, int $editorUserId, bool $isReadOnlyDb, Result $result): Result
    {
        $invoiceItems = $this->createDataProvider()->loadInvoiceItems($invoiceIds, $isReadOnlyDb);
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem->InvoiceId = $result->mergedInvoice->Id;
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }
        $result->mergedInvoiceItems = $invoiceItems;
        return $result;
    }

    /**
     * @param Invoice[] $validInvoices
     * @param int $editorUserId
     * @param Result $result
     * @return Result
     */
    protected function deleteOldInvoices(array $validInvoices, int $editorUserId, Result $result): Result
    {
        // delete old invoices after merge
        foreach ($validInvoices as $invoice) {
            $invoice->toDeleted();
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        }
        $result->deletedInvoices = $validInvoices;
        return $result;
    }

    /**
     * @param int[] $invoiceIds
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param Result $result
     * @return Result
     */
    protected function updatePayments(array $invoiceIds, int $editorUserId, bool $isReadOnlyDb, Result $result): Result
    {
        $payments = $this->createDataProvider()->loadPayments($invoiceIds, $isReadOnlyDb);
        foreach ($payments as $payment) {
            $payment->TranId = $result->mergedInvoice->Id;
            $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);
        }
        $result->mergedInvoicePayments = $payments;
        return $result;
    }

    // --- Internal logic ---

    /**
     * @param Invoice[] $validInvoices
     * @return array
     */
    protected function getInvoiceIds(array $validInvoices): array
    {
        return array_map(
            static function (Invoice $invoice) {
                return $invoice->Id;
            },
            $validInvoices
        );
    }
}
