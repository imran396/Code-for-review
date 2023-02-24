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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\PayTrace;

use Email_Template;
use Invoice;
use Payment_PayTrace;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common\AdminInvoiceEditChargingHelperCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\PayTrace\AdminInvoiceEditPayTraceChargingResult as Result;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\PayTrace\AdminInvoiceEditPayTraceChargingInput as Input;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class AdminInvoiceEditPayTraceCharger
 * @package
 */
class AdminInvoiceEditPayTraceCharger extends CustomizableClass
{
    use AdminInvoiceEditChargingHelperCreateTrait;
    use AdminTranslatorAwareTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
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
        $amount = Floating::roundOutput($amount);
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
            $bCountry = $input->country;
            $bAddress = trim($input->address1);
            $bCity = trim($input->city);
            $bState = $input->state;
            $bZip = trim($input->zip);
            $firstName = trim($input->firstName);
            $lastName = trim($input->lastName);
            $expMonth = $input->expMonth;
            $expYear = $input->expYear;
            $ccType = $input->ccType;
            $ccNumber = trim($input->ccNumber);
            $ccCode = trim($input->ccCode);

            log_debug(composeLogData(['Other CC Number' => substr($ccNumber, -4)]));

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
            log_debug(composeLogData(['Profile CC Number' => substr($ccNumber, -4)]));
        }

        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $ccType,
            $amount,
            $invoice,
            $input->editorUserId
        );

        $payTraceManager = new Payment_PayTrace($invoice->AccountId);
        if (
            $isCim
            && $input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE
        ) {
            log_debug('Charging invoice using cim' . composeSuffix(['pt' => $payTraceCustId]));
            $payTraceManager->setParameter('CUSTID', $payTraceCustId);
            $payTraceManager->setParameter('AMOUNT', sprintf("%01.2F", $amount));
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
                        'CC number' => substr($ccNumber, -4),
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
            $this->getDb()->TransactionRollback();
            return $result->addError(Result::ERR_BIDDER_CARD);
        }

        if ($input->onlyCharge) {
            return $result->addSuccess(Result::OK_CHARGED);
        }

        // PayTrace always return transaction id even in test mode
        $note = 'Trans.:' . $payTraceManager->getTransactionId() . ' CC:' . substr($ccNumber, -4);

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
        $balanceDue = $this->calcRoundedBalanceDue($invoice);
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

    protected function calcRoundedBalanceDue(Invoice $invoice): float
    {
        return $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);
    }
}
