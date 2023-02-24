<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemData
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Load
 */
class InvoiceItemData extends CustomizableClass
{
    public readonly ?float $quantity;
    public readonly ?int $bpTaxSchemaId;
    public readonly ?int $hpTaxSchemaId;
    public readonly bool $isRelease;
    public readonly float $bp;
    public readonly float $bpTaxAmount;
    public readonly float $hp;
    public readonly float $hpTaxAmount;
    public readonly int $accountId;
    public readonly int $auctionId;
    public readonly int $auctionStatusId;
    public readonly int $eventType;
    public readonly int $id;
    public readonly int $invoiceId;
    public readonly int $lotItemId;
    public readonly int $quantityScale;
    public readonly string $auctionName;
    public readonly string $auctionTimezoneLocation;
    public readonly string $auctionType;
    public readonly string $bidderNum;
    public readonly string $taxCountry;
    public readonly string $itemNo;
    public readonly string $lotName;
    public readonly string $lotNo;
    public readonly string $saleDateIso;
    public readonly string $saleNo;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromDbRow(array $row): InvoiceItemData
    {
        $this->accountId = (int)$row['account_id'];
        $this->auctionId = (int)$row['auction_id'];
        $this->auctionName = (string)$row['name'];
        $this->auctionTimezoneLocation = (string)$row['timezone_location'];
        $this->auctionType = (string)$row['auction_type'];
        $this->bidderNum = (string)$row['bidder_num'];
        $this->bp = (float)$row['buyers_premium'];
        $this->bpTaxAmount = (float)$row['bp_tax_amount'];
        $this->bpTaxSchemaId = Cast::toInt($row['bp_tax_schema_id']);
        $this->eventType = (int)$row['event_type'];
        $this->hp = (float)$row['hammer_price'];
        $this->hpTaxAmount = (float)$row['hp_tax_amount'];
        $this->hpTaxSchemaId = Cast::toInt($row['hp_tax_schema_id']);
        $this->id = (int)$row['id'];
        $this->invoiceId = (int)$row['invoice_id'];
        $this->isRelease = (bool)$row['release'];
        $this->itemNo = (string)$row['item_no'];
        $this->lotItemId = (int)$row['lot_item_id'];
        $this->lotName = (string)$row['lot_name'];
        $this->lotNo = (string)$row['lot_no'];
        $this->quantity = Cast::toFloat($row['quantity']);
        $this->quantityScale = (int)$row['quantity_digits'];
        $this->saleDateIso = (string)$row['sale_date'];
        $this->saleNo = (string)$row['sale_no'];
        $this->taxCountry = (string)$row['tax_country'];
        return $this;
    }

    public function calcHpBp(): float
    {
        return StackedTaxInvoicePureCalculator::new()
            ->calcHpBp($this->hp, $this->bp);
    }

    public function calcHpTaxAmountBpTaxAmount(): float
    {
        return StackedTaxInvoicePureCalculator::new()
            ->calcHpTaxAmountBpTaxAmount($this->hpTaxAmount, $this->bpTaxAmount);
    }

    public function calcHpWithTax(): float
    {
        return StackedTaxInvoicePureCalculator::new()
            ->calcHpWithTax($this->hp, $this->hpTaxAmount);
    }

    public function calcBpWithTax(): float
    {
        return StackedTaxInvoicePureCalculator::new()
            ->calcBpWithTax($this->bp, $this->bpTaxAmount);
    }

    public function calcHpBpWithTax(): float
    {
        return StackedTaxInvoicePureCalculator::new()->calcHpBpWithTax(
            $this->hp,
            $this->bp,
            $this->hpTaxAmount,
            $this->bpTaxAmount
        );
    }
}
