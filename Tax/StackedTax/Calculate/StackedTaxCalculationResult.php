<?php
/**
 * SAM-11222: More info in Goods line item detail page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use TaxDefinition;
use TaxSchema;

/**
 * Class StackedTaxCalculationResult
 * @package Sam\Tax\StackedTax\Calculate
 */
class StackedTaxCalculationResult extends CustomizableClass
{
    protected TaxSchema $taxSchema;
    /** @var TaxDefinition[] */
    protected array $taxDefinitions = [];
    /** @var float[] */
    protected array $taxAmountsPerDefinition = [];
    protected float $totalTaxAmount = 0.;
    protected float $countryTaxAmount = 0.;
    protected float $stateTaxAmount = 0.;
    protected float $countyTaxAmount = 0.;
    protected float $cityTaxAmount = 0.;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(TaxSchema $taxSchema): static
    {
        $this->taxSchema = $taxSchema;
        return $this;
    }

    public function addTax(TaxDefinition $taxDefinition, float $amount): static
    {
        $this->taxDefinitions[$taxDefinition->Id] = $taxDefinition;
        $this->taxAmountsPerDefinition[$taxDefinition->Id] = $amount;
        $this->totalTaxAmount += $amount;
        if ($taxDefinition->GeoType === Constants\StackedTax::GT_COUNTRY) {
            $this->countryTaxAmount += $amount;
        } elseif ($taxDefinition->GeoType === Constants\StackedTax::GT_STATE) {
            $this->stateTaxAmount += $amount;
        } elseif ($taxDefinition->GeoType === Constants\StackedTax::GT_COUNTY) {
            $this->countyTaxAmount += $amount;
        } elseif ($taxDefinition->GeoType === Constants\StackedTax::GT_CITY) {
            $this->cityTaxAmount += $amount;
        }
        return $this;
    }

    public function getTaxSchema(): TaxSchema
    {
        return $this->taxSchema;
    }

    public function getTaxDefinitions(): array
    {
        return $this->taxDefinitions;
    }

    public function getTaxAmountForDefinition(TaxDefinition $taxDefinition): ?float
    {
        return $this->taxAmountsPerDefinition[$taxDefinition->Id] ?? null;
    }

    public function getTaxAmount(): float
    {
        return $this->totalTaxAmount;
    }

    public function getCountryTaxAmount(): float
    {
        return $this->countryTaxAmount;
    }

    public function getStateTaxAmount(): float
    {
        return $this->stateTaxAmount;
    }

    public function getCountyTaxAmount(): float
    {
        return $this->countyTaxAmount;
    }

    public function getCityTaxAmount(): float
    {
        return $this->cityTaxAmount;
    }
}
