<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\Common\Goods;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\View\Common\Goods\Load\InvoiceItemData;

/**
 * Class InvoiceGoodsSubtotal
 * @package Sam\Invoice\StackedTax\View\Common\Goods
 */
class InvoiceGoodsSubtotal extends CustomizableClass
{
    public readonly ?float $hammerPrice;
    public readonly ?float $buyersPremium;
//    public readonly ?float $hammerPriceTaxAmount;
//    public readonly ?float $buyersPremiumTaxAmount;
    public readonly float $hpCountryTaxAmount;
    public readonly float $hpStateTaxAmount;
    public readonly float $hpCountyTaxAmount;
    public readonly float $hpCityTaxAmount;
    public readonly float $bpCountryTaxAmount;
    public readonly float $bpStateTaxAmount;
    public readonly float $bpCountyTaxAmount;
    public readonly float $bpCityTaxAmount;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param InvoiceItemData[] $invoiceItemDtos
     * @return static
     */
    public function construct(array $invoiceItemDtos): static
    {
        $hammerPrice = 0;
        $buyersPremium = 0;
//        $hammerPriceTaxAmount = 0;
//        $buyersPremiumTaxAmount = 0;
        $hpCountryTaxAmount = 0;
        $hpStateTaxAmount = 0;
        $hpCountyTaxAmount = 0;
        $hpCityTaxAmount = 0;
        $bpCountryTaxAmount = 0;
        $bpStateTaxAmount = 0;
        $bpCountyTaxAmount = 0;
        $bpCityTaxAmount = 0;
        foreach ($invoiceItemDtos as $invoiceItemDto) {
            $hammerPrice += $invoiceItemDto->hp;
            $buyersPremium += $invoiceItemDto->bp;
//            $hammerPriceTaxAmount += $invoiceItemDto->hpTaxAmount;
//            $buyersPremiumTaxAmount += $invoiceItemDto->bpTaxAmount;
            $hpCountryTaxAmount += $invoiceItemDto->hpCountryTaxAmount;
            $hpStateTaxAmount += $invoiceItemDto->hpStateTaxAmount;
            $hpCountyTaxAmount += $invoiceItemDto->hpCountyTaxAmount;
            $hpCityTaxAmount += $invoiceItemDto->hpCityTaxAmount;
            $bpCountryTaxAmount += $invoiceItemDto->bpCountryTaxAmount;
            $bpStateTaxAmount += $invoiceItemDto->bpStateTaxAmount;
            $bpCountyTaxAmount += $invoiceItemDto->bpCountyTaxAmount;
            $bpCityTaxAmount += $invoiceItemDto->bpCityTaxAmount;
        }
        $this->hammerPrice = $hammerPrice;
        $this->buyersPremium = $buyersPremium;
//        $this->hammerPriceTaxAmount = $hammerPriceTaxAmount;
//        $this->buyersPremiumTaxAmount = $buyersPremiumTaxAmount;
        $this->hpCountryTaxAmount = $hpCountryTaxAmount;
        $this->hpStateTaxAmount = $hpStateTaxAmount;
        $this->hpCountyTaxAmount = $hpCountyTaxAmount;
        $this->hpCityTaxAmount = $hpCityTaxAmount;
        $this->bpCountryTaxAmount = $bpCountryTaxAmount;
        $this->bpStateTaxAmount = $bpStateTaxAmount;
        $this->bpCountyTaxAmount = $bpCountyTaxAmount;
        $this->bpCityTaxAmount = $bpCityTaxAmount;
        return $this;
    }

    public function calcHpWithBp(): float
    {
        return $this->hammerPrice + $this->buyersPremium;
    }

    /*
     * Commented out logic of v3.7 Stacked Tax implementation.
     * Remove in further releases.
     *
    public function calcHpBpTaxAmount(): float
    {
        return $this->hammerPriceTaxAmount + $this->buyersPremiumTaxAmount;
    }

    public function calcHpWithTax(): float
    {
        return $this->hammerPrice + $this->hammerPriceTaxAmount;
    }

    public function calcBpWithTax(): float
    {
        return $this->buyersPremium + $this->buyersPremiumTaxAmount;
    }

    public function calcHpBpWithTax(): float
    {
        return $this->hammerPrice
            + $this->hammerPriceTaxAmount
            + $this->buyersPremium
            + $this->buyersPremiumTaxAmount;
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

    /**
     * IK, 2022-12: These methods can be useful for column hide/show decision based on tax amount collected at some geo level.
     * We may add configuration option "show_zero" = false, and hide columns with zero tax amount.
     * We don't need such logic now, thus methods are commented out.
     *
     * Check, if tax amount is collected at some geo level for HP and/or BP amount sources.
     * @param int $geoType
     * @return bool
     *
    public function hasGeoTaxCollected(int $geoType): bool
    {
        return match ($geoType) {
            Constants\StackedTax::GT_COUNTRY => Floating::gt($this->calcCountryTaxAmount(), 0.),
            Constants\StackedTax::GT_STATE => Floating::gt($this->calcStateTaxAmount(), 0.),
            Constants\StackedTax::GT_COUNTY => Floating::gt($this->calcCountyTaxAmount(), 0.),
            Constants\StackedTax::GT_CITY => Floating::gt($this->calcCityTaxAmount(), 0.),
            default => false,
        };
    }

    public function hasHpGeoTaxCollected(int $geoType): bool
    {
        return match ($geoType) {
            Constants\StackedTax::GT_COUNTRY => Floating::gt($this->hpCountryTaxAmount, 0.),
            Constants\StackedTax::GT_STATE => Floating::gt($this->hpStateTaxAmount, 0.),
            Constants\StackedTax::GT_COUNTY => Floating::gt($this->hpCountyTaxAmount, 0.),
            Constants\StackedTax::GT_CITY => Floating::gt($this->hpCityTaxAmount, 0.),
            default => false,
        };
    }

    public function hasBpGeoTaxCollected(int $geoType): bool
    {
        return match ($geoType) {
            Constants\StackedTax::GT_COUNTRY => Floating::gt($this->bpCountryTaxAmount, 0.),
            Constants\StackedTax::GT_STATE => Floating::gt($this->bpStateTaxAmount, 0.),
            Constants\StackedTax::GT_COUNTY => Floating::gt($this->bpCountyTaxAmount, 0.),
            Constants\StackedTax::GT_CITY => Floating::gt($this->bpCityTaxAmount, 0.),
            default => false,
        };
    }
     */
}
