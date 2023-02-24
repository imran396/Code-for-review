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

use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult as CalculationResult;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculationResult;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculatorCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class PaymentInvoiceAdditionalCalculator
 * @package Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate
 */
class PaymentInvoiceAdditionalCalculator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use PaymentRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use StackedTaxCalculatorCreateTrait;
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calculate(
        int $paymentMethod,
        ?int $creditCardId,
        float $amount,
        ?int $taxSchemaId,
        int $accountId,
        bool $applyCashDiscount = false
    ): ?CalculationResult {
        $ccSurchargeResult = null;
        if ($paymentMethod !== Constants\Payment::PM_CC) {
            $ccSurchargeResult = $this->calcCreditCardSurcharge($creditCardId, $amount, $taxSchemaId, $accountId);
        } elseif ($applyCashDiscount) {
            $discountPercent = (float)$this->getSettingsManager()->get(Constants\Setting::CASH_DISCOUNT, $accountId);
            if ($discountPercent) {
                return null;
            }
        }
        return $ccSurchargeResult;
    }

    public function calcCreditCardSurcharge(?int $creditCardId, float $amount, ?int $taxSchemaId, int $accountId): ?CalculationResult
    {
        $surcharge = $this->getInvoiceAdditionalChargeManager()->buildCcSurcharge(
            $creditCardId,
            $amount,
            $accountId
        );
        if (!isset($surcharge['name'], $surcharge['amount'])) {
            return null;
        }

        $tax = $this->calcTax($surcharge['amount'], $taxSchemaId);
        return PaymentInvoiceAdditionalCalculationResult::new()->surcharge(
            name: $surcharge['name'],
            amount: $surcharge['amount'],
            taxCalculationResult: $tax
        );
    }

    public function calcCashDiscount(float $amount, int $accountId, int $paymentMethod, string $language): ?CalculationResult
    {
        $discountPercent = (float)$this->getSettingsManager()->get(Constants\Setting::CASH_DISCOUNT, $accountId);
        $discountAmount = StackedTaxInvoicePureCalculator::new()->calcCashDiscount($amount, $discountPercent);
        if (Floating::eq($discountAmount, 0.)) {
            return null;
        }

        $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslatedForAdmin($paymentMethod, $language);
        return PaymentInvoiceAdditionalCalculationResult::new()->cashDiscount(
            name: $this->getAdminTranslator()->trans(
                'payment.cash_discount.name_template',
                ['paymentMethod' => $paymentMethodName],
                'admin_invoice',
                $language
            ),
            amount: $discountAmount * -1,
        );
    }

    protected function calcTax(float $amount, ?int $taxSchemaId): ?StackedTaxCalculationResult
    {
        $taxSchema = $this->createTaxSchemaLoader()->load($taxSchemaId);
        if (
            !$amount
            || !$taxSchema
        ) {
            return null;
        }

        return $this->createStackedTaxCalculator()->calculate($amount, $taxSchema);
    }
}
