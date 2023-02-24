<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
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

namespace Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem;

use InvoiceItem;
use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceItemTaxCalculationAmountsDto;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput as Input;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;

/**
 * Class InvoiceItemSaver
 * @package Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem
 */
class InvoiceItemSaver extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceItemWriteRepositoryAwareTrait;

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
     * @return InvoiceItem
     */
    public function produceInvoiceItem(Input $input): InvoiceItem
    {
        $dataProvider = $this->createDataProvider();
        $lotItem = $input->lotItem;
        $hammerPrice = (float)$lotItem->HammerPrice;
        $buyersPremium = $this->calculateBuyersPremium($input);
        $taxResult = $dataProvider->detectApplicableSalesTax(
            $input->userId,
            $lotItem->Id,
            $input->auctionId
        );
        $dto = InvoiceItemTaxCalculationAmountsDto::new()->construct(
            $hammerPrice,
            $buyersPremium,
            $taxResult->percent,
            $taxResult->application
        );
        $salesTaxAmount = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedByAmountsDto($dto);
        // Tag item to invoice
        $invoiceItem = $dataProvider->newInvoiceItem();
        $invoiceItem->AuctionId = $lotItem->AuctionId;
        $invoiceItem->BuyersPremium = $buyersPremium;
        $invoiceItem->HammerPrice = $hammerPrice;
        $invoiceItem->InvoiceId = $input->invoiceId;
        $invoiceItem->LotItemId = $lotItem->Id;
        $invoiceItem->LotName = $dataProvider->buildLotName($lotItem->Name, $lotItem->Description, $lotItem->AccountId);
        $invoiceItem->Release = false;
        $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()->calc($hammerPrice, $buyersPremium, $salesTaxAmount);
        $invoiceItem->WinningBidderId = $lotItem->WinningBidderId;
        $invoiceItem->applyApplicableSaleTaxResult($taxResult);
        $invoiceItem->toActive();
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $input->editorUserId);
        return $invoiceItem;
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
