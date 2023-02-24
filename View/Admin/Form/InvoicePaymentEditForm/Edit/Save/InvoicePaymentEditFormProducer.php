<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Save;

use InvoiceAdditional;
use Payment;
use Sam\Billing\Gate\Opayo\Common\TransactionParameter\TransactionParameterCollection;
use Sam\Billing\Payment\Load\Exception\CouldNotFindPayment;
use Sam\Billing\Payment\Load\PaymentLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Entity\Model\Payment\Status\PaymentStatusPureChecker;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\InvoiceAdditional\Delete\StackedTaxInvoiceAdditionalDeleterCreateTrait;
use Sam\Invoice\StackedTax\InvoiceAdditional\Load\InvoiceAdditionalLoaderCreateTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculatorCreateTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\TaxCalculationResultSnapshotMakerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Dto\InvoicePaymentEditFormInput as Input;

/**
 * Class InvoicePaymentEditFormProducer
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Save
 */
class InvoicePaymentEditFormProducer extends CustomizableClass
{
    use DateHelperAwareTrait;
    use EntityFactoryCreateTrait;
    use InvoiceAdditionalLoaderCreateTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentInvoiceAdditionalCalculatorCreateTrait;
    use PaymentLoaderAwareTrait;
    use PaymentWriteRepositoryAwareTrait;
    use StackedTaxInvoiceAdditionalDeleterCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use TaxCalculationResultSnapshotMakerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(Input $input, int $editorUserId): Payment
    {
        $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);
        $netAmount = $this->getNumberFormatter()->parseMoney($input->netAmount, $input->systemAccountId);
        $payment = $this->loadOrCreatePayment($input);
        $invoiceAdditional = $this->createInvoiceAdditionalLoader()->loadByPaymentId($payment->Id);

        $invoiceAdditionalChanged = $this->isInvoiceAdditionalChanged($payment, $invoiceAdditional, $input);
        $invoiceAdditionalCalculationResult = $invoiceAdditionalChanged ? $this->calculateInvoiceAdditional($input) : null;

        $payment->PaymentMethodId = $input->paymentMethod;
        if ($this->isCreditCardPaymentMethod($input->paymentMethod)) {
            $payment->CreditCardId = $input->creditCardId;
            $tpc = TransactionParameterCollection::new();
            $tpc->setPaymentGateway($input->paymentGateway);
            $payment->TranParam = $tpc->serialize();
        }
        $payment->Amount = !$invoiceAdditionalChanged
            ? StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
                $netAmount,
                $invoiceAdditional?->Amount,
                $invoiceAdditional?->TaxAmount
            )
            : StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
                $netAmount,
                $invoiceAdditionalCalculationResult?->amount,
                $invoiceAdditionalCalculationResult?->taxAmount
            );
        $payment->PaidOn = $this->getDateHelper()->convertSysToUtcByDateIso($input->dateSysIso, $input->invoiceAccountId);
        $payment->Note = $input->note;
        $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);

        if ($invoiceAdditionalChanged) {
            $this->updateInvoiceAdditional(
                invoiceAdditional: $invoiceAdditional,
                calculationResult: $invoiceAdditionalCalculationResult,
                paymentId: $payment->Id,
                invoiceId: $input->invoiceId,
                editorUserId: $editorUserId,
                language: $input->language
            );
        }
        $this->getStackedTaxInvoiceSummaryCalculator()->recalculateAndSave($input->invoiceId, $editorUserId);
        return $payment;
    }

    protected function updateInvoiceAdditional(
        ?InvoiceAdditional $invoiceAdditional,
        ?PaymentInvoiceAdditionalCalculationResult $calculationResult,
        int $paymentId,
        int $invoiceId,
        int $editorUserId,
        string $language,
    ): void {
        if ($calculationResult) {
            $invoiceAdditional ??= $this->createEntityFactory()->invoiceAdditional();
            $invoiceAdditional->Type = $calculationResult->type;
            $invoiceAdditional->Amount = $calculationResult->amount;
            $invoiceAdditional->InvoiceId = $invoiceId;
            $invoiceAdditional->Name = $calculationResult->name;
            $invoiceAdditional->TaxAmount = $calculationResult->taxAmount;
            $invoiceAdditional->PaymentId = $paymentId;
            $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);

            if ($calculationResult->taxCalculationResult) {
                $taxSchemaSnapshot = $this->createTaxCalculationResultSnapshotMaker()->forInvoiceServiceFee(
                    calculationResult: $calculationResult->taxCalculationResult,
                    invoiceAdditionalId: $invoiceAdditional->Id,
                    invoiceId: $invoiceId,
                    editorUserId: $editorUserId,
                    language: $language,
                );
                $invoiceAdditional->TaxSchemaId = $taxSchemaSnapshot->Id;
                $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
            }
        } elseif ($invoiceAdditional) {
            $this->createStackedTaxInvoiceAdditionalDeleter()->deleteById($invoiceAdditional->Id, $editorUserId);
        }
    }

    protected function calculateInvoiceAdditional(Input $input): ?PaymentInvoiceAdditionalCalculationResult
    {
        $netAmount = $this->getNumberFormatter()->parseMoney($input->netAmount, $input->systemAccountId);
        if ($this->isCreditCardPaymentMethod($input->paymentMethod)) {
            return $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
                creditCardId: $input->creditCardId,
                amount: $netAmount,
                taxSchemaId: $input->taxSchemaId,
                accountId: $input->invoiceAccountId
            );
        }
        if ($input->applyCashDiscount) {
            return $this->createPaymentInvoiceAdditionalCalculator()->calcCashDiscount(
                amount: $netAmount,
                accountId: $input->invoiceAccountId,
                paymentMethod: $input->paymentMethod,
                language: $input->language
            );
        }
        return null;
    }

    protected function isInvoiceAdditionalChanged(
        Payment $payment,
        ?InvoiceAdditional $invoiceAdditional,
        Input $input
    ): bool {
        $netAmount = $this->getNumberFormatter()->parseMoney($input->netAmount, $input->systemAccountId);
        $totalAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $netAmount,
            $invoiceAdditional?->Amount,
            $invoiceAdditional?->TaxAmount
        );
        return !$payment->Id
            || !Floating::eq($payment->Amount, $totalAmount, 2)
            || !$invoiceAdditional
            || $invoiceAdditional->TaxSchemaId !== $input->taxSchemaId
            || $payment->PaymentMethodId !== $input->paymentMethod
            || ($payment->isCreditCardMethod()
                && $payment->CreditCardId !== $input->creditCardId)
            || (!$payment->isCreditCardMethod()
                && !$input->applyCashDiscount);
    }

    protected function loadOrCreatePayment(Input $input): Payment
    {
        if ($input->paymentId) {
            $payment = $this->getPaymentLoader()->load($input->paymentId);
            if (!$payment) {
                throw CouldNotFindPayment::withId($input->paymentId);
            }
        } else {
            $payment = $this->createEntityFactory()->payment();
            $payment->TranId = $input->invoiceId;
            $payment->TranType = Constants\Payment::TT_INVOICE;
            $payment->AccountId = $input->invoiceAccountId;
        }

        return $payment;
    }

    protected function isCreditCardPaymentMethod(?int $paymentMethod): bool
    {
        return PaymentStatusPureChecker::new()->isCcPaymentMethod($paymentMethod);
    }
}
