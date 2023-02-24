<?php
/**
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo;

use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\Payment\OpayoGateManagerCreateTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\AdminStackedTaxInvoicePaymentOpayoChargingInput as Input;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\AdminStackedTaxInvoicePaymentOpayoChargingResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Update\AdminInvoiceEditOpayoCcInfoUpdaterCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

class AdminStackedTaxInvoicePaymentOpayoCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use AdminInvoiceEditOpayoCcInfoUpdaterCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;
    use OpayoGateManagerCreateTrait;

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
        log_trace(composeLogData(['Charge amount' => $input->amountFormatted]));
        $dataProvider = $this->createDataProvider();
        $result = Result::new()->construct();

        $chargeOtherCc = ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC);
        $invoice = $input->invoice;

        $isFound = $dataProvider->existUser($invoice->BidderId);
        if (!$isFound) {
            log_error(
                "Unable to charge invoice of deleted winning bidder"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId);
        [
            $firstName,
            $lastName,
            $phone,
            $address,
            $country,
            $state,
            $city,
            $zip,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
        ] = $chargeOtherCc
            ? $this->getInputValuesForChargeOtherCc($input)
            : $this->getInputValuesForChargeCcOnProfile($input);
        $ccCode = trim($input->ccCode);
        $editorUserId = $input->editorUserId;

        if (!$chargeOtherCc
            && !$dataProvider->isCcInfoExists($userBilling)
        ) {
            return $result->addError(Result::ERR_NO_CC_INFO);
        }

        $isOpayoToken = $dataProvider->isOpayoToken($invoice->AccountId);
        $hasOpayoCustomerProfile = $dataProvider->decryptValue($userBilling->OpayoTokenId) !== '';

        $ccInfoUpdateResult = $this->createAdminInvoiceEditOpayoCcInfoUpdater()->update(
            $invoice->BidderId,
            $invoice->Id,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $ccCode,
            $input->isReplaceOldCard,
            $input->chargeOption,
            $editorUserId,
            $hasOpayoCustomerProfile,
            $isOpayoToken
        );

        if ($ccInfoUpdateResult->hasError()) {
            return $result->addError(Result::ERR_UPDATE_CC_INFO, $ccInfoUpdateResult->errorMessage());
        }

        $amount = $this->getNumberFormatter()->parse($input->amountFormatted, $invoice->AccountId);
        $opayoManager = $this->createOpayoGateManager()->init($invoice->AccountId)
            ->disableCVVCheck()
            ->disableThreeDSecure();
        $opayoManager->setTransactionType('PAYMENT');
        $opayoManager->setParameter('BillingFirstnames', $firstName);
        $opayoManager->setParameter('BillingSurname', $lastName);
        $opayoManager->setParameter('BillingCountry', $country);
        $opayoManager->setParameter('BillingAddress1', $address);
        $opayoManager->setParameter('BillingCity', $city);
        if (strlen($state) === 2) {
            $opayoManager->setParameter('BillingState', $state);
        }
        $opayoManager->setParameter('BillingPostCode', $zip);
        $opayoManager->setParameter('BillingPhone', $phone);
        $opayoManager->setParameter('BillingFirstnames', $firstName);
        $opayoManager->setParameter('BillingSurname', $lastName);

        $chargingHelper = $this->createAdminStackedTaxInvoicePaymentChargingHelper();
        $accountingEmail = $chargingHelper->getAccountingEmail($invoice->BidderId, $invoice->Id);
        $opayoManager->setParameter('CustomerEMail', $accountingEmail);

        if (
            !$chargeOtherCc
            && $isOpayoToken
        ) {
            // Use Opayo token
            $opayoTokenId = $dataProvider->decryptValue($userBilling->OpayoTokenId);
            $opayoManager->setParameter('VendorTxCode', $opayoManager->vendorTxCode());
            log_debug('Charging invoice using cim' . composeSuffix(['st' => $opayoTokenId]));
            $opayoManager->setParameter('Token', $opayoTokenId);
            $opayoManager->setParameter('StoreToken', 1);
            $opayoManager->setParameter('Amount', $amount);
            $opayoManager->setParameter('CV2', $ccCode);
        } else {
            log_debug('Charging invoice using cc info');
            $billingFullName = $dataProvider->makeFullName($firstName, $lastName);
            $opayoManager->setParameter('CustomerName', $billingFullName);
            $opayoManager->setParameter('CardHolder', $billingFullName);
            $creditCard = $dataProvider->loadCreditCard($ccType);
            if ($creditCard) {
                $cardType = $opayoManager->getCardType($creditCard->Name);
                $opayoManager->setParameter('CardType', $cardType);
            }
            $opayoManager->transaction(
                $ccNumber,
                $expMonth,
                $expYear,
                $amount,
                $ccCode
            );
            log_debug(
                composeSuffix(
                    [
                        'CC number' => $this->cutCcLast4($ccNumber),
                        'Expiration' => $expMonth . '-' . $expYear,
                        'Amount' => $amount,
                    ]
                )
            );
        }

        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        $opayoManager->setParameter('Description', $description);
        $opayoManager->process(2);

        $errorMessage = '';
        if (
            $opayoManager->isError()
            || $opayoManager->isDeclined()
        ) {
            if ($opayoManager->isError()) {
                $errorMessage .= 'Problem encountered in your credit card validation. <br />';
                $errorMessage .= $opayoManager->getErrorReport();
            } elseif ($opayoManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $opayoManager->getErrorReport();
            }
            log_debug(composeLogData(['Error charging invoice' => $errorMessage]));
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        return $result
            ->setNoteInfo($opayoManager->getTransactionId(), $this->cutCcLast4($ccNumber));
    }

    private function getInputValuesForChargeOtherCc(Input $input): array
    {
        log_debug(composeLogData(['Other CC Number' => substr(trim($input->ccNumber), -4)]));
        $invoiceUserBilling = $this->createDataProvider()->loadInvoiceBillingOrCreate($input->invoice->Id);
        return [
            trim($input->firstName),
            trim($input->lastName),
            $invoiceUserBilling->Phone,
            $invoiceUserBilling->Address,
            $invoiceUserBilling->Country,
            $invoiceUserBilling->State,
            $invoiceUserBilling->City,
            $invoiceUserBilling->Zip,
            trim($input->ccNumber),
            $input->ccType,
            $input->expMonth,
            $input->expYear,
        ];
    }

    protected function getInputValuesForChargeCcOnProfile(Input $input): array
    {
        $dataProvider = $this->createDataProvider();
        $userBilling = $dataProvider->loadUserBillingOrCreate($input->invoice->BidderId);
        $invoiceUserBilling = $dataProvider->loadInvoiceBillingOrCreate($input->invoice->Id);
        $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
        [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

        log_debug(composeLogData(['Profile CC Number' => $this->cutCcLast4($ccNumber)]));
        return [
            $invoiceUserBilling->FirstName,
            $invoiceUserBilling->LastName,
            $invoiceUserBilling->Phone,
            $invoiceUserBilling->Address,
            $invoiceUserBilling->Country,
            $invoiceUserBilling->State,
            $invoiceUserBilling->City,
            $invoiceUserBilling->Zip,
            $ccNumber,
            (int)$userBilling->CcType,
            $expMonth,
            $expYear
        ];
    }

    protected function cutCcLast4(string $ccNumber): string
    {
        return substr($ccNumber, -4);
    }
}
