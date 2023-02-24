<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Invoice\StackedTax\Calculate;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StackedTaxInvoicePureCalculator
 * @package Sam\Core\Invoice\StackedTax\Calculate
 */
class StackedTaxInvoicePureCalculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calcHpWithTax(
        ?float $hp,
        ?float $hpTaxAmount,
    ): float {
        return $hp + $hpTaxAmount;
    }

    public function calcBpWithTax(
        ?float $bp,
        ?float $bpTaxAmount
    ): float {
        return $bp + $bpTaxAmount;
    }

    public function calcHpBp(
        ?float $hp,
        ?float $bp
    ): float {
        return $hp + $bp;
    }

    public function calcHpTaxAmountBpTaxAmount(
        ?float $hpTaxAmount,
        ?float $bpTaxAmount,
    ): float {
        return $hpTaxAmount + $bpTaxAmount;
    }

    public function calcHpBpWithTax(
        ?float $hp,
        ?float $bp,
        ?float $hpTaxAmount,
        ?float $bpTaxAmount
    ): float {
        return $hp + $bp + $hpTaxAmount + $bpTaxAmount;
    }

    public function calcServicesWithTax(
        ?float $services,
        ?float $servicesTaxAmount
    ): float {
        return $services + $servicesTaxAmount;
    }

    /**
     * @param float|null $hp
     * @param float|null $bp
     * @param float|null $services
     * @param float|null $hpTaxAmount
     * @param float|null $bpTaxAmount
     * @param float|null $servicesTaxAmount
     * @return float
     */
    public function calcInvoiceTotal(
        ?float $hp,
        ?float $bp,
        ?float $services,
        ?float $hpTaxAmount,
        ?float $bpTaxAmount,
        ?float $servicesTaxAmount
    ): float {
        return $this->calcHpBpWithTax($hp, $bp, $hpTaxAmount, $bpTaxAmount)
            + $services + $servicesTaxAmount;
    }

    public function calcNetTotal(
        ?float $hp,
        ?float $bp,
        ?float $services
    ): float {
        return $hp + $bp + $services;
    }

    public function calcTaxTotal(
        ?float $hpTaxAmount,
        ?float $bpTaxAmount,
        ?float $servicesTaxAmount
    ): float {
        return $hpTaxAmount + $bpTaxAmount + $servicesTaxAmount;
    }

    public function calcBalanceDue(
        ?float $hp,
        ?float $bp,
        ?float $services,
        ?float $hpTaxAmount,
        ?float $bpTaxAmount,
        ?float $servicesTaxAmount,
        ?float $totalPayment
    ): float {
        return $this->calcInvoiceTotal($hp, $bp, $services, $hpTaxAmount, $bpTaxAmount, $servicesTaxAmount)
            - $totalPayment;
    }

    public function calcRoundedBalanceDue(
        ?float $hp,
        ?float $bp,
        ?float $services,
        ?float $hpTaxAmount,
        ?float $bpTaxAmount,
        ?float $servicesTaxAmount,
        ?float $totalPayment,
        int $precision = 2
    ): float {
        $invoiceTotal = $this->calcInvoiceTotal($hp, $bp, $services, $hpTaxAmount, $bpTaxAmount, $servicesTaxAmount);
        return round($invoiceTotal, $precision) - round((float)$totalPayment, $precision);
    }

    public function calcCashDiscount(float $amount, float $discountPercent): float
    {
        if (
            Floating::eq($amount, 0)
            || Floating::eq($discountPercent, 0.)
        ) {
            return 0.;
        }
        return $amount * $discountPercent / 100;
    }

    public function calcPaymentAmount(float $netAmount, ?float $invoiceAdditionalAmount, ?float $invoiceAdditionalTaxAmount): float
    {
        return $netAmount + ($invoiceAdditionalAmount ?? 0.) + ($invoiceAdditionalTaxAmount ?? 0.);
    }

    public function calcPaymentNetAmount(float $amount, ?float $invoiceAdditionalAmount, ?float $taxAmount): float
    {
        return $amount - ($invoiceAdditionalAmount ?? 0.) - ($taxAmount ?? 0.);
    }
}
