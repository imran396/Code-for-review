<?php
/**
 * SAM-10967: Stacked Tax. Public My Invoice pages. Extract Opayo invoice charging.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Opayo\Charge;


use Email_Template;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\Common\TransactionParameter\TransactionParameterCollection;
use Sam\Billing\Gate\Opayo\Payment\OpayoGateManagerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCimUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Common\ResponsiveInvoiceViewChargingHelperCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount\InvoiceChargeAmountDetailsFactoryCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Opayo\Charge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Opayo\Charge\ResponsiveInvoiceViewOpayoChargingInput as Input;
use Sam\Invoice\Common\Charge\Responsive\Gate\Opayo\Charge\ResponsiveInvoiceViewOpayoChargingResult as Result;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce\PaymentInvoiceAdditionalProducerCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

class ResponsiveInvoiceViewOpayoCharger extends CustomizableClass
{
    use CcExpiryDateBuilderCreateTrait;
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use InvoiceChargeAmountDetailsFactoryCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceUserCimUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use OpayoGateManagerCreateTrait;
    use PaymentInvoiceAdditionalProducerCreateTrait;
    use ResponsiveInvoiceViewChargingHelperCreateTrait;

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
        $dataProvider = $this->createDataProvider();
        $invoice = $input->invoice;

        $isFound = $dataProvider->existUser($invoice->BidderId, $input->isReadOnlyDb);
        if (!$isFound) {
            log_error(
                "Available invoice winning user not found, when charge payment through Opayo"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $isCcModified = false;

        $account = $invoice->AccountId;
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
        $amountDetails = $this->createInvoiceChargeAmountDetailsFactory()->create(
            invoice: $invoice,
            paymentMethod: Constants\Payment::PM_CC,
            creditCardType: $userBilling->CcType,
            netAmount: $input->amount
        );

        $errorMessage = '';
        $description = 'Payment on Invoice ' . $invoice->InvoiceNo;
        $amountFormatted = sprintf("%1.2F", Floating::roundOutput($amountDetails->paymentAmount));

        $opayoTokenId = $dataProvider->decryptValue($userBilling->OpayoTokenId);
        $hasOpayoTokenProfile = $opayoTokenId !== '';

        $opayoManager = $this->createOpayoGateManager()->init($account);
        $opayoManager->setTransactionType('PAYMENT');
        $opayoManager->setParameter('Description', $description);
        $firstName = $userBilling->FirstName;
        $lastName = $userBilling->LastName;
        $opayoManager->setParameter('BillingFirstnames', $firstName);
        $opayoManager->setParameter('BillingSurname', $lastName);
        $opayoManager->setParameter('BillingAddress1', $userBilling->Address);
        $opayoManager->setParameter('BillingCity', $userBilling->City);

        $country = $userBilling->Country;
        $bState = $userBilling->State;
        if ($bState !== '') {
            $opayoManager->setParameter('BillingState', $bState);
        }
        $opayoManager->setParameter('BillingCountry', $country);

        $opayoManager->setParameter('BillingPostCode', $userBilling->Zip);
        $opayoManager->setParameter('BillingPhone', $userBilling->Phone);
        $isOpayoToken = $dataProvider->isOpayoToken($invoice->AccountId);
        if (
            $isOpayoToken
            && $hasOpayoTokenProfile
        ) { // USE CIM
            $opayoManager->setParameter('VendorTxCode', $opayoManager->vendorTxCode());
            $opayoManager->setParameter('Token', $opayoTokenId);
            $opayoManager->setParameter('StoreToken', 1);
            $opayoManager->setParameter('Amount', $amountFormatted);
            $opayoManager->setParameter('CV2', $input->ccCode);
        } else {
            $isCcModified = true;
            $fullName = UserPureRenderer::new()->makeFullName($firstName, $lastName);
            $opayoManager->setParameter('CustomerName', $fullName);
            $opayoManager->setParameter('CardHolder', $fullName);
            $accountingEmail = $this->createResponsiveInvoiceViewChargingHelper()->getAccountingEmail($invoice->BidderId, $userBilling->Email);
            $opayoManager->setParameter('CustomerEMail', $accountingEmail);
            $creditCard = $dataProvider->loadCreditCard($userBilling->CcType, $input->isReadOnlyDb);
            $cardType = $opayoManager->getCardType($creditCard->Name);
            $opayoManager->setParameter('CardType', $cardType);
            $opayoManager->setParameter('Amount', $amountFormatted);
            [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
            $opayoManager->transaction($ccNumber, $ccExpMonth, $ccExpYear, $amountDetails->paymentAmount, $input->ccCode);
        }
        $opayoManager->process(2);

        $resultStatus = $opayoManager->getResultResponse();
        $threeDStatusResponse = $opayoManager->get3dStatusResponse();
        $threeDSecureUnavailable = false;

        if (!$opayoManager->isError()) {
            if ($opayoManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $opayoManager->getErrorReport();
            }
            if (
                $resultStatus === Constants\BillingOpayo::STATUS_3D_AUTH
                && $threeDStatusResponse === Constants\BillingOpayo::STATUS_OK
            ) {
                $tpc = TransactionParameterCollection::new()
                    ->setParams($input->billingParams)
                    ->enableCcModified($isCcModified)
                    ->setVpsTxId($opayoManager->getTransactionId())
                    ->setAmount($amountDetails->paymentAmount)
                    ->setPaymentItemId($invoice->Id)
                    ->setPaymentType(Constants\BillingOpayo::PT_CHARGE_RESPONSIVE_INVOICE_VIEW)
                    ->setPaymentUrl($input->paymentUrl)
                    ->setSessionId($input->sessionId)
                    ->setUserId($invoice->BidderId);
                $opayoManager->saveAsPaymentPending($tpc, $input->editorUserId);

                $opayoManager->forwardTo3DSecure();
            } elseif (
                $resultStatus === Constants\BillingOpayo::STATUS_OK
                && (
                    $threeDStatusResponse === Constants\BillingOpayo::STATUS_CANT_AUTH
                    || $threeDStatusResponse === Constants\BillingOpayo::STATUS_ERROR
                )
            ) {
                $threeDSecureUnavailable = true;
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $opayoManager->getErrorReport();
        }


        if ($errorMessage !== '') {
            log_warning($errorMessage);
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        log_info(
            'Payment on invoice was successful'
            . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );

        $txnId = $opayoManager->getTransactionId();
        if ($threeDSecureUnavailable) {
            $note = composeSuffix(['3D Secure failed' => true, 'Trans.' => $txnId, 'CC' => substr($ccNumber, -4)]);
        } else {
            $note = composeSuffix(['Trans.' => $txnId, 'CC' => substr($ccNumber, -4)]);
        }
        log_info('Opayo transaction id return' . $note);

        log_debug(
            'Adding payment for invoice' .
            composeSuffix(['id' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC,
            $amountDetails->paymentAmount,
            $input->editorUserId,
            $note,
            null,
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
        if (!$threeDSecureUnavailable) {
            log_debug('Saving invoice info');
            $invoice->toPaid();
            $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);
            $invoice->Reload();
        }
        log_debug(
            'Sending payment confirmation for invoice' .
            composeSuffix(['id' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $input->editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        if (
            $isOpayoToken
            && $input->wasCcEdited
        ) {
            $user = $dataProvider->loadUser($invoice->BidderId, $input->isReadOnlyDb);
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

        return Result::new()->construct($amountDetails->paymentAmount);
    }
}
