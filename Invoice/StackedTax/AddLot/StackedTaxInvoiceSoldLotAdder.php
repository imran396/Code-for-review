<?php
/**
 * SAM-10902: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the "Add New Sold Lots" button action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\AddLot;

use Invoice;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\AddLot\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\AddLot\Internal\Save\DataSaverCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput;
use Sam\Invoice\StackedTax\AddLot\StackedTaxInvoiceSoldLotAddingResult as Result;

class StackedTaxInvoiceSoldLotAdder extends CustomizableClass
{
    use DbConnectionTrait;
    use DataProviderCreateTrait;
    use DataSaverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Invoice $invoice
     * @param int $editorUserId
     * @param string $language
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function add(
        Invoice $invoice,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): Result {
        $this->transactionBegin();
        $result = $this->doAdd($invoice, $editorUserId, $language, $isReadOnlyDb);
        if ($result->hasError()) {
            $this->transactionRollback();
        } else {
            $this->transactionCommit();
        }
        return $result;
    }

    protected function doAdd(
        Invoice $invoice,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): Result {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $dataSaver = $this->createDataSaver();
        $invoicedAuctionIds = $dataProvider->loadInvoicedAuctionIds($invoice->Id, $isReadOnlyDb);
        $wonLotItems = $dataProvider->loadPreInvoicingLotItems(
            $invoice->AccountId,
            $invoice->BidderId,
            null,
            null,
            $invoicedAuctionIds
        );
        $isMultipleSaleInvoice = $dataProvider->isMultipleSaleInvoice($invoice->AccountId);

        if (!$isMultipleSaleInvoice) {
            foreach ($wonLotItems as $key => $wonLotItem) {
                if (!$wonLotItem->isSaleSoldAuctionAmong($invoicedAuctionIds)) {
                    unset($wonLotItems[$key]);
                }
            }
            array_multisort($wonLotItems);
        }

        array_multisort($wonLotItems);

        foreach ($wonLotItems as $wonLotItem) {
            $invoiceItemProductionInput = StackedTaxSingleInvoiceItemProductionInput::new()->construct(
                $invoice->Id,
                $wonLotItem,
                $wonLotItem->AuctionId,
                $wonLotItem->WinningBidderId,
                $invoice->TaxCountry,
                $editorUserId,
                $language,
                $isReadOnlyDb
            );
            $invoiceProductionResult = $dataSaver->produceSingleInvoice($invoiceItemProductionInput);
            if ($invoiceProductionResult->hasError()) {
                return $result->addInvoiceProductionError($invoiceProductionResult);
            }
        }

        $count = count($wonLotItems);
        if ($count) {
            $dataSaver->recalculateSummary($invoice, $editorUserId);
        }

        return $result->setAddedLotCount($count);
    }
}
