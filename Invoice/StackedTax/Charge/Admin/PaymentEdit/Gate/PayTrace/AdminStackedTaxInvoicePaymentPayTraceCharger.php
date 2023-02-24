<?php
/**
 * <Description of class>
 *
 * SAM-10918: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract PayTrace invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace;

use Invoice;
use Payment_PayTrace;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace\AdminStackedTaxInvoicePaymentPayTraceChargingResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace\AdminStackedTaxInvoicePaymentPayTraceChargingInput as Input;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class AdminInvoiceEditPayTraceCharger
 * @package
 */
class AdminStackedTaxInvoicePaymentPayTraceCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use AdminTranslatorAwareTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CreditCardValidatorAwareTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use NumberFormatterAwareTrait;
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
        $isCim = false;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $user = $this->getUserLoader()->load($invoice->BidderId);
        if (!$user) {
            log_error(
                "Unable to charge invoice of deleted winning bidder"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }
        $amount = $nf->parseMoney($input->amountFormatted);
        $amount = sprintf("%1.2F", Floating::roundOutput($amount));
        log_trace(composeLogData(['Charge amount' => $amount]));
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($user->Id);
        // make description
        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        if ($input->invoicedAuctionDtos) {
            $saleNos = [];
            foreach ($input->invoicedAuctionDtos as $invoicedAuctionDto) {
                $saleNos[] = $invoicedAuctionDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(', ', $saleNos) . ')';
        }
        $firstName = $lastName = $expMonth = $expYear = $ccNumber = $ccCode = $bAddress
            = $bCity = $bState = $bZip = $bCountry = '';
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $payTraceCustId = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->PayTraceCustId);
        $editorUserId = $input->editorUserId;
        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
            $bCountry = $invoiceUserBilling->Country;
            $bAddress = $invoiceUserBilling->Address;
            $bCity = $invoiceUserBilling->City;
            $bState = $invoiceUserBilling->State;
            $bZip = $invoiceUserBilling->Zip;

            $firstName = trim($input->firstName);
            $lastName = trim($input->lastName);
            $expMonth = $input->expMonth;
            $expYear = $input->expYear;
            $ccType = $input->ccType;
            $ccNumber = trim($input->ccNumber);
            $ccCode = trim($input->ccCode);

            log_debug(composeLogData(['Other CC Number' => $this->cutCcLast4($ccNumber)]));

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
        } else {
            $billingChecker = $this->createBillingGateAvailabilityChecker();
            $isCim = $billingChecker->isPayTraceCim($invoice->AccountId);
            $ccType = (int)$userBilling->CcType;
            if ($isCim) {
                $cimProfile = $payTraceCustId !== '';
                if (!$cimProfile) { // No Customer Profile Yet
                    if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                        return $result->addError(Result::ERR_CARD_INFO_NOT_FOUND);
                    }
                    $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
                    if (!$this->getCreditCardValidator()->validateNumber($ccNumber)) { // Check if a valid cc
                        $creditCardError = $this->getAdminTranslator()->trans('billing.cc_charge.error.invalid_credit_card_number', [], 'admin_invoice_item');
                        return $result->addError(Result::ERR_INVALID_CREDIT_CARD, $creditCardError);
                    }
                    [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

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
            } else {
                if (!$this->getUserBillingChecker()->isCcInfoExists($userBilling)) {
                    return $result->addError(Result::ERR_CARD_INFO_NOT_FOUND);
                }

                $firstName = $invoiceUserBilling->FirstName;
                $lastName = $invoiceUserBilling->LastName;
                [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
                $bCountry = $invoiceUserBilling->Country;
                $bAddress = $invoiceUserBilling->Address;
                $bCity = $invoiceUserBilling->City;
                $bState = $invoiceUserBilling->State;
                $bZip = $invoiceUserBilling->Zip;
            }
            log_debug(composeLogData(['Profile CC Number' => $this->cutCcLast4($ccNumber)]));
        }

        $amount = sprintf("%01.2F", $amount);

        $payTraceManager = new Payment_PayTrace($invoice->AccountId);
        if (
            $isCim
            && $input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE
        ) {
            log_debug('Charging invoice using cim' . composeSuffix(['pt' => $payTraceCustId]));
            $payTraceManager->setParameter('CUSTID', $payTraceCustId);
            $payTraceManager->setParameter('AMOUNT', $amount);
        } else {
            log_debug('Charging invoice using cc info');
            $payTraceManager->setParameter('BADDRESS', $bAddress);
            $payTraceManager->setParameter('BCITY', $bCity);
            if (strlen($bState) === 2) {
                $payTraceManager->setParameter('BSTATE', $bState);
            }
            $payTraceManager->setParameter('BZIP', $bZip);
            if (strlen($bCountry) === 2) {
                $payTraceManager->setParameter('BCOUNTRY', $bCountry);
            }
            //$payTraceManager->setParameter('PHONE', $bPhone); Exclude phone/fax because it has different formatting
            //$payTraceManager->setParameter('FAX', $bFax);
            $fullName = UserPureRenderer::new()->makeFullName($firstName, $lastName);
            $payTraceManager->setParameter('BNAME', $fullName);
            if ($ccCode !== '') {
                $payTraceManager->setParameter('CSC', $ccCode);
            }
            $payTraceManager->transaction($ccNumber, $expMonth, $expYear, $amount);
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
        $payTraceManager->setMethod('ProcessTranx');
        $payTraceManager->setTransactionType('Sale');
        $payTraceManager->setParameter('DESCRIPTION', $description);
        $payTraceManager->setParameter('INVOICE', $invoice->InvoiceNo);
        $email = $this->getUserRenderer()->renderAccountingEmail($user, null, $invoiceUserBilling);
        $payTraceManager->setParameter('EMAIL', $email);
        $payTraceManager->process(2);

        $errorMessage = '';
        if (!$payTraceManager->isError()) {
            if ($payTraceManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $payTraceManager->getErrorReport();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $payTraceManager->getErrorReport();
        }

        if ($errorMessage !== '') {
            log_debug(composeLogData(['Error charging invoice' => $errorMessage]));
            return $result->addError(Result::ERR_BIDDER_CARD);
        }

        // PayTrace always return transaction id even in test mode
        $transactionId = $payTraceManager->getTransactionId();
        return $result
            ->setNoteInfo($transactionId, $this->cutCcLast4($ccNumber))
            ->addSuccess(Result::OK_CHARGED);
    }

    protected function calcRoundedBalanceDue(Invoice $invoice): float
    {
        return $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);
    }

    protected function cutCcLast4(string $ccNumber): string
    {
        return substr($ccNumber, -4);
    }
}
