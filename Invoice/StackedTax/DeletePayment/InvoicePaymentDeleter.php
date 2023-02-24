<?php
/**
 * SAM-11000: Stacked Tax. New Invoice Edit page: Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\DeletePayment;

use RuntimeException;
use Sam\Billing\Payment\Load\Exception\CouldNotFindPayment;
use Sam\Billing\Payment\Load\PaymentLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\InvoiceAdditional\Delete\StackedTaxInvoiceAdditionalDeleterCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;

/**
 * Class InvoicePaymentDeleter
 * @package Sam\Invoice\StackedTax\DeletePayment
 */
class InvoicePaymentDeleter extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentLoaderAwareTrait;
    use PaymentWriteRepositoryAwareTrait;
    use StackedTaxInvoiceAdditionalDeleterCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $paymentId, int $editorUserId): void
    {
        $payment = $this->getPaymentLoader()->load($paymentId);
        if (!$payment) {
            throw CouldNotFindPayment::withId($paymentId);
        }
        if ($payment->TranType !== Constants\Payment::TT_INVOICE) {
            throw new RuntimeException("Payment with id {$paymentId} has invalid transaction type");
        }

        $payment->toDeleted();
        $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);

        $invoiceId = $payment->TranId;
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        $invoice->toOpen();
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        $this->getStackedTaxInvoiceSummaryCalculator()->recalculateAndSave($invoiceId, $editorUserId);
    }
}
