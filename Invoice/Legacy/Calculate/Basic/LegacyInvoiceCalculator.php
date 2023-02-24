<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Calculate\Basic;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Invoice\Legacy\Calculate\Formula\Descriptor\FormulaDescriptor;
use Sam\Invoice\Legacy\Calculate\SaleTax\LegacyInvoiceApplicableSaleTaxCalculator;
use Sam\Invoice\Legacy\Calculate\SaleTax\LegacyInvoiceApplicableSaleTaxCalculatorCreateTrait;
use Sam\Invoice\Legacy\Calculate\SaleTax\LegacyInvoiceApplicableSaleTaxResult;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculator;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;

class LegacyInvoiceCalculator extends CustomizableClass
{
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use LegacyInvoiceApplicableSaleTaxCalculatorCreateTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use PaymentReadRepositoryCreateTrait;
    use SettingsManagerAwareTrait;

    public const OP_GRAND_TOTAL = OptionalKeyConstants::KEY_GRAND_TOTAL; //float
    public const OP_GRAND_TOTAL_AND_FORMULA = 'grandTotalAndFormula'; // array [float, string, array]
    public const OP_TOTAL_PAYMENTS = OptionalKeyConstants::KEY_TOTAL_PAYMENTS; // float
    public const OP_TOTAL_PAYMENTS_AND_FORMULA = 'totalPaymentsAndFormula'; // array [float, string]
    public const OP_SUB_TOTAL = OptionalKeyConstants::KEY_SUB_TOTAL; //float
    public const OP_CALC_TOTAL = OptionalKeyConstants::KEY_CALC_TOTAL; //float
    public const OP_BALANCE_DUE = OptionalKeyConstants::KEY_BALANCE_DUE; //float
    public const OP_TOTAL_ADDITIONAL_CHARGES = OptionalKeyConstants::KEY_TOTAL_ADDITIONAL_CHARGES; //float
    public const OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS = OptionalKeyConstants::KEY_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS; //InvoiceItemTaxCalculationAmountsDto[]

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate total hammer price for invoiceId
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalHammerPrice(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterActive(true)
            ->select(['SUM(hammer_price) AS total_hammer'])
            ->loadRow();
        return (float)($row['total_hammer'] ?? 0.);
    }

    /**
     * Calculate total buyer's premium for invoiceId
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalBuyersPremium(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterActive(true)
            ->select(['SUM(buyers_premium) AS total_premium'])
            ->loadRow();
        return (float)($row['total_premium'] ?? 0.);
    }

    /**
     * Calculate total charges for invoice
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalAdditionalCharges(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->select(['SUM(amount) AS total_charge'])
            ->loadRow();
        $totalCharge = (float)($row['total_charge'] ?? 0.);
        return $totalCharge;
    }

    /**
     * Calculate total payments amount for invoice id
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalPayments(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $totalPaymentFormula = $this->calcTotalPaymentsAndComposeFormula($invoiceId, $isReadOnlyDb);
        return (float)$totalPaymentFormula->value;
    }

    public function calcTotalPaymentsAndComposeFormula(int $invoiceId, bool $isReadOnlyDb = false): FormulaDescriptor
    {
        $row = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($invoiceId)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->select(['SUM(amount) AS total_payment'])
            ->loadRow();
        $totalPayment = (float)($row['total_payment'] ?? 0.);
        return FormulaDescriptor::new()->construct($totalPayment, FormulaDescriptor::KEY_TOTAL_PAYMENT, 'SUM(payment.amount)');
    }

    /**
     * Calculate sum of hammer_price and buyers_premium for invoice id
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotal(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterActive(true)
            ->select(['SUM(hammer_price + buyers_premium) AS total'])
            ->loadRow();
        $total = (float)($row['total'] ?? 0.);
        return $total;
    }

    /**
     * Calculates cash discount for invoiceId
     *
     * @param int $invoiceId
     * @param bool $isCashDiscount
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcCashDiscount(int $invoiceId, bool $isCashDiscount, bool $isReadOnlyDb = false): float
    {
        $formulaDescriptor = $this->calcCashDiscountAndComposeFormula($invoiceId, $isCashDiscount, $isReadOnlyDb);
        return (float)$formulaDescriptor->value;
    }

    public function calcCashDiscountAndComposeFormula(int $invoiceId, bool $isCashDiscount, bool $isReadOnlyDb = false): FormulaDescriptor
    {
        $cashDiscountKey = FormulaDescriptor::KEY_CASH_DISCOUNT;
        if (!$isCashDiscount) {
            return FormulaDescriptor::new()->construct(0., $cashDiscountKey, 'invoice.cash_discount[false]');
        }

        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found for calculating cash discount" . composeSuffix(['i' => $invoiceId]));
            return FormulaDescriptor::new()->construct(0., $cashDiscountKey, 'Error: invoice not found');
        }

        $total = $this->calcTotal($invoiceId, $isReadOnlyDb);
        $discountPercent = (float)$this->getSettingsManager()->get(Constants\Setting::CASH_DISCOUNT, $invoice->AccountId);
        $discount = $total * ($discountPercent / 100);
        $formula = sprintf('SUM(invoice_item.hammer_price + invoice_item.buyers_premium) * setting_invoice.cache_discount[%s] / 100', $discountPercent);
        return FormulaDescriptor::new()->construct($discount, $cashDiscountKey, $formula);
    }

    /**
     * Calculate invoice total minus discount
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_CALC_TOTAL => float
     * ]
     * @return float
     */
    public function calcSubTotal(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): float
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found for calculating sub total" . composeSuffix(['i' => $invoiceId]));
            return 0.;
        }

        $isCashDiscount = $invoice->CashDiscount;
        $total = $optionals[self::OP_CALC_TOTAL] ?? $this->calcTotal($invoiceId);
        $cashDiscountAmount = $this->calcCashDiscount($invoiceId, $isCashDiscount, $isReadOnlyDb);
        $subTotal = $total - $cashDiscountAmount;
        return $subTotal;
    }

    /**
     * Calculate invoice grand total
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_CALC_TOTAL => float
     *   self::OP_TOTAL_ADDITIONAL_CHARGES => float,
     *   self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => InvoiceItemTaxCalculationAmountsDto[]
     * ]
     * @return float
     */
    public function calcGrandTotal(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): float
    {
        $grandTotalFormulaDescriptor = $this->calcGrandTotalAndComposeFormula($invoiceId, $isReadOnlyDb, $optionals);
        return (float)$grandTotalFormulaDescriptor->value;
    }

    /**
     * Calculate invoice grand total and compose formula with clarifications
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_CALC_TOTAL => float
     *   self::OP_TOTAL_ADDITIONAL_CHARGES => float,
     *   self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => InvoiceItemTaxCalculationAmountsDto[]
     * ]
     * @return FormulaDescriptor
     */
    public function calcGrandTotalAndComposeFormula(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): FormulaDescriptor
    {
        $grandTotalKey = FormulaDescriptor::KEY_GRAND_TOTAL;
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found for calculating grand total" . composeSuffix(['i' => $invoiceId]));
            return FormulaDescriptor::new()->construct(0., $grandTotalKey, 'Error: invoice not found');
        }

        $isCashDiscount = $invoice->CashDiscount;
        $total = (float)($optionals[self::OP_CALC_TOTAL] ?? $this->calcTotal($invoiceId));
        $discountFormula = $this->calcCashDiscountAndComposeFormula($invoiceId, $isCashDiscount, $isReadOnlyDb);
        $shipping = (float)$invoice->Shipping;
        $totalCharges = (float)($optionals[self::OP_TOTAL_ADDITIONAL_CHARGES] ?? $this->calcTotalAdditionalCharges($invoiceId, $isReadOnlyDb));
        $taxCalculationAmountsDtos = $optionals[self::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS]
            ?? $this->createLegacyInvoiceTaxCalculator()->loadTaxCalculationAmountsOfInvoiceItems($invoiceId);
        $totalSalesTaxAppliedFormula = $this->createLegacyInvoiceTaxCalculator()->calcTotalSalesTaxAppliedAndComposeFormula(
            $invoiceId,
            $isReadOnlyDb,
            [
                LegacyInvoiceTaxCalculator::OP_TOTAL_ADDITIONAL_CHARGES => $totalCharges,
                LegacyInvoiceTaxCalculator::OP_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS => $taxCalculationAmountsDtos,
            ]
        );
        $grandTotal = $total
            + $shipping
            + $totalCharges
            + $totalSalesTaxAppliedFormula->value
            - $discountFormula->value;

        return $this->composeGrandTotalFormula(
            $total,
            $shipping,
            $totalCharges,
            $totalSalesTaxAppliedFormula,
            $discountFormula,
            $grandTotal
        );
    }

    protected function composeGrandTotalFormula(
        float $total,
        float $shipping,
        float $totalCharges,
        FormulaDescriptor $totalSalesTaxAppliedFormula,
        FormulaDescriptor $discountFormula,
        float $grandTotal,
    ): FormulaDescriptor {
        $formula = sprintf(
            '%s[%s] + %s[%s] + %s[%s] + %s[%s] - %s[%s]',
            FormulaDescriptor::KEY_TOTAL,
            $total,
            FormulaDescriptor::KEY_SHIPPING,
            $shipping,
            FormulaDescriptor::KEY_TOTAL_CHARGES,
            $totalCharges,
            FormulaDescriptor::KEY_TOTAL_SALES_TAX_APPLIED,
            (float)$totalSalesTaxAppliedFormula->value,
            FormulaDescriptor::KEY_CASH_DISCOUNT,
            (float)$discountFormula->value
        );
        $formulaClarifications = [
            FormulaDescriptor::KEY_TOTAL => FormulaDescriptor::new()->construct($total, FormulaDescriptor::KEY_TOTAL, 'SUM(invoice_item.hammer_price + invoice_item.buyers_premium)'),
            FormulaDescriptor::KEY_SHIPPING => FormulaDescriptor::new()->construct(0., FormulaDescriptor::KEY_SHIPPING, 'invoice.shipping'),
            FormulaDescriptor::KEY_TOTAL_CHARGES => FormulaDescriptor::new()->construct($totalCharges, FormulaDescriptor::KEY_TOTAL_CHARGES, 'SUM(invoice_additionals.amount)'),
            FormulaDescriptor::KEY_TOTAL_SALES_TAX_APPLIED => $totalSalesTaxAppliedFormula,
            FormulaDescriptor::KEY_CASH_DISCOUNT => $discountFormula,
        ];
        return FormulaDescriptor::new()->construct($grandTotal, FormulaDescriptor::KEY_GRAND_TOTAL, $formula, $formulaClarifications);
    }

    /**
     * Calc balance due of invoice.
     * Intermediate function does not perform rounding of result value, thus its result can be aggregated and rounded then.
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_GRAND_TOTAL => float,
     *   self::OP_TOTAL_PAYMENTS => float,
     * ]
     * @return float
     */
    public function calcBalanceDue(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): float
    {
        $grandTotal = $optionals[self::OP_GRAND_TOTAL] ?? $this->calcGrandTotal($invoiceId, $isReadOnlyDb);
        $totalPayment = $optionals[self::OP_TOTAL_PAYMENTS] ?? $this->calcTotalPayments($invoiceId, $isReadOnlyDb);
        $balanceDue = $grandTotal - $totalPayment;
        return $balanceDue;
    }

    /**
     * Calc balance due of invoice
     * Final function rounds result value according precision.
     * @param int $invoiceId
     * @param int $precision
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *   self::OP_GRAND_TOTAL => float,
     *   self::OP_TOTAL_PAYMENTS => float,
     * ]
     * @return float
     */
    public function calcRoundedBalanceDue(int $invoiceId, int $precision = 2, bool $isReadOnlyDb = false, array $optionals = []): float
    {
        $grandTotal = $optionals[self::OP_GRAND_TOTAL] ?? $this->calcGrandTotal($invoiceId, $isReadOnlyDb);
        $totalPayment = $optionals[self::OP_TOTAL_PAYMENTS] ?? $this->calcTotalPayments($invoiceId, $isReadOnlyDb);
        $grandTotalRounded = round($grandTotal, $precision);
        $balanceDue = $grandTotalRounded - $totalPayment;
        return $balanceDue;
    }

    /**
     * Calc balance due of invoice, and compose formula with clarifications.
     * Final function rounds result value according precision.
     * @param int $invoiceId
     * @param int $precision
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *   self::OP_GRAND_TOTAL_AND_FORMULA => [float, string, array],
     *   self::OP_TOTAL_PAYMENTS_AND_FORMULA => [float, string],
     * ]
     * @return FormulaDescriptor
     */
    public function calcRoundedBalanceDueAndComposeFormula(int $invoiceId, int $precision = 2, bool $isReadOnlyDb = false, array $optionals = []): FormulaDescriptor
    {
        $grandTotalFormula = $optionals[self::OP_GRAND_TOTAL_AND_FORMULA] ?? $this->calcGrandTotalAndComposeFormula($invoiceId, $isReadOnlyDb);
        $grandTotal = (float)$grandTotalFormula->value;
        $totalPaymentFormula = $optionals[self::OP_TOTAL_PAYMENTS_AND_FORMULA] ?? $this->calcTotalPaymentsAndComposeFormula($invoiceId, $isReadOnlyDb);
        $totalPayment = (float)$totalPaymentFormula->value;
        $grandTotalRounded = round($grandTotal, $precision);
        $balanceDue = $grandTotalRounded - $totalPayment;
        $formula = sprintf(
            'round(%s[%s], %s) - %s[%s]',
            FormulaDescriptor::KEY_GRAND_TOTAL,
            $grandTotal,
            $precision,
            FormulaDescriptor::KEY_TOTAL_PAYMENT,
            $totalPayment
        );
        $formulaClarification = [
            FormulaDescriptor::KEY_GRAND_TOTAL => $grandTotalFormula,
            FormulaDescriptor::KEY_TOTAL_PAYMENT => $totalPaymentFormula,
        ];
        $balanceDueFormula = FormulaDescriptor::new()->construct(
            $balanceDue,
            FormulaDescriptor::KEY_BALANCE_DUE,
            $formula,
            $formulaClarification
        );
        return $balanceDueFormula;
    }

    /**
     * Returns an array of the applicable sales tax together with its application(if available)
     * @param int $winnerUserId
     * @param int $lotItemId
     * @param int|null $auctionId null leads to absence of buyer state on auction billing state
     * @param bool $isReadOnlyDb
     * @return LegacyInvoiceApplicableSaleTaxResult
     */
    public function detectApplicableSalesTax(
        int $winnerUserId,
        int $lotItemId,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): LegacyInvoiceApplicableSaleTaxResult {
        $calculator = $this->createLegacyInvoiceApplicableSaleTaxCalculator()->construct(
            $winnerUserId,
            $lotItemId,
            $auctionId,
            [LegacyInvoiceApplicableSaleTaxCalculator::OP_IS_READ_ONLY_DB => $isReadOnlyDb]
        );
        $taxResult = $calculator->detect();
        $logData = $taxResult->logData()
            + [
                'info' => $calculator->infoMessage(),
                'winner u' => $winnerUserId,
                'li' => $lotItemId,
                'a' => $auctionId,
            ];
        log_debug('Applicable Sales Tax detection service results' . composeSuffix($logData));
        return $taxResult;
    }
}
