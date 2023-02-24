<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\Common\Goods\Load;

use LotItemCustData;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemData
 * @package Sam\Invoice\StackedTax\View\Common\Goods\Load
 */
class InvoiceItemData extends CustomizableClass
{
    public readonly ?float $quantity;
    public readonly ?int $bpTaxSchemaId;
    public readonly ?int $hpTaxSchemaId;
    public readonly bool $isRelease;
    public readonly float $bp;
    public readonly float $bpCityTaxAmount;
    public readonly float $bpCountryTaxAmount;
    public readonly float $bpCountyTaxAmount;
    public readonly float $bpStateTaxAmount;
    public readonly float $bpTaxAmount;
    public readonly float $hp;
    public readonly float $hpCityTaxAmount;
    public readonly float $hpCountryTaxAmount;
    public readonly float $hpCountyTaxAmount;
    public readonly float $hpStateTaxAmount;
    public readonly float $hpTaxAmount;
    public readonly int $accountId;
    public readonly int $auctionId;
    public readonly int $auctionStatusId;
    public readonly int $eventType;
    public readonly int $id;
    public readonly int $invoiceId;
    public readonly int $lotItemId;
    public readonly int $lotStatusId;
    public readonly int $quantityScale;
    public readonly string $auctionName;
    public readonly string $auctionTimezoneLocation;
    public readonly string $auctionType;
    public readonly string $bidderNum;
    public readonly string $itemNo;
    public readonly string $lotName;
    public readonly string $lotNo;
    public readonly string $saleDateIso;
    public readonly string $saleNo;
    public readonly string $taxCountry;
    /** @var LotItemCustData[] */
    public readonly array $customFieldsData;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustData[] $customFieldsData
     */
    public function construct(array $dbRow, array $customFieldsData): InvoiceItemData
    {
        $invoiceItemData = self::new();
        $invoiceItemData->accountId = (int)$dbRow['account_id'];
        $invoiceItemData->auctionId = (int)$dbRow['auction_id'];
        $invoiceItemData->auctionName = (string)$dbRow['name'];
        $invoiceItemData->auctionTimezoneLocation = (string)$dbRow['timezone_location'];
        $invoiceItemData->auctionType = (string)$dbRow['auction_type'];
        $invoiceItemData->bidderNum = (string)$dbRow['bidder_num'];
        $invoiceItemData->bp = (float)$dbRow['buyers_premium'];
        $invoiceItemData->bpCityTaxAmount = (float)$dbRow['bp_city_tax_amount'];
        $invoiceItemData->bpCountryTaxAmount = (float)$dbRow['bp_country_tax_amount'];
        $invoiceItemData->bpCountyTaxAmount = (float)$dbRow['bp_county_tax_amount'];
        $invoiceItemData->bpStateTaxAmount = (float)$dbRow['bp_state_tax_amount'];
        $invoiceItemData->bpTaxAmount = (float)$dbRow['bp_tax_amount'];
        $invoiceItemData->bpTaxSchemaId = Cast::toInt($dbRow['bp_tax_schema_id']);
        $invoiceItemData->eventType = (int)$dbRow['event_type'];
        $invoiceItemData->hp = (float)$dbRow['hammer_price'];
        $invoiceItemData->hpCityTaxAmount = (float)$dbRow['hp_city_tax_amount'];
        $invoiceItemData->hpCountryTaxAmount = (float)$dbRow['hp_country_tax_amount'];
        $invoiceItemData->hpCountyTaxAmount = (float)$dbRow['hp_county_tax_amount'];
        $invoiceItemData->hpStateTaxAmount = (float)$dbRow['hp_state_tax_amount'];
        $invoiceItemData->hpTaxAmount = (float)$dbRow['hp_tax_amount'];
        $invoiceItemData->hpTaxSchemaId = Cast::toInt($dbRow['hp_tax_schema_id']);
        $invoiceItemData->id = (int)$dbRow['id'];
        $invoiceItemData->invoiceId = (int)$dbRow['invoice_id'];
        $invoiceItemData->isRelease = (bool)$dbRow['release'];
        $invoiceItemData->itemNo = (string)$dbRow['item_no'];
        $invoiceItemData->lotItemId = (int)$dbRow['lot_item_id'];
        $invoiceItemData->lotName = (string)$dbRow['lot_name'];
        $invoiceItemData->lotNo = (string)$dbRow['lot_no'];
        $invoiceItemData->lotStatusId = (int)$dbRow['lot_status_id'];
        $invoiceItemData->quantity = Cast::toFloat($dbRow['quantity']);
        $invoiceItemData->quantityScale = (int)$dbRow['quantity_digits'];
        $invoiceItemData->saleDateIso = (string)$dbRow['sale_date'];
        $invoiceItemData->saleNo = (string)$dbRow['sale_no'];
        $invoiceItemData->taxCountry = (string)$dbRow['tax_country'];

        $invoiceItemData->customFieldsData = $customFieldsData;
        return $invoiceItemData;
    }

    public function calcHpBp(): float
    {
        return StackedTaxInvoicePureCalculator::new()
            ->calcHpBp($this->hp, $this->bp);
    }

    /*
     * Commented out the logic of initial implementation of the Stacked Tax v3.7
     *
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
    */

    public function calcCountryTaxAmount(): float
    {
        return $this->hpCountryTaxAmount + $this->bpCountryTaxAmount;
    }

    public function calcStateTaxAmount(): float
    {
        return $this->hpStateTaxAmount + $this->bpStateTaxAmount;
    }

    public function calcCountyTaxAmount(): float
    {
        return $this->hpCountyTaxAmount + $this->bpCountyTaxAmount;
    }

    public function calcCityTaxAmount(): float
    {
        return $this->hpCityTaxAmount + $this->bpCityTaxAmount;
    }

    public function getCustomFieldData(int $customFieldId): ?LotItemCustData
    {
        return $this->customFieldsData[$customFieldId] ?? null;
    }
}
