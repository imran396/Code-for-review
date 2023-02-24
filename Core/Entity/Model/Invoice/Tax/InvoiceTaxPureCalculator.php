<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Invoice\Tax;

use InvoiceItem;
use JetBrains\PhpStorm\Pure;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Formula\Descriptor\FormulaDescriptor;

/**
 * Class InvoiceTaxPureCalculator
 * @package Sam\Core\Entity\Model\Invoice\Tax
 */
class InvoiceTaxPureCalculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get Invoice Buyer Tax Service (invoice.tax_service)
     *
     * @param float|null $extraChargesAmount null means 0
     * @param float|null $shippingFees null means 0
     * @param float|null $taxChargesRate null means 0
     * @param float|null $taxFeesRate null means 0
     * @return float
     */
    #[Pure] public function calcBuyerTaxService(
        ?float $extraChargesAmount,
        ?float $shippingFees,
        ?float $taxChargesRate,
        ?float $taxFeesRate
    ): float {
        $extraChargesAmount = (float)$extraChargesAmount;
        $shippingFees = (float)$shippingFees;
        $taxChargesRate = (float)$taxChargesRate;
        $taxFeesRate = (float)$taxFeesRate;
        $taxChargesAmount = $extraChargesAmount * ($taxChargesRate / 100);
        $taxFees = $shippingFees * ($taxFeesRate / 100);
        $buyerTaxServiceAmount = $taxChargesAmount + $taxFees;
        return $buyerTaxServiceAmount;
    }

    /**
     * Calculate Sales tax amount after application on respective sources (HP, BP) according Tax application setting.
     *
     * @param float $hammerPrice
     * @param float $buyersPremium
     * @param float $percent
     * @param int $taxApplication
     * @return float
     */
    #[Pure] public function calcSalesTaxApplied(
        float $hammerPrice,
        float $buyersPremium,
        float $percent,
        int $taxApplication
    ): float {
        switch ($taxApplication) {
            case Constants\User::TAX_HP:
                $salesTaxAmount = $hammerPrice * ($percent / 100);
                break;
            case Constants\User::TAX_BP:
                $salesTaxAmount = $buyersPremium * ($percent / 100);
                break;
            case Constants\User::TAX_HP_BP:
                $salesTaxAmount = $hammerPrice * ($percent / 100);
                $salesTaxAmount += $buyersPremium * ($percent / 100);
                break;
            default: // Constants\User::TAX_SERVICES
                $salesTaxAmount = 0.;
        }
        return $salesTaxAmount;
    }

    /**
     * Calculate Sales tax amount after application on respective sources (HP, BP) by values of InvoiceItem
     * @param InvoiceItem $invoiceItem
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedByInvoiceItem(InvoiceItem $invoiceItem): float
    {
        return $this->calcSalesTaxApplied(
            $invoiceItem->HammerPrice,
            $invoiceItem->BuyersPremium,
            $invoiceItem->SalesTax, // percent value
            $invoiceItem->TaxApplication
        );
    }

    /**
     * Calculate sales tax amount for taxApplication on services - means excluding hammer price factor from calculation.
     * @param float $buyersPremium
     * @param float $percent
     * @param int $taxApplication
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedOnServices(float $buyersPremium, float $percent, int $taxApplication): float
    {
        return $this->calcSalesTaxApplied(
            0.,
            $buyersPremium,
            $percent,
            $taxApplication
        );
    }

    /**
     * Calculate sales tax amount for taxApplication on services - means excluding buyer's premium factor from calculation.
     * @param float $hammerPrice
     * @param float $percent
     * @param int $taxApplication
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedOnGoods(float $hammerPrice, float $percent, int $taxApplication): float
    {
        return $this->calcSalesTaxApplied(
            $hammerPrice,
            0.,
            $percent,
            $taxApplication
        );
    }

    /**
     * Calculate sales tax amount for taxApplication on services - means excluding hammer price factor from calculation.
     * @param InvoiceItemTaxCalculationAmountsDto $dto
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedOnServicesByAmountsDto(InvoiceItemTaxCalculationAmountsDto $dto): float
    {
        $dtoNoHp = clone $dto;
        $dtoNoHp->hammerPrice = 0.;
        return $this->calcSalesTaxAppliedByAmountsDto($dtoNoHp);
    }

    /**
     * Calculate sales tax amount for taxApplication on goods - means excluding buyer's premium factor from calculation.
     * @param InvoiceItemTaxCalculationAmountsDto $dto
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedOnGoodsByAmountsDto(InvoiceItemTaxCalculationAmountsDto $dto): float
    {
        $dtoNoBp = clone $dto;
        $dtoNoBp->buyersPremium = 0.;
        return $this->calcSalesTaxAppliedByAmountsDto($dtoNoBp);
    }

    /**
     * Calculate sales tax amount for taxApplication
     * @param InvoiceItemTaxCalculationAmountsDto $dto
     * @return float
     */
    #[Pure] public function calcSalesTaxAppliedByAmountsDto(InvoiceItemTaxCalculationAmountsDto $dto): float
    {
        return $this->calcSalesTaxApplied(
            $dto->hammerPrice,
            $dto->buyersPremium,
            $dto->salesTax,
            $dto->taxApplication
        );
    }

    #[Pure] public function renderSalesTaxAppliedFormula(
        string $itemKey,
        float $hammerPrice,
        float $buyersPremium,
        float $percent,
        int $taxApplication
    ): FormulaDescriptor {
        $salesTaxApplied = $this->calcSalesTaxApplied($hammerPrice, $buyersPremium, $percent, $taxApplication);
        $p = $percent / 100;
        return match ($taxApplication) {
            Constants\User::TAX_HP => FormulaDescriptor::new()->construct(
                $salesTaxApplied,
                $itemKey . '/TaxOnHP',
                sprintf('HP[%s] * Percent[%s]', $hammerPrice, $p)
            ),
            Constants\User::TAX_BP => FormulaDescriptor::new()->construct(
                $salesTaxApplied,
                $itemKey . '/TaxOnBP',
                sprintf('BP[%s] * Percent[%s]', $buyersPremium, $p)
            ),
            Constants\User::TAX_HP_BP => FormulaDescriptor::new()->construct(
                $salesTaxApplied,
                $itemKey . '/TaxOnHP&BP',
                sprintf('HP[%s] * Percent[%s] + BP[%s] * Percent[%s]', $hammerPrice, $p, $buyersPremium, $p)
            ),
            default => FormulaDescriptor::new()->construct(0., $itemKey . '/TaxOnServices', '0'),
        };
    }

    #[Pure] public function composeSalesTaxAppliedFormulaByAmountsDto(
        string $itemKey,
        InvoiceItemTaxCalculationAmountsDto $dto
    ): FormulaDescriptor {
        return $this->renderSalesTaxAppliedFormula($itemKey, $dto->hammerPrice, $dto->buyersPremium, $dto->salesTax, $dto->taxApplication);
    }

    #[Pure] public function composeBuyerTaxServiceFormula(
        ?float $extraChargesAmount,
        ?float $shippingFees,
        ?float $taxChargesRate,
        ?float $taxFeesRate
    ): FormulaDescriptor {
        $result = $this->calcBuyerTaxService((float)$extraChargesAmount, (float)$shippingFees, (float)$taxChargesRate, (float)$taxFeesRate);
        $formula = sprintf(
            'ExtraChargesAmount[%s] * TaxChargesRate[%s] + ShippingFees[%s] * TaxFeesRate[%s]',
            $extraChargesAmount,
            $taxChargesRate / 100,
            $shippingFees,
            $taxFeesRate / 100
        );
        return FormulaDescriptor::new()->construct($result, FormulaDescriptor::KEY_BUYER_TAX_SERVICE, $formula);
    }
}
