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

/**
 * Trait PaymentInvoiceAdditionalCalculatorCreateTrait
 * @package Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate
 */
trait PaymentInvoiceAdditionalCalculatorCreateTrait
{
    protected ?PaymentInvoiceAdditionalCalculator $paymentInvoiceAdditionalCalculator = null;

    /**
     * @return PaymentInvoiceAdditionalCalculator
     */
    protected function createPaymentInvoiceAdditionalCalculator(): PaymentInvoiceAdditionalCalculator
    {
        return $this->paymentInvoiceAdditionalCalculator ?: PaymentInvoiceAdditionalCalculator::new();
    }

    /**
     * @param PaymentInvoiceAdditionalCalculator $paymentInvoiceAdditionalCalculator
     * @return static
     * @internal
     */
    public function setPaymentInvoiceAdditionalCalculator(PaymentInvoiceAdditionalCalculator $paymentInvoiceAdditionalCalculator): static
    {
        $this->paymentInvoiceAdditionalCalculator = $paymentInvoiceAdditionalCalculator;
        return $this;
    }
}
