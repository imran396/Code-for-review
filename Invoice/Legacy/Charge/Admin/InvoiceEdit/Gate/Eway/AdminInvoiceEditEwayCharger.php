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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway;

use Email_Template;
use Eway\Rapid\Enum\PaymentMethod;
use Eway\Rapid\Enum\TransactionType;
use Payment_Eway;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common\AdminInvoiceEditChargingHelperCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway\AdminInvoiceEditEwayChargingInput as Input;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway\AdminInvoiceEditEwayChargingResult as Result;

/**
 * Class InvoiceEditEwayCharger
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway
 */
class AdminInvoiceEditEwayCharger extends CustomizableClass
{
    use AdminInvoiceEditChargingHelperCreateTrait;
    use AnyInvoiceCalculatorCreateTrait;
    use BlockCipherProviderCreateTrait;
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
        $firstName = trim($input->firstName);
        $lastName = trim($input->lastName);
        $expMonth = $input->expMonth;
        $expYear = $input->expYear;
        $ccType = $input->ccType;
        $ccNumber = trim($input->ccNumber);
        $ccCode = trim($input->ccCode);
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $bPhone = $invoiceUserBilling->Phone;
        $bCountry = $invoiceUserBilling->Country;
        $bAddress = $invoiceUserBilling->Address;
        $bCity = $invoiceUserBilling->City;
        $bState = $invoiceUserBilling->State;
        $bZip = $invoiceUserBilling->Zip;
        $bFax = $invoiceUserBilling->Fax;
        $custNo = $user->CustomerNo;

        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            log_debug(composeLogData(['Other CC Number' => substr($ccNumber, -4)]));
        } else {
            log_debug(composeLogData(['Profile CC Number' => substr($ccNumber, -4)]));
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
                $input->ewayCardnumber
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

        $this->getDb()->TransactionBegin();
        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $ccType,
            $amount,
            $invoice,
            $editorUserId
        );

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
                $userBilling = $this->getUserLoader()->loadUserBilling($user->Id);
                $cardNumber = $userBilling->CcNumberEway ?? '';
            } else {
                $cardNumber = $input->ewayCardnumber;
            }
            $cardCvn = $input->ewayCardcvn;
        } else {
            $cardNumber = $ccNumber;
            $cardCvn = $ccCode;
        }
        $ewayManager->setMethod(PaymentMethod::PROCESS_PAYMENT);
        $ewayManager->transaction($cardNumber, $expMonth, $expYear, $amount, $cardCvn);
        $logData = [
            'CC number' => substr($ccNumber, -4),
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
            $this->getDb()->TransactionRollback();
            return $result->addError(Result::ERR_CHARGE, $errorMessage);
        }

        if ($input->onlyCharge) {
            return $result->addSuccess(Result::OK_CHARGED);
        }

        $note = composeLogData(['Trans.' => $ewayManager->getTransactionId(), 'CC' => substr($ccNumber, -4)]);

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
