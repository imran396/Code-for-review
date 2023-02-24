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

namespace Sam\Invoice\Legacy\Generate\Produce\AutoInvoice;

use Email_Template;
use Invoice;
use LotItem;
use QMySqliDatabaseException;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\InvoiceNo\InvoiceNoAdviserAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Lock\UniqueInvoiceNoLockerCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput;
use Sam\Invoice\Legacy\Generate\Note\LegacyInvoiceNoteBuilderAwareTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\BulkGroup\BulkGroupLotItemCollectorCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\InvoiceLineItemCharge\InvoiceLineItemChargeProducerCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\Note\InvoiceNoteUpdaterCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\ProcessingCharge\ProcessingChargeProducerCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\ShippingCharge\ShippingChargeProducerCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\TaxRate\TaxRateApplierCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\BasicInvoice\LegacyBasicInvoiceProducerAwareTrait;
use Sam\Invoice\Common\Validate\InvoiceExistenceCheckerAwareTrait;
use Sam\Reseller\ResellerHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceProducer
 * @package Sam\Invoice\Legacy\Generate
 */
class LegacyAutoInvoiceProducer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use LegacyBasicInvoiceProducerAwareTrait;
    use BulkGroupLotItemCollectorCreateTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use InvoiceExistenceCheckerAwareTrait;
    use InvoiceLineItemChargeProducerCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceNoAdviserAwareTrait;
    use LegacyInvoiceNoteBuilderAwareTrait;
    use InvoiceNoteUpdaterCreateTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use ProcessingChargeProducerCreateTrait;
    use ResellerHelperAwareTrait;
    use ShippingChargeProducerCreateTrait;
    use LegacySingleInvoiceItemProducerAwareTrait;
    use TaxRateApplierCreateTrait;
    use UniqueInvoiceNoLockerCreateTrait;
    use UserLoaderAwareTrait;

    /** @var array */
    protected array $errorMessages = []; // used in createAutoInvoice only

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem $initialLotItem
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     * @throws QMySqliDatabaseException
     */
    public function createAutoInvoice(
        LotItem $initialLotItem,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): ?Invoice {
        // do not create if it is already been added in invoice
        if ($this->getInvoiceExistenceChecker()->existByLotItemId($initialLotItem->Id, $isReadOnlyDb)) {
            log_warning(
                "Failed to create auto invoice. Lot already has been invoiced"
                . composeSuffix(['li' => $initialLotItem->Id])
            );
            return null;
        }

        $winningUserId = $initialLotItem->WinningBidderId;
        // do not create invoice if there is no winning bidder
        if (!$winningUserId) {
            log_warning(
                'Failed to create auto invoice. Winning bidder user not found'
                . composeSuffix(['u' => $initialLotItem->WinningBidderId, 'li' => $initialLotItem->Id])
            );
            return null;
        }

        /** @var LotItem[][] $lotItemsPerUsers */
        $lotItemsPerUsers = [];
        $invoice = null;
        $lotItemsPerUsers[$winningUserId] = $this->createBulkGroupLotItemCollector()->addBulkItems([$initialLotItem]);

        $accountId = $initialLotItem->AccountId;

        $invoiceProducer = $this->getLegacyBasicInvoiceProducer();
        $singleInvoiceProducer = $this->getLegacySingleInvoiceItemProducer();
        $invoicesToRecalc = [];

        foreach ($lotItemsPerUsers as $userId => $lotItems) {
            $invoiceWinningUser = $this->getUserLoader()->load($userId, $isReadOnlyDb);
            if (!$invoiceWinningUser) {
                log_error(
                    "Available invoice winning user not found, when creating auto-invoice"
                    . composeSuffix(['u' => $userId])
                );
                continue;
            }

            $firstLoItem = $lotItems[0];

            $isLocked = $this->createUniqueInvoiceNoLocker()->lock(null, '', $accountId); // #i-lock-4
            if (!$isLocked) {
                log_error('Attempts limit exceeded when trying to get a free lock to create an invoice');
                return null;
            }
            try {
                $invoice = $invoiceProducer->createPersisted(
                    $editorUserId,
                    $accountId,
                    $userId,
                    $firstLoItem->AuctionId,
                    $isReadOnlyDb
                );
            } finally {
                $this->createUniqueInvoiceNoLocker()->unlock($accountId); // #i-lock-4
            }
            $invoicesToRecalc[] = $invoice->Id;

            foreach ($lotItems as $lotItem) {
                $invoiceItemProductionInput = LegacySingleInvoiceItemProductionInput::new()->construct(
                    $invoice->Id,
                    $lotItem,
                    $lotItem->AuctionId,
                    $userId,
                    $editorUserId
                );
                $singleInvoiceProducer->produce($invoiceItemProductionInput);
            }

            $invoice->toPending();

            $this->createInvoiceLineItemChargeProducer()->applyCharges($invoice->Id, $editorUserId);

            $this->applyShipping($invoice, $editorUserId);

            $this->createProcessingChargeProducer()->applyProcessingFeeCharge($invoice, $editorUserId, $isReadOnlyDb);

            $this->notifyByEmail($invoice, $editorUserId);

            $logData = [
                'i' => $invoice->Id,
                'invoice#' => $invoice->InvoiceNo,
                'username' => $invoiceWinningUser->Username,
            ];
            log_info('Auto-invoice created' . composeSuffix($logData));
        }

        //calculate the summary columns for the invoices created and update notes
        foreach ($invoicesToRecalc as $invoiceId) {
            $this->createTaxRateApplier()->applyTaxService($invoiceId, $editorUserId, $isReadOnlyDb);
            $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoiceId, $editorUserId, $isReadOnlyDb);
            $this->createInvoiceNoteUpdater()->update($invoiceId, $editorUserId, $isReadOnlyDb);
        }

        return $invoice;
    }

    protected function applyShipping(Invoice $invoice, int $editorUserId): void
    {
        $errorMessage = $this->createShippingChargeProducer()->applyShipping($invoice, $editorUserId);
        if ($errorMessage) {
            $this->errorMessages[$invoice->AccountId][] = $errorMessage;
        }
    }

    protected function notifyByEmail(Invoice $invoice, int $editorUserId): void
    {
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::INVOICE,
            $editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }
}
