<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem;

use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\InvoiceItemSavingResult as Result;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput as Input;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxCalculationResultSnapshotMakerCreateTrait;

/**
 * Class InvoiceItemSaver
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem
 */
class InvoiceItemSaver extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LotRendererAwareTrait;
    use TaxCalculationResultSnapshotMakerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creates invoice item based on PreInvoiceItemDto data
     *
     * @param Input $input
     * @return Result
     */
    public function produceInvoiceItem(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $lotItem = $input->lotItem;

        $hpTaxSchema = $dataProvider->detectHpTaxSchema($lotItem->Id, $lotItem->AuctionId);
        $bpTaxSchema = $dataProvider->detectBpTaxSchema($lotItem->Id, $lotItem->AuctionId);
        $clarificationData = [
            'li' => $lotItem->Id,
            'a' => $lotItem->AuctionId,
            'acc' => $lotItem->AccountId,
        ];
        if (
            !$hpTaxSchema
            || !$bpTaxSchema
        ) {
            if (!$hpTaxSchema) {
                $result->addError(Result::ERR_HP_TAX_SCHEMA_NOT_FOUND, $clarificationData);
            }
            if (!$bpTaxSchema) {
                $result->addError(Result::ERR_BP_TAX_SCHEMA_NOT_FOUND, $clarificationData);
            }
            return $result;
        }

        if ($hpTaxSchema->Country !== $input->taxCountry) {
            $clarificationData = array_merge($clarificationData, [
                'hp ts' => $hpTaxSchema->Id,
                'ts country' => $hpTaxSchema->Country,
                'i country' => $input->taxCountry,
            ]);
            $result->addError(Result::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH, $clarificationData);
            return $result;
        }

        if ($bpTaxSchema->Country !== $input->taxCountry) {
            $clarificationData = array_merge($clarificationData, [
                'bp ts' => $bpTaxSchema->Id,
                'ts country' => $bpTaxSchema->Country,
                'i country' => $input->taxCountry,
            ]);
            $result->addError(Result::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH, $clarificationData);
            return $result;
        }

        $hammerPrice = (float)$lotItem->HammerPrice;
        $hpTax = $dataProvider->calcHpTax($hammerPrice, $hpTaxSchema);

        $buyersPremium = $this->calculateBuyersPremium($input);
        $bpTax = $dataProvider->calcBpTax($buyersPremium, $bpTaxSchema);

        // Tag item to invoice
        $invoiceItem = $dataProvider->newInvoiceItem();
        $invoiceItem->AuctionId = $lotItem->AuctionId;

        $invoiceItem->BuyersPremium = $buyersPremium;
        $invoiceItem->BpTaxAmount = $bpTax->getTaxAmount();
        $invoiceItem->BpCountryTaxAmount = $bpTax->getCountryTaxAmount();
        $invoiceItem->BpStateTaxAmount = $bpTax->getStateTaxAmount();
        $invoiceItem->BpCountyTaxAmount = $bpTax->getCountyTaxAmount();
        $invoiceItem->BpCityTaxAmount = $bpTax->getCityTaxAmount();

        $invoiceItem->HammerPrice = $hammerPrice;
        $invoiceItem->HpTaxAmount = $hpTax->getTaxAmount();
        $invoiceItem->HpCountryTaxAmount = $hpTax->getCountryTaxAmount();
        $invoiceItem->HpStateTaxAmount = $hpTax->getStateTaxAmount();
        $invoiceItem->HpCountyTaxAmount = $hpTax->getCountyTaxAmount();
        $invoiceItem->HpCityTaxAmount = $hpTax->getCityTaxAmount();

        $invoiceItem->InvoiceId = $input->invoiceId;
        $invoiceItem->LotItemId = $lotItem->Id;
        $invoiceItem->LotName = $dataProvider->buildLotName($lotItem->Name, $lotItem->Description, $lotItem->AccountId);
        $invoiceItem->ItemNo = $this->getLotRenderer()->renderItemNo($lotItem);

        // Auction Lot record may not present in auction where lot is marked as sold
        $auctionLot = $dataProvider->loadAuctionLot($lotItem->Id, $lotItem->AuctionId);
        if ($auctionLot) {
            $invoiceItem->LotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
            $invoiceItem->Quantity = $auctionLot->Quantity;
            $invoiceItem->QuantityDigits = $dataProvider->loadAuctionLotQuantityScale($lotItem->Id, $lotItem->AuctionId);
        } else {
            $invoiceItem->Quantity = $lotItem->Quantity;
            $invoiceItem->QuantityDigits = $dataProvider->loadLotItemQuantityScale($lotItem->Id);
        }

        $invoiceItem->Release = false;
        $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()
            ->calc($hammerPrice, $buyersPremium, $hpTax->getTaxAmount() + $bpTax->getTaxAmount());
        $invoiceItem->WinningBidderId = $lotItem->WinningBidderId;

        $invoiceItem->toActive();
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $input->editorUserId);

        $hpTaxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceItem(
            $hpTax,
            $invoiceItem->Id,
            $input->invoiceId,
            $lotItem->Id,
            $input->editorUserId,
            $input->language
        );
        $bpTaxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceItem(
            $bpTax,
            $invoiceItem->Id,
            $input->invoiceId,
            $lotItem->Id,
            $input->editorUserId,
            $input->language
        );
        $invoiceItem->HpTaxSchemaId = $hpTaxSchemaSnapshot->Id;
        $invoiceItem->BpTaxSchemaId = $bpTaxSchemaSnapshot->Id;
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $input->editorUserId);

        return $result->setInvoiceItem($invoiceItem);
    }

    protected function calculateBuyersPremium(Input $input): float
    {
        $dataProvider = $this->createDataProvider();
        $lotItem = $input->lotItem;
        $accountId = $lotItem->AccountId;
        $buyersPremium = $dataProvider->calcBuyersPremium($lotItem->Id, $input->auctionId, $input->userId, $accountId);
        $postAucImportPremium = $dataProvider->loadPostAucImportPremium($input->userId, $input->auctionId);
        if ($postAucImportPremium) {
            $hammerPrice = (float)$lotItem->HammerPrice;
            $buyersPremium += $hammerPrice * ($postAucImportPremium / 100);
        }
        return $buyersPremium;
    }
}
