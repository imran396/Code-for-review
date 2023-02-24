<?php
/**
 * SAM-10978: Stacked Tax. Public My Invoice pages. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge;

use Email_Template;
use Eway\Rapid\Enum\PaymentMethod;
use Eway\Rapid\Enum\TransactionType;
use Invoice;
use Payment_Eway;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge\ResponsiveInvoiceViewEwayChargingInput as Input;
use Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge\ResponsiveInvoiceViewEwayChargingResult as Result;
use Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount\InvoiceChargeAmountDetailsFactoryCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Produce\PaymentInvoiceAdditionalProducerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class PublicInvoiceViewEwayCharger
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge
 */
class ResponsiveInvoiceViewEwayCharger extends CustomizableClass
{
    use CcExpiryDateBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DataProviderCreateTrait;
    use InvoiceChargeAmountDetailsFactoryCreateTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use PaymentInvoiceAdditionalProducerCreateTrait;
    use TranslatorAwareTrait;

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

        $dataProvider = $this->createDataProvider();
        $isFound = $dataProvider->existUser($invoice->BidderId, $input->isReadOnlyDb);
        if (!$isFound) {
            log_error(
                "Available invoice winning user not found, when charge payment through Eway"
                . composeSuffix(['u' => $invoice->BidderId, 'i' => $invoice->Id])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $amountDetails = $this->createInvoiceChargeAmountDetailsFactory()->create(
            invoice: $invoice,
            paymentMethod: $input->paymentMethodId,
            creditCardType: $userBilling->CcType,
            netAmount: $input->balanceDue
        );
        // CC owner name must be the same format appears on credit card
        $name = UserPureRenderer::new()->makeFullName($userBilling->FirstName, $userBilling->LastName);

        $eway = Payment_Eway::new()->init($invoice->AccountId);

        if ($input->paymentMethodId === Constants\Payment::PM_CC) { // Cc payment
            $user = $dataProvider->loadUser($invoice->BidderId);

            $params = [
                'Customer' => [
                    'FirstName' => $userBilling->FirstName,
                    'LastName' => $userBilling->LastName,
                    'Street1' => $userBilling->Address,
                    'City' => $userBilling->City,
                    'PostalCode' => $userBilling->Zip,
                    'Phone' => $userBilling->Phone,
                    'Fax' => $userBilling->Fax,
                    'CardDetails' => ['Name' => $name],
                    'State' => $userBilling->State,
                    'Country' => $userBilling->Country,
                    'Email' => $user->Email ?? '',
                ],
                'Payment' => [
                    'InvoiceNumber' => $invoice->InvoiceNo,
                    'InvoiceDescription' => $this->generateDescription($invoice),
                ]
            ];
            $eway->setParameter($params);
            $eway->setMethod(PaymentMethod::PROCESS_PAYMENT);
            $eway->setTransactionType(TransactionType::PURCHASE);

            if ($dataProvider->getEwayEncryptionKey($invoice->AccountId)) {
                $ccNumber = $userBilling->CcNumberEway;
            } else {
                $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
            }

            [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);

            $eway->transaction(
                $ccNumber,
                $ccExpMonth,
                $ccExpYear,
                $amountDetails->paymentAmount,
                $input->ccCode
            );
            $eway->process();
        } else {
            log_debug('No payment method click');
            $result->addError(Result::ERR_PAYMENT_METHOD_NOT_MATCHED);
            return $result;
        }

        $errorMessage = '';
        if (!$eway->isError()) {
            if ($eway->isDeclined()) {
                $errorMessage .= 'Credit Card Declined. <br />';
                $errorMessage .= $eway->getResultResponse() . ':' . $eway->getResponseText();
            }
        } else {
            $errorMessage .= 'Problem encountered in your credit card validation. <br />';
            $errorMessage .= $eway->getResultResponse() . ':' . $eway->getResponseText();
        }

        if ($errorMessage !== '') {
            log_warning($errorMessage);
            $result->addError(Result::ERR_BIDDER_CARD, $errorMessage);
            return $result;
        }

        log_info(
            'Payment on invoice was successful'
            . composeSuffix(['i' => $invoice->Id, 'invoice#' => $invoice->InvoiceNo])
        );

        $txnId = $eway->getTransactionId();
        $note = composeSuffix(['Trans.' => $txnId, 'CC' => substr($ccNumber, -4)]);
        log_info('Eway transaction id return' . $note);

        $payment = $this->getInvoicePaymentManager()->add(
            $input->invoice->Id,
            $input->paymentMethodId,
            $amountDetails->paymentAmount,
            $input->editorUserId,
            $note,
            $this->getCurrentDateUtc(),
            Cast::toString($txnId),
            $userBilling->CcType
        );

        if ($amountDetails->surcharge) {
            $this->createPaymentInvoiceAdditionalProducer()->add(
                paymentCalculationResult: $amountDetails->surcharge,
                invoiceId: $invoice->Id,
                paymentId: $payment->Id,
                accountId: $invoice->AccountId,
                editorUserId: $input->editorUserId
            );
        }

        log_debug('Saving invoice info');
        $invoice->toPaid();
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);
        $invoice->Reload();

        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $input->editorUserId,
            [$invoice]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        $result->setAmount($amountDetails->paymentAmount);
        return $result;
    }

    protected function generateDescription(Invoice $invoice): string
    {
        $description = sprintf(
            $this->getTranslator()->translate("GENERAL_PAYMENT_ON_INVOICE", "general"),
            $invoice->InvoiceNo
        );
        $invoicedAuctionItemDtos = $this->createDataProvider()->loadInvoicedAuctionDtos($invoice->Id);
        if ($invoicedAuctionItemDtos) {
            $salesNoList = [];
            foreach ($invoicedAuctionItemDtos as $invoicedAuctionItemDto) {
                $salesNoList[] = $invoicedAuctionItemDto->makeSaleNo();
            }
            $description .= ' Sales(' . implode(',', $salesNoList) . ')';
        }
        return $description;
    }
}
