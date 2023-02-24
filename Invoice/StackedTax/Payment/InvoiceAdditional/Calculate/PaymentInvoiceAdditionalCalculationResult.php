<?php
/**
 * SAM-11336: Stacked Tax. Tax Schema on CC Surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculationResult;

/**
 * Class PaymentInvoiceAdditionalCalculationResult
 * @package Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate
 */
class PaymentInvoiceAdditionalCalculationResult extends CustomizableClass
{
    public readonly string $name;
    public readonly float $amount;
    public readonly int $type;
    public readonly ?int $taxSchemaId;
    public readonly float $taxAmount;
    public readonly ?StackedTaxCalculationResult $taxCalculationResult;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $name,
        float $amount,
        int $type,
        ?float $taxAmount = null,
        ?StackedTaxCalculationResult $taxCalculationResult = null
    ): static {
        $this->name = $name;
        $this->amount = $amount;
        $this->type = $type;
        $this->taxSchemaId = $taxCalculationResult?->getTaxSchema()->Id;
        $this->taxAmount = $taxCalculationResult?->getTaxAmount() ?? $taxAmount ?? 0.;
        $this->taxCalculationResult = $taxCalculationResult;
        return $this;
    }

    public function surcharge(
        string $name,
        float $amount,
        ?float $taxAmount = null,
        ?StackedTaxCalculationResult $taxCalculationResult = null
    ): static {
        return $this->construct(
            name: $name,
            amount: $amount,
            type: Constants\Invoice::IA_CC_SURCHARGE,
            taxAmount: $taxAmount,
            taxCalculationResult: $taxCalculationResult
        );
    }

    public function cashDiscount(
        string $name,
        float $amount
    ): static {
        return $this->construct(
            name: $name,
            amount: $amount,
            type: Constants\Invoice::IA_CASH_DISCOUNT,
        );
    }
}
