<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 * SAM-4669: Invoice management modules
 *
 * Calculation functions perform intermediate role, thus we cannot make rounding decision in them.
 * Read about rounding error in:
 * SAM-9342: Do not round decimal numbers in calculations
 * SAM-9764: Tax total calculated wrong for sales tax at invoice detail page - https://bidpath.atlassian.net/browse/SAM-9764?focusedCommentId=140011
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           14.12.2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Calculate\Tax;

use Invoice;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceItemTaxCalculationAmountsDto;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Formula\Descriptor\FormulaDescriptor;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\Internal\Load\DataProviderCreateTrait;

class LegacyInvoiceTaxCalculator extends CustomizableClass
{
    use LegacyInvoiceCalculatorAwareTrait;
    use DataProviderCreateTrait;

    public const OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS = 'invoiceItemTaxCalculationAmountsDtos'; // InvoiceItemTaxCalculationAmountsDto[]
    public const OP_TOTAL_ADDITIONAL_CHARGES = 'totalAdditionalCharges'; // float

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate total applied sales tax for invoice id.
     * Intermediate function does not round result.
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *      self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => InvoiceItemTaxCalculationAmountsDto[],
     *      self::OP_TOTAL_ADDITIONAL_CHARGES => float,
     * ]
     * @return float
     */
    public function calcTotalSalesTaxApplied(
        int $invoiceId,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): float {
        $totalTaxFormula = $this->calcTotalSalesTaxAppliedAndComposeFormula($invoiceId, $isReadOnlyDb, $optionals);
        return (float)$totalTaxFormula->value;
    }

    public function calcTotalSalesTaxAppliedAndComposeFormula(
        int $invoiceId,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): FormulaDescriptor {
        $invoice = $this->createDataProvider()->loadInvoiceById($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Active invoice not found" . composeSuffix(['i' => $invoiceId]));
            return FormulaDescriptor::new()->construct(
                0.,
                FormulaDescriptor::KEY_TOTAL_SALES_TAX_APPLIED,
                'Error: invoice not found'
            );
        }

        $invoiceItemTaxCalculationAmountsDtos = $this->fetchOptionalTaxCalculationAmountsOfInvoiceItems($invoiceId, $isReadOnlyDb, $optionals);
        $totalTax = 0.;
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        foreach ($invoiceItemTaxCalculationAmountsDtos as $dto) {
            $totalTax += $invoiceTaxPureCalculator->calcSalesTaxAppliedByAmountsDto($dto);
        }

        $extraChargesAmount = $this->fetchOptionalTotalAdditionalCharges($invoiceId, $isReadOnlyDb, $optionals);
        $taxServiceAmount = $invoiceTaxPureCalculator->calcBuyerTaxService(
            $extraChargesAmount,
            (float)$invoice->Shipping,
            (float)$invoice->TaxChargesRate,
            (float)$invoice->TaxFeesRate
        );

        $totalTax += $taxServiceAmount;

        $formulaDescriptor = $this->composeTotalSalesTaxAppliedFormula($totalTax, $invoice, $invoiceItemTaxCalculationAmountsDtos, $extraChargesAmount);
        return $formulaDescriptor;
    }

    protected function composeTotalSalesTaxAppliedFormula(
        float $totalTax,
        Invoice $invoice,
        array $invoiceItemTaxCalculationAmountsDtos,
        ?float $extraChargesAmount
    ): FormulaDescriptor {
        $formulas = $clarifications = [];
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        $i = 0;
        foreach ($invoiceItemTaxCalculationAmountsDtos as $dto) {
            $i++;
            $itemKey = sprintf(FormulaDescriptor::KEY_ITEM_TPL, $i);
            $formulas[] = $itemKey;
            $clarifications[] = $invoiceTaxPureCalculator->composeSalesTaxAppliedFormulaByAmountsDto($itemKey, $dto);
        }

        $formulas[] = FormulaDescriptor::KEY_BUYER_TAX_SERVICE;
        $clarifications[] = $invoiceTaxPureCalculator->composeBuyerTaxServiceFormula(
            $extraChargesAmount,
            (float)$invoice->Shipping,
            (float)$invoice->TaxChargesRate,
            (float)$invoice->TaxFeesRate
        );

        $formula = implode(" + ", $formulas);
        return FormulaDescriptor::new()->construct(
            $totalTax,
            FormulaDescriptor::KEY_TOTAL_SALES_TAX_APPLIED,
            $formula,
            $clarifications
        );
    }

    /**
     * Get Total Tax on Goods Service.
     * Intermediate function does not round result.
     *
     * @param int $invoiceId invoice.id
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *      self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => InvoiceItemTaxCalculationAmountsDto[],
     * ]
     * @return float $totalTaxOnGoods
     */
    public function calcTotalTaxOnGoods(
        int $invoiceId,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): float {
        $invoiceItemTaxCalculationAmountsDtos = $this->fetchOptionalTaxCalculationAmountsOfInvoiceItems($invoiceId, $isReadOnlyDb, $optionals);
        $totalTaxOnGoods = 0.;
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        foreach ($invoiceItemTaxCalculationAmountsDtos as $dto) {
            $totalTaxOnGoods += $invoiceTaxPureCalculator->calcSalesTaxAppliedOnGoodsByAmountsDto($dto);
        }
        return $totalTaxOnGoods;
    }

    /**
     * Get Total Tax on Service.
     * Intermediate function does not round result.
     *
     * @param int $invoiceId invoice.id
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *      self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => InvoiceItemTaxCalculationAmountsDto[],
     *      self::OP_TOTAL_ADDITIONAL_CHARGES => float
     * ]
     * @return float
     */
    public function calcTotalTaxOnServices(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): float
    {
        $invoice = $this->createDataProvider()->loadInvoiceById($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found for calculating total tax on services" . composeSuffix(['i' => $invoiceId]));
            return 0.;
        }
        $invoiceItemTaxCalculationAmountsDtos = $this->fetchOptionalTaxCalculationAmountsOfInvoiceItems($invoiceId, $isReadOnlyDb, $optionals);
        $totalTaxOnServices = 0.;
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        foreach ($invoiceItemTaxCalculationAmountsDtos as $dto) {
            $totalTaxOnServices += $invoiceTaxPureCalculator->calcSalesTaxAppliedOnServicesByAmountsDto($dto);
        }
        $extraCharge = $this->fetchOptionalTotalAdditionalCharges($invoiceId, $isReadOnlyDb, $optionals);
        $buyerTaxService = $invoiceTaxPureCalculator->calcBuyerTaxService(
            $extraCharge,
            (float)$invoice->Shipping,
            (float)$invoice->TaxChargesRate,
            (float)$invoice->TaxFeesRate
        );
        $totalTax = $totalTaxOnServices + $buyerTaxService;
        return $totalTax;
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceItemTaxCalculationAmountsDto[]
     */
    public function loadTaxCalculationAmountsOfInvoiceItems(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createDataProvider()->loadInvoiceItemSelectedByInvoiceId($invoiceId, $isReadOnlyDb);
        $invoiceItemTaxCalculationAmountsDtos = [];
        foreach ($rows as $row) {
            $invoiceItemTaxCalculationAmountsDtos[] = InvoiceItemTaxCalculationAmountsDto::new()->fromDbRow($row);
        }
        return $invoiceItemTaxCalculationAmountsDtos;
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals
     * @return float
     */
    protected function fetchOptionalTotalAdditionalCharges(
        int $invoiceId,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): float {
        return $optionals[self::OP_TOTAL_ADDITIONAL_CHARGES]
            ?? $this->getLegacyInvoiceCalculator()->calcTotalAdditionalCharges($invoiceId, $isReadOnlyDb);
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals
     * @return InvoiceItemTaxCalculationAmountsDto[]
     */
    protected function fetchOptionalTaxCalculationAmountsOfInvoiceItems(
        int $invoiceId,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): array {
        return $optionals[self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS]
            ?? $this->loadTaxCalculationAmountsOfInvoiceItems($invoiceId, $isReadOnlyDb);
    }
}
