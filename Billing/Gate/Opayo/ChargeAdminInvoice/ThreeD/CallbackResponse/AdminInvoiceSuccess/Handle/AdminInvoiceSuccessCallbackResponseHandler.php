<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle;

use Invoice;
use Payment;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\AdminInvoiceSuccessCallbackResponseInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle\AdminInvoiceSuccessCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle\Internal\Produce\DataProducerCreateTrait;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

class AdminInvoiceSuccessCallbackResponseHandler extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DataProducerCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(Input $input): Result
    {
        $payment = $this->produceInvoicePayment($input);
        $invoice = $this->applyOnInvoice($input);
        $this->notifyUser($invoice, $input);
        $successMessage = $this->composeSuccessMessage($input->paymentType, $invoice->InvoiceNo);

        $result = Result::new()->construct($payment, $invoice)
            ->addSuccess(Result::OK_SUCCESS_PAYMENT, $successMessage);
        return $result;
    }

    protected function composeSuccessMessage(string $paymentType, ?int $invoiceNo): string
    {
        $successMessage = '';
        if ($paymentType === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_EDIT) {
            $successMessage = 'Payment successful after the payment is processed.';
        } elseif ($paymentType === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_LIST) {
            $successMessage = sprintf(
                'Successfully charged invoice %s through Opayo! <br />',
                $invoiceNo
            );
        }
        return $successMessage;
    }

    protected function produceInvoicePayment(Input $input): Payment
    {
        $note = $this->composeNote($input);
        return $this->createDataProducer()->addInvoicePayment(
            $input->invoiceId,
            $input->amount,
            $input->editorUserId,
            $note,
            $input->currentDate,
            $input->creditCardId
        );
    }

    protected function composeNote(Input $input): string
    {
        $transactionId = $input->transactionId;
        $note = composeLogData(
            [
                'Trans.' => $transactionId,
                'CC' => substr($input->ccNumber, -4)
            ]
        );
        if ($input->threeDStatusResponse === Constants\BillingOpayo::STATUS_ATTEMPTONLY) {
            $note .= ' 3DSecure=ATTEMPTONLY';
            log_info(
                'Opayo 3DSecure=ATTEMPTONLY for invoice'
                . composeSuffix(['i' => $input->invoiceId])
            );
        }
        return $note;
    }

    protected function applyOnInvoice(Input $input): Invoice
    {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($input->invoiceId, $input->isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($input->invoiceId);
        }
        $balanceDue = $dataProvider->calculateBalanceDue($invoice);
        if (Floating::lteq($balanceDue, 0)) {
            $invoice->toPaid();
        }
        $invoice = $this->createDataProducer()->recalculateTotalsAndAssign($invoice, $input->isReadOnlyDb);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);
        return $invoice;
    }

    protected function notifyUser(Invoice $invoice, Input $input): bool
    {
        // Email customer as a proof of their payment
        return $this->createDataProducer()->addEmailToActionQueue($invoice, $input->editorUserId);
    }
}
