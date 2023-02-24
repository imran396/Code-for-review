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

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi;

use Payment_NmiCustomerVault;
use Payment_NmiDirectPost;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi\AdminStackedTaxInvoicePaymentNmiChargingInput as Input;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi\AdminStackedTaxInvoicePaymentNmiChargingResult as Result;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Lot\Move\BlockSoldLotsCheckerCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;
use Sam\Core\Constants;

class AdminStackedTaxInvoicePaymentNmiCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use AdminTranslatorAwareTrait;
    use BlockCipherProviderCreateTrait;
    use BlockSoldLotsCheckerCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CreditCardValidatorAwareTrait;
    use CurrentDateTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use InvoiceUserLoaderAwareTrait;
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
        $amount = sprintf("%1.2F", Floating::roundOutput($amount));
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
        $billingFirstName = $billingLastName = $expMonth = $expYear = $ccNumber = $ccCode
            = $bAddress = $bCity = $bState = $bZip = $bCountry = $bPhone = '';
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $nmiVaultId = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->NmiVaultId);
        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
            $bCountry = $invoiceUserBilling->Country;
            $bAddress = $invoiceUserBilling->Address;
            $bCity = $invoiceUserBilling->City;
            $bState = $invoiceUserBilling->State;
            $bZip = $invoiceUserBilling->Zip;

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
                $params = $this->createAdminStackedTaxInvoicePaymentChargingHelper()->getParams(
                    $invoice,
                    $ccNumber,
                    $ccType,
                    $expMonth,
                    $expYear,
                    $ccCode
                );
                $error = $this->createAdminStackedTaxInvoicePaymentChargingHelper()->updateCimInfo(
                    $params,
                    $invoice,
                    $user,
                    $editorUserId
                );

                if ($error) {
                    return $result->addError(Result::ERR_UPDATE_CIM_INFO, $error);
                }
            }
            log_debug(composeLogData(['Other CC Number' => $this->cutCcLast4($ccNumber)]));
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
                    $params = $this->createAdminStackedTaxInvoicePaymentChargingHelper()->getParams(
                        $invoice,
                        $ccNumber,
                        $ccType,
                        $expMonth,
                        $expYear,
                    );
                    $error = $this->createAdminStackedTaxInvoicePaymentChargingHelper()->updateCimInfo(
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
                $bPhone = $invoiceUserBilling->Phone;
                $bCountry = $invoiceUserBilling->Country;
                $bAddress = $invoiceUserBilling->Address;
                $bCity = $invoiceUserBilling->City;
                $bState = $invoiceUserBilling->State;
                $bZip = $invoiceUserBilling->Zip;
            }
            log_debug(composeLogData(['Profile CC Number' => $this->cutCcLast4($ccNumber)]));
        }

        $amount = sprintf("%01.2F", $amount);

        if (
            $isNmiVault
            && $input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE
        ) {
            log_debug('Charging invoice using cim' . composeSuffix(['nv' => $nmiVaultId]));
            $nmiVaultManager = new Payment_NmiCustomerVault($invoice->AccountId);
            $nmiVaultManager->setParameter('customer_vault_id', $nmiVaultId);
            $nmiVaultManager->setParameter('amount', $amount);
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
                        'CC number' => $this->cutCcLast4($ccNumber),
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
            return $result->addError(Result::ERR_BIDDER_CARD);
        }

        return $result
            ->setNoteInfo($nmiVaultManager->getTransactionId(), $this->cutCcLast4($ccNumber));
    }

    protected function cutCcLast4(string $ccNumber): string
    {
        return substr($ccNumber, -4);
    }
}
