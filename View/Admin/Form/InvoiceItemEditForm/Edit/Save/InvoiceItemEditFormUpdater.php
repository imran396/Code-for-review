<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Save;

use InvoiceItem;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceAuction;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoiceItem;
use Sam\Invoice\Common\Load\InvoiceAuctionLoaderCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAuction\InvoiceAuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculatorCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxCalculationResultSnapshotMakerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Dto\InvoiceItemEditFormInput;

/**
 * Class InvoiceItemEditFormUpdater
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Save
 */
class InvoiceItemEditFormUpdater extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use InvoiceAuctionLoaderCreateTrait;
    use InvoiceAuctionWriteRepositoryAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use StackedTaxCalculatorCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use TaxCalculationResultSnapshotMakerCreateTrait;
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(InvoiceItemEditFormInput $input): InvoiceItem
    {
        $isReadOnlyDb = $input->isReadOnlyDb;
        $invoiceItemId = $input->invoiceItemId;
        $editorUserId = $input->editorUserId;
        $language = $input->language;

        $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemId);
        if (!$invoiceItem) {
            throw CouldNotFindInvoiceItem::withId($invoiceItemId);
        }

        $invoiceAuction = $this->createInvoiceAuctionLoader()->load($invoiceItem->InvoiceId, $invoiceItem->AuctionId);
        if (!$invoiceAuction) {
            throw CouldNotFindInvoiceAuction::withInvoiceIdAndAuctionId($invoiceItem->InvoiceId, $invoiceItem->AuctionId);
        }

        $numberFormatter = $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);
        $invoiceItem->BuyersPremium = $numberFormatter->parseMoney($input->buyersPremium);
        $invoiceItem->HammerPrice = $numberFormatter->parseMoney($input->hammerPrice);
        $invoiceItem->ItemNo = $input->itemNo;
        $invoiceItem->LotName = $input->lotName;
        $invoiceItem->LotNo = $input->lotNo;
        $invoiceItem->Quantity = $numberFormatter->parse($input->quantity, $input->quantityDigits);
        $invoiceItem->QuantityDigits = $input->quantityDigits;

        $hpTaxSchema = $this->createTaxSchemaLoader()->load($input->hpTaxSchemaId, $isReadOnlyDb);
        if ($hpTaxSchema) {
            $hpTax = $this->createStackedTaxCalculator()->calculate($invoiceItem->HammerPrice, $hpTaxSchema);
            $hpTaxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceItem(
                $hpTax,
                $invoiceItem->Id,
                $invoiceItem->InvoiceId,
                $invoiceItem->LotItemId,
                $editorUserId,
                $language
            );
            $invoiceItem->HpTaxSchemaId = $hpTaxSchemaSnapshot->Id;
            $invoiceItem->HpTaxAmount = $hpTax->getTaxAmount();
        } else {
            $invoiceItem->HpTaxSchemaId = null;
            $invoiceItem->HpTaxAmount = 0.;
        }

        $bpTaxSchema = $this->createTaxSchemaLoader()->load($input->bpTaxSchemaId, $isReadOnlyDb);
        if ($bpTaxSchema) {
            $bpTax = $this->createStackedTaxCalculator()->calculate($invoiceItem->BuyersPremium, $bpTaxSchema);
            $bpTaxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceItem(
                $bpTax,
                $invoiceItem->Id,
                $invoiceItem->InvoiceId,
                $invoiceItem->LotItemId,
                $editorUserId,
                $language
            );
            $invoiceItem->BpTaxSchemaId = $bpTaxSchemaSnapshot->Id;
            $invoiceItem->BpTaxAmount = $bpTax->getTaxAmount();
        } else {
            $invoiceItem->BpTaxSchemaId = null;
            $invoiceItem->BpTaxAmount = 0.;
        }

        $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()->calc(
            $invoiceItem->HammerPrice,
            $invoiceItem->BuyersPremium,
            $invoiceItem->HpTaxAmount + $invoiceItem->BpTaxAmount
        );
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);

        $invoiceAuction->BidderNum = $this->getBidderNumberPadding()->add($input->bidderNum);
        $this->getInvoiceAuctionWriteRepository()->saveWithModifier($invoiceAuction, $editorUserId);

        $this->getStackedTaxInvoiceSummaryCalculator()->recalculateAndSave($invoiceItem->InvoiceId, $editorUserId);
        return $invoiceItem;
    }
}
