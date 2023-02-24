<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\ProfileCcCharge;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\ProfileCcCharge\ProfileCcChargingResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\ProfileCcCharge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;

class ProfileCcCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function charge(
        Invoice $invoice,
        float $amount,
        string $ccCode,
        bool $isReadOnlyDb = false,
    ): Result {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        log_debug('Charging invoice using cc info');
        $description = 'Charging Invoice ' . $invoice->InvoiceNo;

        $invoiceUserBilling = $dataProvider->loadInvoiceUserBillingOrCreate($invoice->Id, $isReadOnlyDb);
        $firstName = $invoiceUserBilling->FirstName;
        $lastName = $invoiceUserBilling->LastName;
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $isReadOnlyDb);
        $ccExpDate = $userBilling->CcExpDate;
        $ccType = (int)$userBilling->CcType;
        $billingPhone = $invoiceUserBilling->Phone;
        $billingCountry = $invoiceUserBilling->Country;
        $billingAddress = $invoiceUserBilling->Address;
        $billingCity = $invoiceUserBilling->City;
        $billingState = $invoiceUserBilling->State;
        $billingZip = $invoiceUserBilling->Zip;
        $billingFax = $invoiceUserBilling->Fax;

        $user = $dataProvider->loadUser($invoice->BidderId, $isReadOnlyDb);
        if (!$user) {
            return $result->addError(Result::ERR_USER_NOT_FOUND);
        }
        $customerNo = $user->CustomerNo;

        $authNetManager = $dataProvider->createAuthNetManager($invoice->AccountId);
        $authNetManager->setTransactionType($this->cfg()->get('core->billing->gate->authorizeNet->type'));
        $authNetManager->setParameter('x_description', $description);
        $authNetManager->setParameter('x_first_name', $firstName);
        $authNetManager->setParameter('x_last_name', $lastName);
        $authNetManager->setParameter('x_invoice_num', $invoice->InvoiceNo);
        if ($ccCode !== '') {
            $authNetManager->setParameter('x_card_code', $ccCode);
        }
        if ($ccType) {
            $authNetManager->setParameter('x_card_type', $ccType);
        }

        $chargingHelper = $this->createAdminStackedTaxInvoicePaymentChargingHelper();
        $accountingEmail = $chargingHelper->getAccountingEmail($user->Id, $invoice->Id);
        $authNetManager->setParameter('x_address', $billingAddress);
        $authNetManager->setParameter('x_city', $billingCity);
        $authNetManager->setParameter('x_state', $billingState);
        $authNetManager->setParameter('x_zip', $billingZip);
        $authNetManager->setParameter('x_country', $billingCountry);
        $authNetManager->setParameter('x_phone', $billingPhone);
        $authNetManager->setParameter('x_fax', $billingFax);
        $authNetManager->setParameter('x_email', $accountingEmail);
        $authNetManager->setParameter('x_cust_id', $customerNo);
        $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
        $authNetManager->transaction($ccNumber, $ccExpDate, $amount);
        log_debug(
            composeSuffix(
                [
                    'CC number' => substr($ccNumber, -4),
                    'Expiration' => $ccExpDate,
                    'Amount' => $amount,
                ]
            )
        );
        $authNetManager->process(2);

        $errorMessage = '';
        if (
            !$authNetManager->isError()
            || $authNetManager->isTransactionInReview()
        ) {
            if ($authNetManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= 'Code : ' . $authNetManager->getResponseCode() . ' ' .
                    $authNetManager->getResponseText() . '<br />';
                $errorMessage .= ($authNetManager->getCardCodeResponse() !== '')
                    ? 'Credit Card :' . $authNetManager->getCardCodeResponse() . ' <br />' : '';
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= 'Code : ' . $authNetManager->getResponseCode() . ' ' .
                $authNetManager->getResponseText() . '<br />';
            $errorMessage .= ($authNetManager->getCardCodeResponse() !== '')
                ? 'Credit Card :' . $authNetManager->getCardCodeResponse() . ' <br />' : '';
        }

        if ($errorMessage !== '') {
            log_debug(composeLogData(['Error charging invoice' => $errorMessage]));
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        if ($authNetManager->isTransactionInReview()) {
            $noteInfo = [
                'Trans.ID' => $authNetManager->getTransactionId(),
                'Status' => 'Payment was marked as "Held for Review"',
                'CC' => substr($ccNumber, -4)
            ];
            return Result::new()
                ->construct()
                ->setNoteInfo($noteInfo)
                ->addSuccess(Result::OK_HELD_FOR_REVIEW);
        }

        $noteInfo = [
            'Trans.ID' => $authNetManager->getTransactionId(),
            'CC' => substr($ccNumber, -4)
        ];
        return Result::new()
            ->construct()
            ->setNoteInfo($noteInfo)
            ->addSuccess(Result::OK_CHARGED);
    }
}
