<?php
/**
 * SAM-10971: Stacked Tax. Public My Invoice pages. Extract Authorize.net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge;

use Email_Template;
use Payment_AuthorizeNet;
use Payment_AuthorizeNetCim;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCimUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge\ResponsiveInvoiceViewAuthorizeNetChargingInput as Input;
use Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge\ResponsiveInvoiceViewAuthorizeNetChargingResult as Result;
use Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount\InvoiceChargeAmountDetailsFactoryCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce\PaymentInvoiceAdditionalProducerCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;


class ResponsiveInvoiceViewAuthorizeNetCharger extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use InvoiceChargeAmountDetailsFactoryCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceUserCimUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentInvoiceAdditionalProducerCreateTrait;

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
                "Available invoice winning user not found, when charge payment through AuthorizeNet"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $account = $invoice->AccountId;
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $amountDetails = $this->createInvoiceChargeAmountDetailsFactory()->create(
            invoice: $invoice,
            paymentMethod: $input->paymentMethodId,
            creditCardType: $userBilling->CcType,
            netAmount: $input->amount
        );
        $errorMessage = '';

        if ($input->paymentMethodId === Constants\Payment::PM_CC) {
            $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
            $isAuthNetCimProfile = $userBilling->AuthNetCpi !== ''
                && $userBilling->AuthNetCppi !== '';

            if (
                !$input->wasCcEdited
                && $dataProvider->isAuthNetCim($invoice->AccountId)
                && $isAuthNetCimProfile
            ) {
                $amountFormatted = sprintf("%1.2F", Floating::roundOutput($amountDetails->paymentAmount));
                $authNetCpi = $dataProvider->decryptValue($userBilling->AuthNetCpi);
                $authNetCppi = $dataProvider->decryptValue($userBilling->AuthNetCppi);
                $authNetCai = $dataProvider->decryptValue($userBilling->AuthNetCai);
                // Cc number was not changed and CIM is enabled
                $authNet = new Payment_AuthorizeNetCim($invoice->AccountId);
                $authNet->setParameter('order_description', $input->orderDescription);
                $authNet->setParameter('order_invoiceNumber', $invoice->InvoiceNo);
                $authNet->setParameter('transaction_amount', $amountFormatted); // Up to 4 digits with a decimal (required)
                $authNet->setParameter('transactionType', 'profileTransAuthCapture'); // see options above
                $authNet->setParameter('customerProfileId', $authNetCpi); // Numeric (required)
                $authNet->setParameter('customerPaymentProfileId', $authNetCppi); // Numeric (required)
                if ($authNetCai !== '') {
                    $authNet->setParameter('customerShippingAddressId', $authNetCai); // Numeric (optional)
                }
                $authNet->createCustomerProfileTransactionRequest();

                if (!$authNet->isSuccessful()) {
                    $errorMessage .= 'Problem encountered bidder CIM payment. <br>' . $authNet->code . ': ' .
                        ($authNet->directResponse ? $authNet->directResponseErr : '');
                    $errorMessage .= ' ' . $authNet->text;
                }
            } else {
                $authNet = new Payment_AuthorizeNet($account);
                $authNet->setTransactionType($this->cfg()->get('core->billing->gate->authorizeNet->type'));
                $authNet->setParameter('x_description', $input->orderDescription);
                $authNet->setParameter('x_first_name', $userBilling->FirstName);
                $authNet->setParameter('x_last_name', $userBilling->FirstName);
                $authNet->setParameter('x_address', $userBilling->Address);
                $authNet->setParameter('x_city', $userBilling->City);
                $authNet->setParameter('x_state', $userBilling->State);
                $authNet->setParameter('x_zip', $userBilling->Zip);
                if (trim($input->ccCode) !== '') {
                    $authNet->setParameter('x_card_code', $input->ccCode);
                }

                $authNet->setParameter('x_invoice_num', $invoice->InvoiceNo);
                $authNet->setParameter('x_country', $userBilling->Country);
                $authNet->setParameter('x_phone', $userBilling->Phone);
                $authNet->setParameter('x_fax', $userBilling->Fax);

                $user = $dataProvider->loadUser($invoice->BidderId, $input->isReadOnlyDb);
                $authNet->setParameter('x_email', $user->Email);
                $authNet->setParameter('x_cust_id', $user->CustomerNo);

                $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
                $authNet->transaction($ccNumber, $userBilling->CcExpDate, $amountDetails->paymentAmount);
                $authNet->process(2);
                if (
                    !$authNet->isError()
                    || $authNet->isTransactionInReview()
                ) {
                    if ($authNet->isDeclined()) {
                        $errorMessage .= 'Credit Card Declined. <br />';
                        $errorMessage .= 'Code : ' . $authNet->getResponseCode() . ' ' .
                            $authNet->getResponseText() . '<br />';
                        $errorMessage .= ($authNet->getCardCodeResponse() !== '') ?
                            'Credit Card :' . $authNet->getCardCodeResponse() .
                            ' <br />' : '';
                    }
                } else {
                    $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                    $errorMessage .= 'Code : ' . $authNet->getResponseCode() . ' ' .
                        $authNet->getResponseText() . '<br />';
                    $errorMessage .= ($authNet->getCardCodeResponse() !== '') ?
                        'Credit Card :' . $authNet->getCardCodeResponse() .
                        ' <br />' : '';
                }
            }
        } elseif ($input->paymentMethodId === Constants\Payment::PM_BANK_WIRE) { // pay via wire transfer
            $authNet = new Payment_AuthorizeNet($account);
            $accountTypeAuthNet = Constants\BillingBank::ACCOUNT_TYPE_AUTH_NET_VALUES[$userBilling->BankAccountType];
            $authNet->echeck(
                $userBilling->BankRoutingNumber,
                $userBilling->BankAccountNumber,
                $accountTypeAuthNet,
                $userBilling->BankName,
                $userBilling->BankAccountName,
                'WEB',
                $amountDetails->paymentAmount
            );

            $authNet->setParameter('x_recurring_billing', 'NO');
            $authNet->process(2);
            if (
                !$authNet->isError()
                || $authNet->isTransactionInReview()
            ) {
                if ($authNet->isDeclined()) {
                    $errorMessage .= 'Credit Card Declined. <br />';
                    $errorMessage .= 'Code : ' . $authNet->getResponseCode() . ' ' .
                        $authNet->getResponseText() . '<br />';
                    $errorMessage .= ($authNet->getCardCodeResponse() !== '') ?
                        'Wire :' . $authNet->getCardCodeResponse() .
                        ' <br />' : '';
                }
            } else {
                $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                $errorMessage .= 'Code : ' . $authNet->getResponseCode() . ' ' .
                    $authNet->getResponseText() . '<br />';
                $errorMessage .= ($authNet->getCardCodeResponse() !== '') ?
                    'Wire :' . $authNet->getCardCodeResponse() .
                    ' <br />' : '';
            }
        }

        if ($errorMessage !== '') {
            log_warning($errorMessage);
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        log_info(
            'Payment on invoice was successful'
            . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );

        $note = '';
        $txnId = '';

        if (
            isset($authNet)
            && !$authNet->isTestMode()
            && $input->paymentMethodId === Constants\Payment::PM_CC
        ) {
            $txnId = $authNet->getTransactionId();
            $note = composeSuffix(['Trans.' => $txnId, 'CC' => substr($ccNumber ?? '', -4)]);
            if ($authNet->isTransactionInReview()) {
                $note = 'Payment with Trans.: ' . $authNet->getTransactionId() . ' was marked as "Held for Review"';
            }
            log_info('Auth.Net transaction id return' . $note);
        }

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
        if (
            isset($authNet)
            && !$authNet->isTransactionInReview()
        ) {
            $invoice->toPaid();
        }
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);
        $invoice->Reload();

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
            $dataProvider->isAuthNetCim($invoice->AccountId)
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
