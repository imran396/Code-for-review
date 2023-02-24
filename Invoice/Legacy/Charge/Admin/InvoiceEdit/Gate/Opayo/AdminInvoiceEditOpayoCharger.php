<?php
/**
 * SAM-10913: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Opayo invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo;

use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\Payment\OpayoGateManagerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common\AdminInvoiceEditChargingHelperCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\AdminInvoiceEditOpayoChargingInput as Input;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\AdminInvoiceEditOpayoChargingResult as Result;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Calculate\InvoiceCalculatorCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Notify\NotifierCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Update\AdminInvoiceEditOpayoCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

class AdminInvoiceEditOpayoCharger extends CustomizableClass
{
    use AdminInvoiceEditChargingHelperCreateTrait;
    use AdminInvoiceEditOpayoCcInfoUpdaterCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use DbConnectionTrait;
    use InvoiceCalculatorCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use NotifierCreateTrait;
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

        $this->getDb()->TransactionBegin();

        $amount = $this->getAmount($input);
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

        $chargingHelper = $this->createAdminInvoiceEditChargingHelper();
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
            $opayoManager->setParameter('Amount', sprintf("%01.2F", $amount));
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
                        'CC number' => substr($ccNumber, -4),
                        'Expiration' => $expMonth . ' -' . $expYear,
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
            $this->getDb()->TransactionRollback();
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        // Opayo always return transaction id even in test mode
        $note = 'Trans.:' . $opayoManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

        $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC,
            $amount,
            $editorUserId,
            $note,
            $this->getCurrentDateUtc()
        );

        $balanceDue = $dataProvider->calculateBalanceDue($invoice);
        log_trace(composeLogData(['Balance due' => $balanceDue]));
        if (Floating::lteq($balanceDue, 0.)) {
            $invoice->toPaid();
        }
        $invoice = $dataProvider->calculateAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $this->getDb()->TransactionCommit();
        // Email customer as a proof of their payment
        $this->createNotifier()->addEmailToActionQueue($invoice, $editorUserId);
        return $result;
    }

    private function getAmount(Input $input): float
    {
        $invoice = $input->invoice;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $amount = $nf->parse($input->amountFormatted, 2, $invoice->AccountId);
        $amount = Floating::roundOutput($amount);
        $amount = $this->createInvoiceCalculator()->addCcSurchargeToTheAmount(
            $invoice,
            $input->ccType,
            $amount,
            $input->editorUserId
        );
        return $amount;
    }

    private function getInputValuesForChargeOtherCc(Input $input): array
    {
        log_debug(composeLogData(['Other CC Number' => substr(trim($input->ccNumber), -4)]));
        return [
            trim($input->firstName),
            trim($input->lastName),
            trim($input->phone),
            trim($input->address),
            trim($input->country),
            trim($input->state),
            trim($input->city),
            trim($input->zip),
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

        log_debug(composeLogData(['Profile CC Number' => substr($ccNumber, -4)]));
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
}
