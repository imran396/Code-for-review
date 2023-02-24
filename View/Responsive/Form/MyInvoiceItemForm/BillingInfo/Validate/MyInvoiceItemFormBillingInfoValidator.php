<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Validate;

use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Common\MyInvoiceItemFormBillingInfoInput as Input;
use Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Validate\MyInvoiceItemFormBillingInfoValidationResult as Result;

class MyInvoiceItemFormBillingInfoValidator extends CustomizableClass
{
    use CurrentDateTrait;
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        if ($input->paymentMethodId === Constants\Payment::PM_BANK_WIRE) {
            $result = $this->validateWireBillingInfo($input);
        } else {
            $result = $this->validateRegularBillingInfo($input);
        }

        if ($result->hasError()) {
            return $result;
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    protected function validateWireBillingInfo(Input $input): Result
    {
        $result = Result::new()->construct();
        if (trim($input->billingBankWireRouteNumber) === '') {
            $result->addError(Result::ERR_BANK_WIRE_ROUTE_NUMBER_REQUIRED);
        }

        if (trim($input->billingBankWireAccountNumber) === '') {
            $result->addError(Result::ERR_BANK_WIRE_ACCOUNT_NUMBER_REQUIRED);
        }

        if ($input->wirePaymentGateway === Constants\SettingUser::PAY_AUTHORIZE_NET) {
            if ($input->billingBankWireAccountName === '') {
                $result->addError(Result::ERR_BANK_WIRE_ACCOUNT_NAME_REQUIRED);
            }
            if ($input->billingBankWireAccountType === '') {
                $result->addError(Result::ERR_BANK_WIRE_ACCOUNT_TYPE_REQUIRED);
            }
            if ($input->billingBankWireName === '') {
                $result->addError(Result::ERR_BANK_WIRE_NAME_REQUIRED);
            }
        }
        return $result;
    }

    protected function validateRegularBillingInfo(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $invoice = $dataProvider->loadInvoice($input->invoiceId, $input->isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($input->invoiceId);
        }

        if (trim($input->billingFirstName) === '') {
            $result->addError(Result::ERR_REQUIRED_FIRST_NAME);
        }

        if (trim($input->billingLastName) === '') {
            $result->addError(Result::ERR_REQUIRED_LAST_NAME);
        }

        if (trim($input->billingAddress) === '') {
            $result->addError(Result::ERR_REQUIRED_ADDRESS);
        }

        if (trim($input->billingCity) === '') {
            $result->addError(Result::ERR_REQUIRED_CITY);
        }

        if (trim($input->billingCountryCode) === '') {
            $result->addError(Result::ERR_REQUIRED_COUNTRY);
        }

        $addressChecker = AddressChecker::new();
        if (
            $addressChecker->isUsa($input->billingCountryCode)
            && !$input->billingState
        ) {
            $result->addError(Result::ERR_INVALID_US_STATE);
        }

        if (
            $addressChecker->isCanada($input->billingCountryCode)
            && !$input->billingState
        ) {
            $result->addError(Result::ERR_INVALID_CANADA_STATE);
        }

        if (
            $addressChecker->isMexico($input->billingCountryCode)
            && !$input->billingState
        ) {
            $result->addError(Result::ERR_INVALID_MEXICO_STATE);
        }

        if (
            !$addressChecker->isCountryWithStates($input->billingCountryCode)
            && trim($input->billingState) === ''
        ) {
            $result->addError(Result::ERR_REQUIRED_CUSTOM_STATE);
        }

        if (trim($input->billingZip) === '') {
            $result->addError(Result::ERR_REQUIRED_ZIP_CODE);
        }

        if ($input->ccType === null) {
            $result->addError(Result::ERR_CC_TYPE_REQUIRED);
        }

        if (!$input->expMonth) {
            $result->addError(Result::ERR_CC_EXP_MONTH_REQUIRED);
        }

        if (!$input->expYear) {
            $result->addError(Result::ERR_CC_EXP_YEAR_REQUIRED);
        }


        $creditCard = $dataProvider->loadCreditCard($input->ccType, $input->isReadOnlyDb);

        if (
            $dataProvider->isEwayEnabled($invoice->AccountId)
            && $dataProvider->getEwayEncryptionKey($invoice->AccountId) === ''
            && $input->ccNumber !== ''
            && (
                !$creditCard
                || !$dataProvider->isValidCreditCard($input->ccNumber, $creditCard->Name)
            )
        ) {
            return $result->addError(Result::ERR_CC_NUMBER_INVALID);
        }

        if (
            $input->wasCcEdited
            && $input->ccNumber !== ''
            && (
                !$creditCard
                || !$dataProvider->isValidCreditCard($input->ccNumber, $creditCard->Name)
            )
        ) {
            $result->addError(Result::ERR_CC_NUMBER_INVALID);
        }

        if (
            $input->wasCcEdited
            && $input->ccNumber === ''
        ) {
            $result->addError(Result::ERR_CC_NUMBER_REQUIRED);
        }

        $ewayEncryptionEnabled = $dataProvider->isEwayEnabled($invoice->AccountId)
            && $dataProvider->getEwayEncryptionKey($invoice->AccountId);

        if (
            !$input->wasCcEdited
            && !$this->isCimEnabled($invoice->AccountId)
            && !$ewayEncryptionEnabled
        ) {
            $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId);
            $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
            if (
                !$creditCard
                || !$dataProvider->isValidCreditCard($ccNumber, $creditCard->Name)
            ) {
                $result->addError(Result::ERR_CC_NUMBER_INVALID);
            }
        }

        if (
            $input->expMonth
            && $input->expYear
        ) {
            $date = $dataProvider->getCardExpDate($input->expMonth, $input->expYear);
            $dateCurrent = $this->getCurrentDateUtc();
            if ($date->getTimestamp() <= $dateCurrent->getTimestamp()) {
                $result->addError(Result::ERR_CC_EXP_DATE_EXPIRED);
            }
        }
        return $result;
    }

    protected function isCimEnabled(int $accountId): bool
    {
        $dataProvider = $this->createDataProvider();
        return $dataProvider->isOpayoToken($accountId)
            || $dataProvider->isPayTraceCim($accountId)
            || $dataProvider->isNmiVault($accountId)
            || $dataProvider->isAuthNetCim($accountId);
    }
}
