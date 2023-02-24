<?php
/**
 * SAM-10974: Stacked Tax. Public My Invoice pages. Extract NMI invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge;

use Email_Template;
use Invoice;
use Payment_NmiCustomerVault;
use Payment_NmiDirectPost;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCimUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount\InvoiceChargeAmountDetailsFactoryCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge\ResponsiveInvoiceViewNmiChargingInput as Input;
use Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge\ResponsiveInvoiceViewNmiChargingResult as Result;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce\PaymentInvoiceAdditionalProducerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class ResponsiveInvoiceViewNmiCharger
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge
 */
class ResponsiveInvoiceViewNmiCharger extends CustomizableClass
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
                "Available invoice winning user not found, when charge payment through Nmi"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        // CC owner name must be the same format appears on credit card

        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $nmiVaultId = $dataProvider->decryptValue($userBilling->NmiVaultId);

        $existNmiId = !$input->wasCcEdited
            && $dataProvider->isNmiVault($invoice->AccountId)
            && $nmiVaultId;

        $amountDetails = $this->createInvoiceChargeAmountDetailsFactory()->create(
            invoice: $invoice,
            paymentMethod: $input->paymentMethodId,
            creditCardType: $userBilling->CcType,
            netAmount: $input->balanceDue
        );

        $user = $dataProvider->loadUser($invoice->BidderId, $input->isReadOnlyDb);
        if ($input->paymentMethodId === Constants\Payment::PM_CC) {
            $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
            [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

            // Cc payment

            if ($existNmiId) {
                // Cc number was not changed and CIM is enabled
                $amountFormatted = sprintf("%1.2F", Floating::roundOutput($amountDetails->paymentAmount));
                $nmi = new Payment_NmiCustomerVault($invoice->AccountId);
                $nmi->setParameter('orderdescription', $this->generateDescription($invoice));
                $nmi->setParameter('customer_vault_id', $nmiVaultId);
                $nmi->setParameter('amount', $amountFormatted);
                $nmi->setParameter('zip', $userBilling->Zip);
                $nmi->process(2);
            } else {
                $nmi = new Payment_NmiDirectPost($invoice->AccountId);
                $nmi->setTransactionType('sale');
                $nmi->setParameter('orderdescription', $this->generateDescription($invoice));
                $nmi->setParameter('firstname', $userBilling->FirstName);
                $nmi->setParameter('lastname', $userBilling->LastName);
                $nmi->setParameter('address1', $userBilling->Address);
                $nmi->setParameter('city', $userBilling->City);
                if ($userBilling->State) {
                    $nmi->setParameter('state', $userBilling->State);
                }
                $nmi->setParameter('country', $userBilling->Country);
                $nmi->setParameter('zip', $userBilling->Zip);
                $nmi->setParameter('phone', $userBilling->Phone);
                $nmi->setParameter('email', $user->Email);
                $nmi->transaction(
                    $ccNumber,
                    $ccExpMonth,
                    $ccExpYear,
                    $amountDetails->paymentAmount,
                    $input->ccCode
                );
                $nmi->process(2);
            }
        } elseif ($input->paymentMethodId === Constants\Payment::PM_BANK_WIRE) {
            $invoiceBilling = $dataProvider->loadInvoiceUserBillingOrCreate($invoice->Id, $input->isReadOnlyDb);
            $nmi = new Payment_NmiDirectPost($invoice->AccountId);
            $nmi->setParameter('zip', $invoiceBilling->Zip);
            $nmi->setParameter('firstname', $invoiceBilling->FirstName);
            $nmi->setParameter('lastname', $invoiceBilling->LastName);
            $accountHolderTypeNmi = Constants\BillingBank::ACCOUNT_HOLDER_TYPE_NMI_VALUES[$userBilling->BankAccountHolderType] ?? '';
            $accountTypeNmi = Constants\BillingBank::ACCOUNT_TYPE_NMI_VALUES[$userBilling->BankAccountType] ?? '';
            $nmi->echeck(
                $userBilling->BankAccountName,
                $userBilling->BankRoutingNumber,
                $userBilling->BankAccountNumber,
                $accountHolderTypeNmi,
                $accountTypeNmi,
                $amountDetails->paymentAmount
            );
            $nmi->process(2);
        } else {
            log_debug('No payment method click');
            $result->addError(Result::ERR_PAYMENT_METHOD_NOT_MATCHED);
            return $result;
        }

        $errorMessage = '';
        if (!$nmi->isError()) {
            if ($nmi->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $nmi->getErrorReport() . ':' . $nmi->getResponseText();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $nmi->getErrorReport() . ':' . $nmi->getResponseText();
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
            $txnId = $nmi->getTransactionId();
            $note = composeSuffix(['Trans.' => $txnId, 'CC' => substr($ccNumber ?? '', -4)]);
            log_info('Nmi transaction id return' . $note);
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
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice, $input->isReadOnlyDb);
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
            !$existNmiId
            && $dataProvider->isNmiVault($invoice->AccountId)
        ) {
            log_info('Nmi saving cim info.');
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
        return $description;
    }
}
