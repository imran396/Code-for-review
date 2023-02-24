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

namespace Sam\Invoice\StackedTax\Generate\Produce\AutoInvoice;

use Email_Template;
use Invoice;
use LotItem;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Invoice\Common\InvoiceNo\InvoiceNoAdviserAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Lock\UniqueInvoiceNoLockerCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput;
use Sam\Invoice\StackedTax\Generate\Note\StackedTaxInvoiceNoteBuilderAwareTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\BulkGroup\BulkGroupLotItemCollectorCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry\LotPerCountryGrouperCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\InvoiceLineItemChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\Note\InvoiceNoteUpdaterCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\ProcessingCharge\ProcessingChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\ShippingCharge\ShippingChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\BasicInvoice\StackedTaxBasicInvoiceProducerAwareTrait;
use Sam\Invoice\Common\Validate\InvoiceExistenceCheckerAwareTrait;
use Sam\Reseller\ResellerHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class StackedTaxAutoInvoiceProducer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use StackedTaxBasicInvoiceProducerAwareTrait;
    use BulkGroupLotItemCollectorCreateTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use LotPerCountryGrouperCreateTrait;
    use InvoiceExistenceCheckerAwareTrait;
    use InvoiceLineItemChargeProducerCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceNoAdviserAwareTrait;
    use StackedTaxInvoiceNoteBuilderAwareTrait;
    use InvoiceNoteUpdaterCreateTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use ProcessingChargeProducerCreateTrait;
    use ResellerHelperAwareTrait;
    use ShippingChargeProducerCreateTrait;
    use StackedTaxSingleInvoiceItemProducerAwareTrait;
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
     * TODO: apply and test
     * @param LotItem $initialLotItem
     * @param int $editorUserId
     * @param string $language
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function createAutoInvoice(
        LotItem $initialLotItem,
        int $editorUserId,
        string $language,
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

        $invoiceProducer = $this->getStackedTaxBasicInvoiceProducer();
        $singleInvoiceProducer = $this->getStackedTaxSingleInvoiceItemProducer();

        foreach ($lotItemsPerUsers as $userId => $lotItems) {
            $invoiceWinningUser = $this->getUserLoader()->load($userId, $isReadOnlyDb);
            if (!$invoiceWinningUser) {
                log_error(
                    "Available invoice winning user not found, when creating auto-invoice"
                    . composeSuffix(['u' => $userId])
                );
                continue;
            }

            $lotItemsWithCountry = $this->createLotPerCountryGrouper()->group($lotItems, $isReadOnlyDb);
            foreach ($lotItemsWithCountry as $taxCountry => $taxCountryLotItems) {
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
                        $taxCountry,
                        $isReadOnlyDb
                    );
                } finally {
                    $this->createUniqueInvoiceNoLocker()->unlock($accountId); // #i-lock-4
                }

                foreach ($taxCountryLotItems as $lotItem) {
                    $invoiceItemProductionInput = StackedTaxSingleInvoiceItemProductionInput::new()->construct(
                        $invoice->Id,
                        $lotItem,
                        $lotItem->AuctionId,
                        $userId,
                        $invoice->TaxCountry,
                        $editorUserId,
                        $language,
                        $isReadOnlyDb
                    );
                    $singleInvoiceProductionResult = $singleInvoiceProducer->produce($invoiceItemProductionInput);
                    if ($singleInvoiceProductionResult->hasError()) {
                        $logData = ['li' => $initialLotItem->Id, 'a' => $initialLotItem->AuctionId, 'acc' => $initialLotItem->AccountId];
                        log_debug(
                            'Failed to add in lot item with auction - ' . $singleInvoiceProductionResult->errorMessage()
                            . ', Input is: ' . composeLogData($logData)
                        );
                        return null;
                    }
                }

                /**
                 * Calculate HP/BP for Invoice earlier, because we may need these values for some of Charge calculations (Processing Fee Charge is based on HP+BP)
                 */
                $summaryCalculator = $this->getStackedTaxInvoiceSummaryCalculator();
                $invoice = $summaryCalculator->calcHpBpTotals($invoice, $isReadOnlyDb);

                $invoice->toPending();

                $this->createInvoiceLineItemChargeProducer()->applyExtraFee($invoice, $editorUserId);

                $this->applyShipping($invoice, $editorUserId);

                $this->createProcessingChargeProducer()->applyProcessingFeeCharge($invoice, $editorUserId);

                $invoice = $summaryCalculator->recalculateInvoice($invoice, $isReadOnlyDb);

                $invoice = $this->createInvoiceNoteUpdater()->updateNote($invoice);

                $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

                $this->notifyByEmail($invoice, $editorUserId);

                $logData = [
                    'i' => $invoice->Id,
                    'invoice#' => $invoice->InvoiceNo,
                    'username' => $invoiceWinningUser->Username,
                ];
                log_info('Auto-invoice created' . composeSuffix($logData));
            }
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
