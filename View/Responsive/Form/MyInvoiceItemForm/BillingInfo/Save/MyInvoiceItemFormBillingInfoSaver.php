<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Save;

use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Storage\WriteRepository\Entity\InvoiceUserBilling\InvoiceUserBillingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Common\MyInvoiceItemFormBillingInfoInput as Input;
use Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Save\Internal\Load\DataProviderCreateTrait;

class MyInvoiceItemFormBillingInfoSaver extends CustomizableClass
{
    use CcExpiryDateBuilderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use DataProviderCreateTrait;
    use InvoiceUserBillingWriteRepositoryAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Input $input
     * @return void
     */
    public function save(Input $input): void
    {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($input->invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($input->invoiceId);
        }
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        if ($input->paymentMethodId === Constants\Payment::PM_CC) {
            log_debug('Credit card payment; saving billing info');
            if ($input->wasCcEdited) { // CC number has been edited
                $ccNumber = trim($input->ccNumber);
            } else {
                $ccNumber = $dataProvider->decrypt($userBilling->CcNumber);
            }


            $expiration = $this->createCcExpiryDateBuilder()->implode(
                $input->expMonth,
                $input->expYear
            ); // MM-YYYY
            $billingCompanyName = trim($input->billingCompanyName);
            $billingFirstName = trim($input->billingFirstName);
            $billingLastName = trim($input->billingLastName);
            $billingAddress = trim($input->billingAddress);
            $billingCity = trim($input->billingCity);
            $country = $input->billingCountryCode;
            $billingZip = trim($input->billingZip);
            $billingPhone = trim($input->billingPhone);

            $userBilling->CompanyName = $billingCompanyName;
            $userBilling->FirstName = $billingFirstName;
            $userBilling->LastName = $billingLastName;
            $userBilling->Phone = $billingPhone;
            $userBilling->Fax = trim($input->billingFax);
            $userBilling->Country = $country;
            $userBilling->Address = $billingAddress;
            $userBilling->Address2 = trim($input->billingAddress2);
            $userBilling->Address3 = trim($input->billingAddress3);
            $userBilling->City = $billingCity;
            $userBilling->State = $input->billingState;
            $userBilling->Zip = $billingZip;

            $hasAuthNetCimProfile = $dataProvider->isAuthNetCim($invoice->AccountId)
                && $userBilling->AuthNetCpi !== ''
                && $userBilling->AuthNetCppi !== '';

            $opayoTokenId = $dataProvider->decrypt($userBilling->OpayoTokenId);
            $hasOpayoTokenProfile = $opayoTokenId !== ''
                && $dataProvider->isOpayoToken($invoice->AccountId);

            $payTraceCustId = $dataProvider->decrypt($userBilling->PayTraceCustId);
            $hasPayTraceCustomerId = $payTraceCustId !== ''
                && $dataProvider->isPayTraceCim($invoice->AccountId);

            $nmiVaultId = $dataProvider->decrypt($userBilling->NmiVaultId);
            $hasNmiVaultId = $nmiVaultId !== ''
                && $dataProvider->isNmiVault($invoice->AccountId);

            if ($input->wasCcEdited) { // CC number has been edited
                if (
                    $hasAuthNetCimProfile
                    || $hasOpayoTokenProfile
                    || $hasPayTraceCustomerId
                    || $hasNmiVaultId
                ) {
                    $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
                } else {
                    $userBilling->CcNumber = $dataProvider->encrypt($ccNumber);
                }
                $userBilling->CcNumberHash = $this->getCcNumberEncrypter()->createHash($ccNumber);
                $userBilling->CcExpDate = $expiration;
                $userBilling->CcType = $input->ccType;
                $encryptedKey = $dataProvider->getEwayEncryptionKey($invoice->AccountId);
                if (
                    $dataProvider->decrypt($encryptedKey) !== ''
                    && $dataProvider->isEwayEnabled($invoice->AccountId)
                ) {
                    $userBilling->CcNumberEway = $input->ccNumberEway;
                    $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
                }
            }
            $invoiceBilling = $dataProvider->loadInvoiceUserBillingOrCreate($input->invoiceId, $input->isReadOnlyDb);
            $invoiceBilling->CompanyName = $billingCompanyName;
            $invoiceBilling->FirstName = $billingFirstName;
            $invoiceBilling->LastName = $billingLastName;
            $invoiceBilling->Phone = $billingPhone;
            $invoiceBilling->Fax = trim($input->billingFax);
            $invoiceBilling->Country = $input->billingCountryCode;
            $invoiceBilling->Address = $billingAddress;
            $invoiceBilling->Address2 = trim($input->billingAddress2);
            $invoiceBilling->Address3 = trim($input->billingAddress3);
            $invoiceBilling->City = $billingCity;
            $invoiceBilling->State = $input->billingState;
            $invoiceBilling->Zip = $billingZip;
            $this->getInvoiceUserBillingWriteRepository()->saveWithModifier($invoiceBilling, $input->editorUserId);
        } elseif ($input->paymentMethodId === Constants\Payment::PM_BANK_WIRE) { // Echeck
            log_debug('ECheck payment; saving billing info');

            $userBilling->BankRoutingNumber = trim($input->billingBankWireRouteNumber);
            $bankAccountNumber = trim($input->billingBankWireAccountNumber);
            $userBilling->BankAccountNumber = $dataProvider->encrypt($bankAccountNumber);
            $userBilling->BankAccountType = $input->billingBankWireAccountType;
            $userBilling->BankName = trim($input->billingBankWireName);
            $userBilling->BankAccountName = trim($input->billingBankWireAccountName);
            $userBilling->BankAccountHolderType = trim($input->billingBankWireAccountHolderType);
        }
        $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $input->editorUserId);
    }
}
