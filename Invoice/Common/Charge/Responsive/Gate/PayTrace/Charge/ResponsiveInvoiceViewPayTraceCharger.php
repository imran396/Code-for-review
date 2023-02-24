<?php
/**
 * SAM-10975: Stacked Tax. Public My Invoice pages. Extract PayTrace invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge;

use Email_Template;
use Invoice;
use Payment_PayTrace;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCimUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount\InvoiceChargeAmountDetailsFactoryCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge\ResponsiveInvoiceViewPayTraceChargingInput as Input;
use Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge\ResponsiveInvoiceViewPayTraceChargingResult as Result;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce\PaymentInvoiceAdditionalProducerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;


class ResponsiveInvoiceViewPayTraceCharger extends CustomizableClass
{
    use CcExpiryDateBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use InvoiceChargeAmountDetailsFactoryCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceUserCimUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentInvoiceAdditionalProducerCreateTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function charge(Input $input): Result
    {
        $result = Result::new()->construct();
        $invoice = $input->invoice;

        $dataProvider = $this->createDataProvider();
        $isFound = $dataProvider->existUser($invoice->BidderId, $input->isReadOnlyDb);
        if (!$isFound) {
            log_error(
                "Available invoice winning user not found, when charge payment through PayTrace"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $amountDetails = $this->createInvoiceChargeAmountDetailsFactory()->create(
            invoice: $invoice,
            paymentMethod: Constants\Payment::PM_CC,
            creditCardType: $userBilling->CcType,
            netAmount: $input->balanceDue
        );
        // CC owner name must be the same format appears on credit card
        $name = UserPureRenderer::new()->makeFullName($userBilling->FirstName, $userBilling->LastName);

        $payTrace = new Payment_PayTrace($invoice->AccountId);

        $payTraceCustId = $dataProvider->decryptValue($userBilling->PayTraceCustId);
        $existPayTraceId = !$input->wasCcEdited
            && $dataProvider->isPayTraceCim($invoice->AccountId)
            && $payTraceCustId;
        $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);

        $user = $dataProvider->loadUser($invoice->BidderId);
        if ($input->paymentMethodId === Constants\Payment::PM_CC) { // Cc payment
            $amountFormatted = sprintf("%1.2F", Floating::roundOutput($amountDetails->paymentAmount));

            if ($existPayTraceId) {
                // Cc number was not changed and CIM is enabled
                $payTrace->setMethod('ProcessTranx');
                $payTrace->setTransactionType('Sale');
                $payTrace->setParameter('DESCRIPTION', $this->generateDescription($invoice));
                $payTrace->setParameter('INVOICE', $invoice->InvoiceNo);
                $payTrace->setParameter('CUSTID', $payTraceCustId); // Numeric (required)
                $payTrace->setParameter('AMOUNT', $amountFormatted); // Numeric (required)
                $payTrace->setParameter('EMAIL', $user->Email);
                $payTrace->process(2);
            } else {
                $payTrace->setMethod('ProcessTranx');
                $payTrace->setTransactionType('Sale');
                $payTrace->setParameter('DESCRIPTION', $this->generateDescription($invoice));
                $payTrace->setParameter('BNAME', $name);
                $payTrace->setParameter('BADDRESS', $userBilling->Address);
                $payTrace->setParameter('BCITY', $userBilling->City);
                if ($userBilling->State) {
                    $payTrace->setParameter('BSTATE', $userBilling->State);
                }
                $payTrace->setParameter('BCOUNTRY', $userBilling->Country);
                $payTrace->setParameter('BZIP', $userBilling->Zip);
                if ($input->ccCode) {
                    $payTrace->setParameter('CSC', $input->ccCode);
                }
                $payTrace->setParameter('INVOICE', $invoice->InvoiceNo);
                $payTrace->setParameter('EMAIL', $user->Email);

                [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $payTrace->transaction(
                    $ccNumber,
                    $ccExpMonth,
                    $ccExpYear,
                    $amountDetails->paymentAmount
                );
                $payTrace->process(2);
            }
        } elseif ($input->paymentMethodId === Constants\Payment::PM_BANK_WIRE) {
            $amountFormatted = sprintf("%1.2F", Floating::roundOutput($amountDetails->paymentAmount));
            $payTrace->echeck(
                $userBilling->BankRoutingNumber,
                $userBilling->BankAccountNumber,
                $userBilling->BankAccountName,
                $amountFormatted
            );
            $payTrace->setCheckType('Sale');
            $payTrace->process(2);
        } else {
            log_debug('No payment method click');
            $result->addError(Result::ERR_PAYMENT_METHOD_NOT_MATCHED);
            return $result;
        }

        $errorMessage = '';
        if (!$payTrace->isError()) {
            if ($payTrace->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $payTrace->getResultResponse() . ':' . $payTrace->getResponseText();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $payTrace->getResultResponse() . ':' . $payTrace->getResponseText();
        }

        if ($errorMessage !== '') {
            log_warning($errorMessage);
            $result->addError(Result::ERR_CHARGE, $errorMessage);
            return $result;
        }

        log_info(
            'Payment on invoice was successful'
            . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );

        $note = '';
        $txnId = '';
        if ($input->paymentMethodId === Constants\Payment::PM_CC) {
            $txnId = $payTrace->getTransactionId();
            $note = composeSuffix(['Trans.' => $txnId, 'CC' => substr($ccNumber, -4)]);
            log_info('Eway transaction id return' . $note);
        }

        $payment = $this->getInvoicePaymentManager()->add(
            $input->invoice->Id,
            $input->paymentMethodId,
            $amountDetails->paymentAmount,
            $input->editorUserId,
            $note,
            $this->getCurrentDateUtc(),
            $txnId,
            $userBilling->CcType
        );

        if ($amountDetails->surcharge) {
            $this->createPaymentInvoiceAdditionalProducer()->add(
                paymentCalculationResult: $amountDetails->surcharge,
                invoiceId: $invoice->Id,
                paymentId: $payment->Id,
                accountId: $invoice->AccountId,
                editorUserId: $input->editorUserId
            );
        }

        log_debug('Saving invoice info');
        $invoice->toPaid();
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);
        $invoice->Reload();

        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $input->editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        if (
            !$existPayTraceId
            && $dataProvider->isPayTraceCim($invoice->AccountId)
        ) {
            log_info('PayTrace saving cim info.');
            $errorMessage = $this->createInvoiceUserCimUpdater()->update(
                $input->billingParams,
                $invoice,
                $user,
                $input->editorUserId
            );
            if ($errorMessage) {
                return $result->addError(Result::ERR_UPDATE_CIM_INFO, $errorMessage);
            }
        }

        $result->setAmount($amountDetails->paymentAmount);
        return $result;
    }

    protected function generateDescription(Invoice $invoice): string
    {
        $description = sprintf(
            $this->getTranslator()->translate("GENERAL_PAYMENT_ON_INVOICE", "general"),
            $invoice->InvoiceNo
        );
        $invoicedAuctionItemDtos = $this->createDataProvider()->loadInvoicedAuctionDtos($invoice->Id);
        if ($invoicedAuctionItemDtos) {
            $salesNoList = [];
            foreach ($invoicedAuctionItemDtos as $invoicedAuctionItemDto) {
                $salesNoList[] = $invoicedAuctionItemDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(',', $salesNoList) . ')';
        }
        return $description;
    }
}
