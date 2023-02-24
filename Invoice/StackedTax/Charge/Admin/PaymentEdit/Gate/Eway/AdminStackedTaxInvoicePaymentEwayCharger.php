<?php
/**
 * SAM-10912: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway;

use Eway\Rapid\Enum\PaymentMethod;
use Eway\Rapid\Enum\TransactionType;
use Payment_Eway;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway\AdminStackedTaxInvoicePaymentEwayChargingInput as Input;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway\AdminStackedTaxInvoicePaymentEwayChargingResult as Result;

/**
 * @package Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway
 */
class AdminStackedTaxInvoicePaymentEwayCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
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

    /**
     * @param Input $input
     * @return Result
     */
    public function charge(Input $input): Result
    {
        $result = Result::new()->construct();

        $invoice = $input->invoice;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $user = $this->getUserLoader()->load($invoice->BidderId);
        if (!$user) {
            log_error(
                "Unable to charge invoice of deleted winning bidder"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $editorUserId = $input->editorUserId;
        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        if ($input->invoicedAuctionDtos) {
            $saleNos = [];
            foreach ($input->invoicedAuctionDtos as $invoicedAuctionDto) {
                $saleNos[] = $invoicedAuctionDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(', ', $saleNos) . ')';
        }
        $amount = $nf->parseMoney($input->amountFormatted);
        log_trace(composeLogData(['Charge amount' => $amount]));
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $bPhone = $invoiceUserBilling->Phone;
        $bCountry = $invoiceUserBilling->Country;
        $bAddress = $invoiceUserBilling->Address;
        $bCity = $invoiceUserBilling->City;
        $bState = $invoiceUserBilling->State;
        $bZip = $invoiceUserBilling->Zip;
        $bFax = $invoiceUserBilling->Fax;
        $custNo = $user->CustomerNo;
        $userBilling = $this->getUserLoader()->loadUserBilling($user->Id);
        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            $firstName = trim($input->firstName);
            $lastName = trim($input->lastName);
            $expMonth = $input->expMonth;
            $expYear = $input->expYear;
            $ccType = $input->ccType;
            $ccNumber = trim($input->ccNumber);
            $ccCode = trim($input->ccCode);
        } else {
            $firstName = $invoiceUserBilling->FirstName;
            $lastName = $invoiceUserBilling->LastName;
            $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
            [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
        }

        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            log_debug(composeLogData(['Other CC Number' => $this->cutCcLast4($ccNumber)]));
        } else {
            log_debug(composeLogData(['Profile CC Number' => $this->cutCcLast4($ccNumber)]));
        }

        if ($input->isReplaceOldCard) {
            $this->createInvoiceUserCcInfoUpdater()->update(
                $invoice->AccountId,
                $editorUserId,
                $user->Id,
                $input->ccNumber,
                $input->ccType,
                $input->expYear,
                $input->expMonth,
                $input->ewayCardNumber
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

        $ewayManager = Payment_Eway::new()->init($invoice->AccountId);
        $params = [];
        $fullName = UserPureRenderer::new()->makeFullName($firstName, $lastName);
        $params["Customer"]["CardDetails"]["Name"] = $fullName;
        $params["Customer"]["Reference"] = $custNo;
        $params["Customer"]["FirstName"] = $firstName;
        $params["Customer"]["LastName"] = $lastName;
        $params["Customer"]["Street1"] = $bAddress;
        $params["Customer"]["City"] = $bCity;
        $params["Customer"]["State"] = $bState;
        $params["Customer"]["PostalCode"] = $bZip;
        $params["Customer"]["Country"] = $bCountry;
        $params["Customer"]["Phone"] = $bPhone;
        $params["Customer"]["Fax"] = $bFax;

        $ewayEncryptionKeyEncrypted = $this->getSettingsManager()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $invoice->AccountId);
        $ewayEncryptionKey = $this->createBlockCipherProvider()->construct()->decrypt($ewayEncryptionKeyEncrypted);

        if (
            $this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_EWAY, $invoice->AccountId)
            && $ewayEncryptionKey !== ''
        ) {
            if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE) {
                $cardNumber = $userBilling->CcNumberEway ?? '';
            } else {
                $cardNumber = $input->ewayCardNumber;
            }
            $cardCvn = $input->ewayCardcvn;
        } else {
            $cardNumber = $ccNumber;
            $cardCvn = $ccCode;
        }
        $ewayManager->setMethod(PaymentMethod::PROCESS_PAYMENT);
        $ewayManager->transaction($cardNumber, $expMonth, $expYear, $amount, $cardCvn);
        $logData = [
            'CC number' => $this->cutCcLast4($ccNumber),
            'Expiration' => "{$expMonth} - {$expYear}",
            'Amount' => $amount,
        ];
        log_debug("Starting charging invoice by Eway" . composeSuffix($logData));
        $params["Payment"]["InvoiceNumber"] = $invoice->InvoiceNo;
        $params["Payment"]["InvoiceDescription"] = $description;
        $params["Customer"]["Email"] = $this->getUserRenderer()
            ->renderAccountingEmail($user, null, $invoiceUserBilling);
        $ewayManager->setParameter($params);
        $ewayManager->setTransactionType(TransactionType::MOTO);
        $ewayManager->process();

        $errorMessage = '';
        if (!$ewayManager->isError()) {
            if ($ewayManager->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $ewayManager->getResultResponse() . ':' . $ewayManager->getResponseText();
        }

        if ($errorMessage !== '') {
            log_debug(composeLogData(['Error charging invoice' => $errorMessage]));
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        return $result
            ->setNoteInfo((string)$ewayManager->getTransactionId(), $this->cutCcLast4($ccNumber))
            ->addSuccess(Result::OK_CHARGED);
    }

    protected function cutCcLast4(string $ccNumber): string
    {
        return substr($ccNumber, -4);
    }
}
