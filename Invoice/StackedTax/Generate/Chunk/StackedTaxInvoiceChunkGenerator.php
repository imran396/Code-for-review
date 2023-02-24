<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotelvskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           24.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk;

use Invoice;
use LotItem;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Lock\UniqueInvoiceNoLockerCreateTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing\InvoiceClosingDetectorCreateTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit\LimitCheckerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit\LimitConfig;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\Report\Build\ReportBuilder;
use Sam\Invoice\StackedTax\Generate\Item\Multiple\StackedTaxMultipleInvoiceItemProductionResult;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\BulkGroup\BulkGroupLotItemCollectorCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry\LotPerCountryGrouperCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\InvoiceLineItemChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\Note\InvoiceNoteUpdaterCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\ProcessingCharge\ProcessingChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\ShippingCharge\ShippingChargeProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\BasicInvoice\StackedTaxBasicInvoiceProducerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Multiple\StackedTaxMultipleInvoiceItemProducerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput;
use Sam\Invoice\StackedTax\Generate\Note\Consignor\InvoiceNoteConsignorInfoBuilderAwareTrait;
use Sam\Invoice\StackedTax\Generate\Note\StackedTaxInvoiceNoteBuilderAwareTrait;
use Sam\Invoice\StackedTax\TaxSchema\Detect\StackedTaxInvoiceTaxSchemaDetectorCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Util_Profiler;
use Sam\Invoice\StackedTax\Generate\Chunk\StackedTaxInvoiceChunkGenerationResult as Result;

/**
 * Class InvoiceGenerator
 * @package Sam\Invoice\StackedTax\Generate
 */
class StackedTaxInvoiceChunkGenerator extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use BulkGroupLotItemCollectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use DbConnectionTrait;
    use InvoiceClosingDetectorCreateTrait;
    use LotPerCountryGrouperCreateTrait;
    use InvoiceLineItemChargeProducerCreateTrait;
    use InvoiceNoteConsignorInfoBuilderAwareTrait;
    use InvoiceNoteUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LimitCheckerAwareTrait;
    use LotRendererAwareTrait;
    use ProcessingChargeProducerCreateTrait;
    use SaleGroupManagerAwareTrait;
    use SettingsManagerAwareTrait;
    use ShippingChargeProducerCreateTrait;
    use StackedTaxBasicInvoiceProducerAwareTrait;
    use StackedTaxInvoiceNoteBuilderAwareTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use StackedTaxInvoiceTaxSchemaDetectorCreateTrait;
    use StackedTaxMultipleInvoiceItemProducerAwareTrait;
    use StackedTaxSingleInvoiceItemProducerAwareTrait;
    use UniqueInvoiceNoLockerCreateTrait;

    protected int $profilingLevel;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->profilingLevel = Constants\Debug::TRACE;
        return $this;
    }

    /**
     * Generates multiple invoices
     *
     * @param array $accountIds
     * @param int $editorUserId
     * @param int|null $winnerUserId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param int|null $auctionId
     * @param LimitConfig $limitConfig
     * @param string $language
     * @return Result
     */
    public function generate(
        array $accountIds,
        int $editorUserId,
        ?int $winnerUserId,
        ?string $startDateSysIso,
        ?string $endDateSysIso,
        ?int $auctionId,
        LimitConfig $limitConfig,
        string $language
    ): Result {
        $limitChecker = $this->getLimitChecker()->construct($limitConfig);
        $dataProvider = $this->createDataProvider();
        $result = Result::new();
        $hasRemaining = false;
        log_debug('Generating invoices' . composeSuffix($limitChecker->logData()));

        $auctionIds = (array)$auctionId;
        $result->initAccountIds($accountIds);
        foreach ($accountIds as $accountId) {
            if ($winnerUserId === null) {
                if ($this->cfg()->get('core->invoice->sam5162')) {
                    $winningBidderIds = $dataProvider->loadPreInvoicingWinningUserIdsNew(
                        $accountId,
                        $startDateSysIso,
                        $endDateSysIso,
                        $auctionIds
                    );
                } else {
                    $winningBidderIds = $dataProvider->loadPreInvoicingWinningUserIds(
                        $accountId,
                        $startDateSysIso,
                        $endDateSysIso,
                        $auctionIds
                    );
                }
            } else {
                $winningBidderIds = [$winnerUserId];
            }
            foreach ($winningBidderIds as $winningBidderId) {
                $resultForUser = $this->generateForUser(
                    $accountId,
                    $editorUserId,
                    $winningBidderId,
                    $startDateSysIso,
                    $endDateSysIso,
                    $auctionId,
                    $language
                );

                $result->addResultForAccount($accountId, $resultForUser);

                if ($resultForUser->hasErrorThatMustStopInvoiceGeneration()) {
                    return $result;
                }

                if (
                    $limitChecker->areLimitsExceededAndLog()
                    || $resultForUser->hasRemaining
                ) {
                    $hasRemaining = true;
                    break;
                }
            }
            if (
                $limitChecker->areLimitsExceededAndLog()
                || $hasRemaining
            ) {
                $hasRemaining = true;
                break;
            }
        }

        if ($this->isProfiling()) {
            Util_Profiler::getInstance(true)->printTimers(true, true);
        }

        return $result->setHasRemaining($hasRemaining);
    }

    /**
     * Return text for invoice generating report
     * @param bool $isAccountFiltering
     * @param Result $result
     * @return string
     */
    public function getReport(bool $isAccountFiltering, Result $result): string
    {
        return ReportBuilder::new()->completeReport($isAccountFiltering, $result);
    }

    /**
     * Internal function to process lots from array
     * @param LotItem[] $lotItems
     * @param int $accountId
     * @param int $editorUserId
     * @param int $winningUserId
     * @param bool $isMultipleSale
     * @param bool $isOneGroupedSale
     * @param string $language
     * @param bool $isReadOnlyDb
     * @return Result
     */
    protected function processLots(
        array $lotItems,
        int $accountId,
        int $editorUserId,
        int $winningUserId,
        bool $isMultipleSale,
        bool $isOneGroupedSale,
        string $language,
        bool $isReadOnlyDb = false
    ): Result {
        $invoice = null;
        /** @var StackedTaxSingleInvoiceItemProductionInput[] $invoiceItemProductionInputs */
        $invoiceItemProductionInputs = [];
        $db = $this->getDb();

        $result = Result::new();

        if (!$lotItems) {
            return $result;
        }

        /**
         * Detect invoice country and lot items collection for the invoice with the same country.
         */
        $taxCountry = '';
        $lotItemsWithCountry = $this->createLotPerCountryGrouper()->group($lotItems, $isReadOnlyDb);
        if ($lotItemsWithCountry) {
            $taxCountry = key($lotItemsWithCountry);
        }
        if (!$taxCountry) {
            $logData = [
                'li' => implode(',', ArrayHelper::toArrayByProperty($lotItems, 'Id')),
                'a' => implode(',', array_unique(ArrayHelper::toArrayByProperty($lotItems, 'AuctionId'))),
                'acc' => $accountId,
                'u' => $winningUserId,
            ];
            $message = 'Invoice country cannot be detected' . composeSuffix($logData);
            log_debug($message);
            $result->addErrorMessagesForAccount($accountId, [$message]);
            return $result;
        }
        $logData = [
            'country' => $taxCountry,
            'li' => ArrayHelper::toArrayByProperty($lotItemsWithCountry[$taxCountry], 'Id')
        ];
        log_debug('Invoice country detected' . composeSuffix($logData));

        $processingLotItems = $lotItemsWithCountry[$taxCountry];

        $invoiceProducer = $this->getStackedTaxBasicInvoiceProducer();
        $invoiceClosingDetector = $this->createInvoiceClosingDetector()
            ->construct($isMultipleSale, $isOneGroupedSale);

        // Iterating all lot items
        do {
            $shouldCloseInvoice = false;
            $lotItem = array_shift($processingLotItems);
            $isLot = $lotItem !== null;

            if ($isLot) {
                $shouldCloseInvoice = $invoiceClosingDetector->shouldClose($lotItem->AuctionId);
            }

            // checking that we still have memory:
            if ($this->getLimitChecker()->areLimitsExceededAndLog()) {
                $result->setHasRemaining(true);
                break;
            }

            /**
             * if invoiceItems ready to be stored into a new invoice - do it now.
             * !$isLot means that all lots processed on previous loop iteration - i.e. $processingLotItems is empty.
             * i.e. invoice creation transaction won't be committed, if input lot item array is not completely processed
             */
            if ((
                    !$isLot
                    || $shouldCloseInvoice
                )
                && count($invoiceItemProductionInputs)
            ) {
                // add lot items to this invoice and save it:
                $multipleInvoiceItemProductionResult = $this->produceInvoiceItems($invoice, $invoiceItemProductionInputs, $editorUserId);
                if ($multipleInvoiceItemProductionResult->isSuccess()) {
                    $this->createUniqueInvoiceNoLocker()->unlock($accountId); // #i-lock-5
                    $result->addInvoiceId($invoice->Id);
                    $invoice = null;
                    $invoiceItemProductionInputs = [];
                    // IK, 2023-01: Currently we don't provide invoice item skipping case, but it's possible in future.
                    if ($multipleInvoiceItemProductionResult->hasSkippedLotItems()) {
                        $result->setHasRemaining(true);
                    }
                } else {
                    return $result
                        ->addErrorMessagesForAccount($accountId, [$multipleInvoiceItemProductionResult->errorMessage()])
                        ->addMultipleInvoiceItemProductionResultForAccount($accountId, $multipleInvoiceItemProductionResult);
                }
            }

            // if this is valid lot
            if ($isLot) {
                if ($invoice === null) {
                    // create an empty invoice first:
                    $isLocked = $this->createUniqueInvoiceNoLocker()->lock(null, '', $accountId); // #i-lock-5
                    if (!$isLocked) {
                        log_error('Attempts limit exceeded when trying to get a free lock to create an invoice');
                        return $result->setHasRemaining(true);
                    }

                    $db->TransactionBegin();

                    $invoice = $invoiceProducer->createPersisted($editorUserId, $accountId, $winningUserId, $taxCountry);
                }

                // adding current lot to a new invoice:
                $invoiceItemProductionInputs[] = StackedTaxSingleInvoiceItemProductionInput::new()->construct(
                    $invoice->Id,
                    $lotItem,
                    $lotItem->AuctionId,
                    $winningUserId,
                    $taxCountry,
                    $editorUserId,
                    $language,
                    $isReadOnlyDb
                );
            }
        } while ($isLot);

        return $result;
    }

    /**
     * Generate Invoices
     * @param int $accountId
     * @param int $editorUserId
     * @param int $winningUserId
     * @param string|null $startDateSysIso null means there is no filtering by start date expected
     * @param string|null $endDateSysIso null means there is no filtering by end date expected
     * @param int|null $auctionId null means there is no filtering by auction id
     * @param string $language
     * @return Result
     */
    protected function generateForUser(
        int $accountId,
        int $editorUserId,
        int $winningUserId,
        ?string $startDateSysIso,
        ?string $endDateSysIso,
        ?int $auctionId,
        string $language
    ): Result {
        $profiler = null;
        $sm = $this->getSettingsManager();
        $dataProvider = $this->createDataProvider();
        $isSam5162 = $this->cfg()->get('core->invoice->sam5162');
        $isOneSaleGroupedInvoice = (bool)$sm->get(Constants\Setting::ONE_SALE_GROUPED_INVOICE, $accountId);
        $isMultipleSale = (bool)$sm->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
        $logData = [
            'win u' => $winningUserId,
            'acc' => $accountId,
            'a' => $auctionId,
            'from' => $startDateSysIso,
            'to' => $endDateSysIso,
            'editor u' => $editorUserId,
            'ONE_SALE_GROUPED_INVOICE' => $isOneSaleGroupedInvoice,
            'MULTIPLE_SALE_INVOICE' => $isMultipleSale,
            'core->invoice->sam5162' => $isSam5162,
        ];
        log_debug('Start generating invoice for user' . composeSuffix($logData));

        $isProfiling = $this->isProfiling();
        if ($isProfiling) {
            $profiler = Util_Profiler::getInstance(true);
            $profiler->startTimer("get billable items");
        }

        $auctionIds = (array)$auctionId;
        if ($isSam5162) {
            $lotItems = $dataProvider->loadPreInvoicingLotItemsNew(
                $accountId,
                $winningUserId,
                $startDateSysIso,
                $endDateSysIso,
                $auctionIds
            );
        } else {
            if (
                $isOneSaleGroupedInvoice
                && $auctionId
            ) {
                [$saleGroup, $auctionAccountId] = $dataProvider->loadAuctionRow($auctionId, true);
                if ($saleGroup) {
                    $auctionIds = $dataProvider->loadSaleGroupAuctionIds($saleGroup, $auctionAccountId, true);
                }
            }
            // getting lot items ordered by a.currency, li.auctionId:
            $lotItems = $dataProvider->loadPreInvoicingLotItems(
                $accountId,
                $winningUserId,
                $startDateSysIso,
                $endDateSysIso,
                $auctionIds
            );
        }

        if ($isProfiling) {
            $profiler->stopTimer("get billable items");
        }

        if (count($lotItems) > 0) {
            $lotItems = $this->createBulkGroupLotItemCollector()->addBulkItems($lotItems);
        }

        $lotItemIds = ArrayHelper::toArrayByProperty($lotItems, 'Id');
        log_debug(
            'finished to get billable items'
            . composeSuffix(['count' => count($lotItems), 'li' => $lotItemIds])
        );

        if ($isProfiling) {
            $profiler->stopTimer("get billable items");
        }

        if ($isProfiling) {
            $profiler->startTimer("process lots");
        }

        $resultForUser = $this->processLots(
            $lotItems,
            $accountId,
            $editorUserId,
            $winningUserId,
            $isMultipleSale,
            $isOneSaleGroupedInvoice,
            $language
        );

        if ($isProfiling) {
            $profiler->stopTimer("process lots");
        }

        $resultForUser->setGeneratedCountForAccount($accountId, count($resultForUser->invoiceIds));

        if ($isProfiling) {
            $profiler->startTimer("post invoice generation steps");
        }

        $invoices = $dataProvider->loadInvoices($resultForUser->invoiceIds);

        foreach ($invoices as $invoice) {
            $this->createInvoiceLineItemChargeProducer()->applyExtraFee($invoice, $editorUserId);

            $errorMessage = $this->createShippingChargeProducer()->applyShipping($invoice, $editorUserId);
            if (!empty($errorMessage)) {
                $resultForUser->addErrorMessagesForAccount($accountId, [$errorMessage]);
            }

            $this->createProcessingChargeProducer()->applyProcessingFeeCharge($invoice, $editorUserId);

            $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);

            $invoice = $this->createInvoiceNoteUpdater()->updateNote($invoice);

            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        }

        $invoiceItemsRows = $this->createDataProvider()->loadBillableItemsInvoiced($accountId, $auctionId);
        // add report messages for billable items:
        $this->addReportMessagesForBillableItems($accountId, $invoiceItemsRows, $resultForUser);

        if ($isProfiling) {
            $profiler->stopTimer("post invoice generation steps");
        }

        return $resultForUser;
    }

    /**
     * @param int $accountId
     * @param array $invoiceItemRows
     * @param Result $result
     */
    protected function addReportMessagesForBillableItems(int $accountId, array $invoiceItemRows, Result $result): void
    {
        $reportMessages = ReportBuilder::new()->buildMessagesForBillableItems($invoiceItemRows);
        $result->addReportMessagesForAccount($accountId, $reportMessages);
    }

    /**
     * Generate and persist invoice items, update invoice summary for HP,BP, and commit/rollback transaction if required.
     *
     * @param Invoice $invoice
     * @param StackedTaxSingleInvoiceItemProductionInput[] $invoiceItemProductionInputs
     * @param int $editorUserId
     * @return StackedTaxMultipleInvoiceItemProductionResult
     */
    protected function produceInvoiceItems(
        Invoice $invoice,
        array $invoiceItemProductionInputs,
        int $editorUserId
    ): StackedTaxMultipleInvoiceItemProductionResult {
        $db = $this->getDb();

        if (count($invoiceItemProductionInputs)) {
            $multipleInvoiceItemProductionResult = $this->getStackedTaxMultipleInvoiceItemProducer()->produce($invoiceItemProductionInputs);

            if ($multipleInvoiceItemProductionResult->isSuccess()) {
                $this->getStackedTaxInvoiceSummaryCalculator()->recalculateHpBpTotalsAndSave($invoice, $editorUserId);
                $db->TransactionCommit();
                return $multipleInvoiceItemProductionResult;
            }

            $db->TransactionRollback();
            return $multipleInvoiceItemProductionResult;
        }

        return StackedTaxMultipleInvoiceItemProductionResult::new()->construct();
    }

    protected function isProfiling(): bool
    {
        return $this->cfg()->get('core->general->debugLevel') >= $this->profilingLevel;
    }
}
