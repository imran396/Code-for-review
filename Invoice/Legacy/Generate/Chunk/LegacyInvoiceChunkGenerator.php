<?php
/**
 * SAM-9667: Refactor invoice generation module for v3-6
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Chunk;

use Invoice;
use LotItem;
use QMySqliDatabaseException;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\Lock\UniqueInvoiceNoLockerCreateTrait;
use Sam\Invoice\Legacy\Generate\Chunk\Internal\DetectClosing\InvoiceClosingDetectorCreateTrait;
use Sam\Invoice\Legacy\Generate\Chunk\Internal\Limit\LimitCheckerAwareTrait;
use Sam\Invoice\Legacy\Generate\Chunk\Internal\Limit\LimitConfig;
use Sam\Invoice\Legacy\Generate\Chunk\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Legacy\Generate\Chunk\Internal\Report\Build\ReportBuilder;
use Sam\Invoice\Legacy\Generate\Produce\Internal\BulkGroup\BulkGroupLotItemCollectorCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\InvoiceLineItemCharge\InvoiceLineItemChargeProducerCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\Note\InvoiceNoteUpdaterCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\Internal\TaxRate\TaxRateApplierCreateTrait;
use Sam\Invoice\Legacy\Generate\Produce\BasicInvoice\LegacyBasicInvoiceProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\LegacyMultipleInvoiceItemProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput;
use Sam\Invoice\Legacy\Generate\Note\Consignor\InvoiceNoteConsignorInfoBuilderAwareTrait;
use Sam\Invoice\Legacy\Generate\Note\LegacyInvoiceNoteBuilderAwareTrait;
use Sam\Invoice\Common\Shipping\Save\InvoiceShippingRateProducerAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Util_Profiler;
use \Sam\Invoice\Legacy\Generate\Chunk\LegacyInvoiceChunkGenerationResult as Result;

class LegacyInvoiceChunkGenerator extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use LegacyBasicInvoiceProducerAwareTrait;
    use BulkGroupLotItemCollectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use DbConnectionTrait;
    use InvoiceClosingDetectorCreateTrait;
    use InvoiceLineItemChargeProducerCreateTrait;
    use LegacyInvoiceNoteBuilderAwareTrait;
    use InvoiceNoteConsignorInfoBuilderAwareTrait;
    use InvoiceNoteUpdaterCreateTrait;
    use InvoiceShippingRateProducerAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use LimitCheckerAwareTrait;
    use LotRendererAwareTrait;
    use LegacyMultipleInvoiceItemProducerAwareTrait;
    use SaleGroupManagerAwareTrait;
    use SettingsManagerAwareTrait;
    use LegacySingleInvoiceItemProducerAwareTrait;
    use TaxRateApplierCreateTrait;
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
     * @return Result
     * @throws QMySqliDatabaseException
     */
    public function generate(
        array $accountIds,
        int $editorUserId,
        ?int $winnerUserId,
        ?string $startDateSysIso,
        ?string $endDateSysIso,
        ?int $auctionId,
        LimitConfig $limitConfig
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
                    $auctionId
                );

                $result->addResultForAccount($accountId, $resultForUser);

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
     * @return Result
     */
    protected function processLots(
        array $lotItems,
        int $accountId,
        int $editorUserId,
        int $winningUserId,
        bool $isMultipleSale,
        bool $isOneGroupedSale
    ): Result {
        $invoice = null;
        /** @var LegacySingleInvoiceItemProductionInput[] $invoiceItemProductionInputs */
        $invoiceItemProductionInputs = [];
        $db = $this->getDb();

        $result = Result::new();

        if (!$lotItems) {
            return $result;
        }

        $invoiceProducer = $this->getLegacyBasicInvoiceProducer();
        $invoiceClosingDetector = $this->createInvoiceClosingDetector()
            ->construct($isMultipleSale, $isOneGroupedSale);

        // Iterating all lot items
        do {
            $shouldCloseInvoice = false;
            $lotItem = array_shift($lotItems);
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
             * !$isLot means that all lots processed on previous loop iteration - i.e. $lotItems is empty.
             * i.e. invoice creation transaction won't be committed, if input lot item array is not completely processed
             */
            if ((
                    !$isLot
                    || $shouldCloseInvoice
                )
                && count($invoiceItemProductionInputs)
            ) {
                // add lot items to this invoice and save it:
                $success = $this->produceInvoiceItemsAndCharges($invoice, $invoiceItemProductionInputs, $editorUserId);
                if ($success) {
                    $this->createUniqueInvoiceNoLocker()->unlock($accountId); // #i-lock-5
                    $result->addInvoiceId($invoice->Id);
                    $invoice = null;
                    $invoiceItemProductionInputs = [];
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

                    $invoice = $invoiceProducer->createPersisted(
                        $editorUserId,
                        $accountId,
                        $winningUserId,
                        $isMultipleSale ? null : $lotItem->AuctionId
                    );
                }

                // adding current lot to a new invoice:
                $invoiceItemProductionInputs[] = LegacySingleInvoiceItemProductionInput::new()->construct(
                    $invoice->Id,
                    $lotItem,
                    $lotItem->AuctionId,
                    $winningUserId,
                    $editorUserId
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
     * @return Result
     * @throws QMySqliDatabaseException
     */
    protected function generateForUser(
        int $accountId,
        int $editorUserId,
        int $winningUserId,
        ?string $startDateSysIso,
        ?string $endDateSysIso,
        ?int $auctionId
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
            $isOneSaleGroupedInvoice
        );

        if ($isProfiling) {
            $profiler->stopTimer("process lots");
        }

        $resultForUser->setGeneratedCountForAccount($accountId, count($resultForUser->invoiceIds));

        if ($isProfiling) {
            $profiler->startTimer("post invoice generation steps");
        }

        $invoices = $dataProvider->loadInvoices($resultForUser->invoiceIds);

        // update shipping rates for created invoices
        $resultForUser = $this->updateShippingRates($accountId, $invoices, $editorUserId, $resultForUser);

        // recalculate the summary columns for the generated invoices + update notes
        foreach ($resultForUser->invoiceIds as $generatedInvoiceId) {
            $this->createTaxRateApplier()->applyTaxService($generatedInvoiceId, $editorUserId);
            $this->getLegacyInvoiceSummaryCalculator()->recalculate($generatedInvoiceId, $editorUserId);
            $this->createInvoiceNoteUpdater()->update($generatedInvoiceId, $editorUserId);
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
    private function addReportMessagesForBillableItems(int $accountId, array $invoiceItemRows, Result $result): void
    {
        $reportMessages = ReportBuilder::new()->buildMessagesForBillableItems($invoiceItemRows);
        $result->addReportMessagesForAccount($accountId, $reportMessages);
    }

    /**
     * @param int $accountId
     * @param Invoice[] $invoices
     * @param int $editorUserId
     * @param Result $result
     * @return Result
     */
    private function updateShippingRates(
        int $accountId,
        array $invoices,
        int $editorUserId,
        Result $result
    ): Result {
        $shippingRateProducer = $this->getInvoiceShippingRateProducer();
        foreach ($invoices as $invoice) {
            $shippingRateProducer->update($invoice, $editorUserId);
            $errorMessage = $shippingRateProducer->getErrorMessage();
            if (!empty($errorMessage)) {
                $result->addErrorMessagesForAccount($accountId, [$errorMessage]);
            }
        }
        return $result;
    }

    /**
     * Stores invoice, trigger required events and commit/rollback transaction if required.
     *
     * @param Invoice $invoice
     * @param LegacySingleInvoiceItemProductionInput[] $invoiceItemProductionInputs
     * @param int $editorUserId
     * @return bool
     */
    private function produceInvoiceItemsAndCharges(
        Invoice $invoice,
        array $invoiceItemProductionInputs,
        int $editorUserId
    ): bool {
        $db = $this->getDb();

        if (count($invoiceItemProductionInputs)) {
            $isAdded = $this->getLegacyMultipleInvoiceItemProducer()->produce($invoiceItemProductionInputs);

            if ($isAdded) {
                $this->createInvoiceLineItemChargeProducer()->applyCharges($invoice->Id, $editorUserId);

                $db->TransactionCommit();
                return true;
            }

            $db->TransactionRollback();
        }

        return false;
    }

    protected function isProfiling(): bool
    {
        return $this->cfg()->get('core->general->debugLevel') >= $this->profilingLevel;
    }
}
