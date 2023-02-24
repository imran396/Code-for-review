<?php
/**
 * SAM-11338: Stacked Tax. Public page Invoice with CC surcharge and Service tax on surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculatorCreateTrait;
use Sam\Invoice\StackedTax\TaxSchema\Detect\StackedTaxInvoiceTaxSchemaDetectorCreateTrait;

/**
 * Class InvoiceChargeAmountDetailsFactory
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amoun
 */
class InvoiceChargeAmountDetailsFactory extends CustomizableClass
{
    use InvoiceAdditionalChargeManagerAwareTrait;
    use PaymentInvoiceAdditionalCalculatorCreateTrait;
    use StackedTaxInvoiceTaxSchemaDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(
        Invoice $invoice,
        int $paymentMethod,
        ?int $creditCardType,
        float $netAmount
    ): InvoiceChargeAmountDetails {
        if (
            $paymentMethod === Constants\Payment::PM_CC
            && $creditCardType
        ) {
            return $invoice->isStackedTaxDesignation()
                ? $this->createForStackedTax($invoice, $creditCardType, $netAmount)
                : $this->createForLegacy($invoice, $creditCardType, $netAmount);
        }
        return InvoiceChargeAmountDetails::new()->construct($netAmount, null);
    }

    protected function createForStackedTax(Invoice $invoice, int $creditCardType, float $netAmount): InvoiceChargeAmountDetails
    {
        $taxSchema = $this->createStackedTaxInvoiceTaxSchemaDetector()->detectForServices($invoice->Id);
        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            $creditCardType,
            $netAmount,
            $taxSchema?->Id,
            $invoice->AccountId
        );
        return InvoiceChargeAmountDetails::new()->construct(
            paymentAmount: StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
                $netAmount,
                $ccSurchargeResult?->amount,
                $ccSurchargeResult?->taxAmount
            ),
            surcharge: $ccSurchargeResult
        );
    }

    protected function createForLegacy(Invoice $invoice, int $creditCardType, float $netAmount): InvoiceChargeAmountDetails
    {
        $ccSurcharge = $this->getInvoiceAdditionalChargeManager()->buildCcSurcharge($creditCardType, $netAmount, $invoice->AccountId);
        $surcharge = null;
        if ($ccSurcharge) {
            $surchargeTaxAmount = InvoiceTaxPureCalculator::new()->calcBuyerTaxService(
                extraChargesAmount: $ccSurcharge['amount'],
                shippingFees: 0,
                taxChargesRate: (float)$invoice->TaxChargesRate,
                taxFeesRate: 0
            );
            $surcharge = PaymentInvoiceAdditionalCalculationResult::new()->surcharge(
                name: $ccSurcharge['name'],
                amount: $ccSurcharge['amount'],
                taxAmount: $surchargeTaxAmount,
            );
        }

        return InvoiceChargeAmountDetails::new()->construct(
            paymentAmount: StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
                $netAmount,
                $surcharge?->amount,
                $surcharge?->taxAmount
            ),
            surcharge: $surcharge
        );
    }
}
