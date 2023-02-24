<?php
/**
 * <Description of class>
 *
 * SAM-10919: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Nmi invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Nmi;

use Email_Template;
use Payment_NmiCustomerVault;
use Payment_NmiDirectPost;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common\AdminInvoiceEditChargingHelperCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Nmi\AdminInvoiceEditNmiChargingInput as Input;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Nmi\AdminInvoiceEditNmiChargingResult as Result;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Lot\Move\BlockSoldLotsCheckerCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;
use Sam\Core\Constants;

/**
 * Class AdminInvoiceEditNmiCharger
 * @package
 */
class AdminInvoiceEditNmiCharger extends CustomizableClass
{
    use AdminInvoiceEditChargingHelperCreateTrait;
    use AdminTranslatorAwareTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BlockCipherProviderCreateTrait;
    use BlockSoldLotsCheckerCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CreditCardValidatorAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingCheckerAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

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
        $editorUserId = $input->editorUserId;
        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        $isNmiVault = false;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $amount = $nf->parseMoney($input->amountFormatted);
        $user = $this->getUserLoader()->load($invoice->BidderId);
        if (!$user) {
            log_error(
                "Unable to charge invoice of deleted winning bidder"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($user->Id);
        log_trace(composeLogData(['Charge amount' => $amount]));
        $billingFirstName = $billingLastName = $expMonth = $expYear = $ccType = $ccNumber = $ccCode
            = $bAddress = $bCity = $bState = $bZip = $bCountry = $bPhone = '';
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $nmiVaultId = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->NmiVaultId);
        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            $bCountry = $input->country;
            $bAddress = trim($input->address1);
            $bCity = trim($input->city);
            $bState = trim($input->state);
            $bZip = trim($input->city);
            $billingFirstName = trim($input->firstName);
            $billingLastName = trim($input->lastName);
            $expMonth = $input->expMonth;
            $expYear = $input->expYear;
            $ccType = $input->ccType;
            $ccNumber = trim($input->ccNumber);
            $ccCode = trim($input->ccCode);

            if ($input->isReplaceOldCard) {
                $this->createInvoiceUserCcInfoUpdater()->update(
                    $invoice->AccountId,
                    $editorUserId,
                    $user->Id,
                    $input->ccNumber,
                    $input->ccType,
                    $input->expYear,
                    $input->expMonth
                );
                $params = $this->createAdminInvoiceEditChargingHelper()->getParams(
                    $invoice,
                    $ccNumber,
                    $ccType,
                    $expMonth,
                    $expYear,
                    $ccCode
                );
                $error = $this->createAdminInvoiceEditChargingHelper()->updateCimInfo(
                    $params,
                    $invoice,
                    $user,
                    $editorUserId
                );

                if ($error) {
                    return $result->addError(Result::ERR_UPDATE_CIM_INFO, $error);
                }
            }
            log_debug(composeLogData(['Other CC Number' => substr($ccNumber, -4)]));
        } else {
            $isNmiVault = $this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $invoice->AccountId);
            if ($isNmiVault) { // USE VAULT
                $vaultProfile = $nmiVaultId !== '';
                if (!$vaultProfile) { // No Customer Profile Yet
                    if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                        return $result->addError(Result::ERR_CARD_INFO_NOT_FOUND);
                    }
                    $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
                    if (!$this->getCreditCardValidator()->validateNumber($ccNumber)) { // Check if a valid cc
                        $creditCardError = $this->getAdminTranslator()->trans('billing.cc_charge.error.invalid_credit_card_number', [], 'admin_invoice_item');
                        return $result->addError(Result::ERR_INVALID_CREDIT_CARD, $creditCardError);
                    }
                    $ccType = (int)$userBilling->CcType;
                    [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                    $params = $this->createAdminInvoiceEditChargingHelper()->getParams(
                        $invoice,
                        $ccNumber,
                        $ccType,
                        $expMonth,
                        $expYear,
                    );
                    $error = $this->createAdminInvoiceEditChargingHelper()->updateCimInfo(
                        $params,
                        $invoice,
                        $user,
                        $editorUserId
                    );
                    if ($error) {
                        return $result->addError(Result::ERR_UPDATE_CIM_INFO, $error);
                    }
                }
            } else {
                if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                    return $result->addError(Result::ERR_CARD_INFO_NOT_FOUND);
                }

                $billingFirstName = $invoiceUserBilling->FirstName;
                $billingLastName = $invoiceUserBilling->LastName;
                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
                $ccType = (int)$userBilling->CcType;
                $bPhone = $invoiceUserBilling->Phone;
                $bCountry = $invoiceUserBilling->Country;
                $bAddress = $invoiceUserBilling->Address;
                $bCity = $invoiceUserBilling->City;
                $bState = $invoiceUserBilling->State;
                $bZip = $invoiceUserBilling->Zip;
            }
            log_debug(composeLogData(['Profile CC Number' => substr($ccNumber, -4)]));
        }
        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $ccType,
            $amount,
            $invoice,
            $input->editorUserId
        );

        if (
            $isNmiVault
            && $input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE
        ) {
            log_debug('Charging invoice using cim' . composeSuffix(['nv' => $nmiVaultId]));
            $nmiVaultManager = new Payment_NmiCustomerVault($invoice->AccountId);
            $nmiVaultManager->setParameter('customer_vault_id', $nmiVaultId);
            $nmiVaultManager->setParameter('amount', sprintf("%01.2F", $amount));
            $nmiVaultManager->setParameter('zip', $bZip);
        } else {
            log_debug('Charging invoice using cc info');
            $nmiVaultManager = new Payment_NmiDirectPost($invoice->AccountId);
            $nmiVaultManager->setTransactionType('sale');
            $nmiVaultManager->setParameter('firstname', $billingFirstName);
            $nmiVaultManager->setParameter('lastname', $billingLastName);
            if (strlen($bCountry) === 2) {
                $nmiVaultManager->setParameter('country', $bCountry);
            }
            $nmiVaultManager->setParameter('address1', $bAddress);
            $nmiVaultManager->setParameter('city', $bCity);
            if (strlen($bState) === 2) {
                $nmiVaultManager->setParameter('state', $bState);
            }
            $nmiVaultManager->setParameter('zip', $bZip);
            $nmiVaultManager->setParameter('phone', $bPhone);
            $email = $this->getUserRenderer()->renderAccountingEmail($user, null, $invoiceUserBilling);
            $nmiVaultManager->setParameter('email', $email);
            $nmiVaultManager->transaction($ccNumber, $expMonth, $expYear, $amount, $ccCode);
            log_debug(
                composeSuffix(
                    [
                        'CC number' => substr($ccNumber, -4),
                        'Expiration' => "{$expMonth} - {$expYear}",
                        'Amount' => $amount,
                    ]
                )
            );
        }
        $nmiVaultManager->setParameter('orderdescription', $description);
        $nmiVaultManager->process(2);

        $errorMessage = '';
        if (!$nmiVaultManager->isError()) {
            if ($nmiVaultManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $nmiVaultManager->getErrorReport();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $nmiVaultManager->getErrorReport();
        }

        if ($errorMessage !== '') {
            log_debug(composeLogData(['Error charging invoice' => $errorMessage]));
            $this->getDb()->TransactionRollback();
            return $result->addError(Result::ERR_BIDDER_CARD);
        }

        // Nmi always return transaction id even in test mode
        $note = 'Trans.:' . $nmiVaultManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

        $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC,
            $amount,
            $editorUserId,
            $note,
            $this->getCurrentDateUtc(),
            null,
            $ccType
        );
        $balanceDue = $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);
        log_trace(composeLogData(['Balance due' => $balanceDue]));
        if (Floating::lteq($balanceDue, 0.)) {
            $invoice->toPaid();
        }
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $this->getDb()->TransactionCommit();
        // Email customer as a proof of their payment
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
        return $result;
    }
}
