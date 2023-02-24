<?php
/**
 * The class responsibility is merging two or more selected invoice to one new invoice and delete old selected invoices.
 * It also updates invoice related entities by new merged invoice.
 * SAM-7978 : Decouple invoice merging service and apply unit tests
 * https://bidpath.atlassian.net/browse/SAM-7978
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Merge\Save;

use Invoice;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Legacy\Merge\Save\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Invoice\Legacy\Merge\Save\LegacyInvoiceMergingSaveResult as Result;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAuction\InvoiceAuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;

class LegacyInvoiceMergingSaver extends CustomizableClass
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
        $result = LegacyInvoiceMergingSaveResult::new()->construct();
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
        $mergedInvoice->AccountId = $firstInvoice->AccountId;
        $mergedInvoice->BidderId = $firstInvoice->BidderId;
        if ($this->isSameBidderNumber($validInvoices)) {
            $mergedInvoice->BidderNumber = $firstInvoice->BidderNumber;
        }
        $mergedInvoice->CurrencySign = $firstInvoice->CurrencySign;
        $mergedInvoice->InvoiceNo = $this->createDataProvider()->suggestInvoiceNo($firstInvoice->AccountId, $isReadOnlyDb);
        $mergedInvoice->toOpen();
        $count = 0;
        foreach ($validInvoices as $invoice) {
            $mergedInvoice->BidTotal += $invoice->BidTotal;
            $mergedInvoice->BuyersPremium += $invoice->BuyersPremium;
            $mergedInvoice->ExRate += $invoice->ExRate;
            $mergedInvoice->ExtraCharges += $invoice->ExtraCharges;
            $mergedInvoice->Note .= $invoice->Note . "\n";
            $mergedInvoice->SalesTax += $invoice->SalesTax;
            $mergedInvoice->Shipping += $invoice->Shipping;
            $mergedInvoice->ShippingFees += $invoice->ShippingFees;
            $mergedInvoice->Tax += $invoice->Tax;
            $mergedInvoice->TotalPayment += $invoice->TotalPayment;
            $count++;
        }
        $mergedInvoice->ExRate /= $count;
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
        $invoiceAuctions = $this->createDataProvider()->loadInvoiceAuctions($invoiceIds, $isReadOnlyDb);
        foreach ($invoiceAuctions as $invoiceAuction) {
            $invoiceAuction->InvoiceId = $result->mergedInvoice->Id;
            $this->getInvoiceAuctionWriteRepository()->saveWithModifier($invoiceAuction, $editorUserId);
        }
        $result->mergedInvoiceAuctions = $invoiceAuctions;
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
     * Check that all invoices in validInvoices have the same bidder number
     * @param Invoice[] $validInvoices
     * @return bool
     */
    protected function isSameBidderNumber(array $validInvoices): bool
    {
        $prevBidderNumber = null;
        foreach ($validInvoices as $invoice) {
            if (
                $prevBidderNumber !== null
                && $invoice->BidderNumber !== $prevBidderNumber
            ) {
                return false;
            }
            $prevBidderNumber = $invoice->BidderNumber;
        }
        return true;
    }

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
