<?php
/**
 * Moved from Invoice_Factory. Needs refactoring.
 * Currently, it is called from admin Invoice List action only.
 *
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * IK: Do we want DB transaction wrapping, roll-back on fails? (2022-10-25)
 * TB: For now don't complicate
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Charge;

use Invoice;
use RuntimeException;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Invoice\StackedTax\Calculate\StackedTaxInvoicePureCalculator;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\StackedTax\Calculate\Summary\StackedTaxInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\AdminStackedTaxInvoicePaymentAuthorizeNetChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\AdminStackedTaxInvoicePaymentAuthorizeNetChargingInput;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\Notify\AdminStackedTaxInvoiceChargingNotifierCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway\AdminStackedTaxInvoicePaymentEwayChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway\AdminStackedTaxInvoicePaymentEwayChargingInput;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi\AdminStackedTaxInvoicePaymentNmiChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi\AdminStackedTaxInvoicePaymentNmiChargingInput;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\AdminStackedTaxInvoicePaymentOpayoChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\AdminStackedTaxInvoicePaymentOpayoChargingInput;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace\AdminStackedTaxInvoicePaymentPayTraceChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace\AdminStackedTaxInvoicePaymentPayTraceChargingInput;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculatorCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class AdminStackedTaxInvoiceListCharger extends CustomizableClass
{
    use AdminStackedTaxInvoicePaymentAuthorizeNetChargerCreateTrait;
    use AdminStackedTaxInvoicePaymentEwayChargerCreateTrait;
    use AdminStackedTaxInvoicePaymentNmiChargerCreateTrait;
    use AdminStackedTaxInvoicePaymentPayTraceChargerCreateTrait;
    use AdminStackedTaxInvoicePaymentOpayoChargerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceUserLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentInvoiceAdditionalCalculatorCreateTrait;
    use AdminStackedTaxInvoiceChargingNotifierCreateTrait;
    use StackedTaxInvoiceSummaryCalculatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function chargeInvoiceThroughAuthorizeNet(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        // Amount is calculated with consideration of CC surcharge and zero tax as service fee
        $balanceDue = $invoice->calcRoundedBalanceDue();
        $userLoader = $this->getUserLoader();
        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);

        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            creditCardId: $userBilling->CcType,
            amount: $balanceDue,
            taxSchemaId: null,
            accountId: $invoice->AccountId
        );

        $chargeAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $balanceDue,
            $ccSurchargeResult?->amount,
            $ccSurchargeResult?->taxAmount
        );
        $chargeAmountFormatted = $this->getNumberFormatter()->formatMoney($chargeAmount, $invoice->AccountId);
        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'balance due' => $balanceDue,
            'cc surcharge' => $ccSurchargeResult?->amount,
            'cc surcharge tax' => $ccSurchargeResult?->taxAmount,
            'charge amount' => $chargeAmount,
        ];
        log_debug("Start charging invoice" . composeSuffix($logData));

        $input = AdminStackedTaxInvoicePaymentAuthorizeNetChargingInput::new()->constructForCcOnFile(
            $editorUserId,
            $invoice,
            $chargeAmountFormatted
        );
        $chargeResult = $this->createAdminStackedTaxInvoicePaymentAuthorizeNetCharger()->charge($input);
        if ($chargeResult->hasError()) {
            $errorMessage = $chargeResult->errorMessage();
            log_error($errorMessage);
            return $errorMessage;
        }

        /**
         * Create Payment record
         */
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC_ON_FILE,
            $chargeAmount,
            $editorUserId,
            composeLogData($chargeResult->noteInfo),
            $this->getCurrentDateUtc(),
            null,
            $userBilling->CcType
        );

        /**
         * Calculate and create CC Surcharge record (InvoiceAdditional)
         */
        if (
            $ccSurchargeResult
            && Floating::gt($ccSurchargeResult->amount, 0.)
        ) {
            $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurchargeResult->name,
                $ccSurchargeResult->amount,
                $editorUserId,
                composeLogData($chargeResult->noteInfo),
                null,
                null,
                null,
                $payment->Id,
            );
        }

        $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);
        /**
         * Mark invoice as paid, if balance due is zero and transaction was successfully completed and was not held for review
         */
        $newBalanceDue = $invoice->calcRoundedBalanceDue();
        if (
            !$chargeResult->isHeldForReview
            && Floating::lteq($newBalanceDue, 0.)
            && !$invoice->isShipped()
        ) {
            $invoice->toPaid();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'is' => $invoice->InvoiceStatusId,
            'New balance due' => $newBalanceDue,
            'pmnt' => $payment->Id,
            'iadd' => $invoiceAdditional->Id ?? null,
        ];

        log_trace("Invoice payment successful" . composeSuffix($logData));

        /**
         * Notify user about payment by e-mail
         */
        $this->createAdminStackedTaxInvoiceChargingNotifier()->notify($invoice, $editorUserId);

        return true;
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughPayTrace(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $balanceDue = $invoice->calcRoundedBalanceDue();
        $userLoader = $this->getUserLoader();
        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);

        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            creditCardId: $userBilling->CcType,
            amount: $balanceDue,
            taxSchemaId: null,
            accountId: $invoice->AccountId
        );

        $chargeAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $balanceDue,
            $ccSurchargeResult?->amount,
            $ccSurchargeResult?->taxAmount
        );
        $chargeAmountFormatted = $this->getNumberFormatter()->formatMoney($chargeAmount, $invoice->AccountId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'balance due' => $balanceDue,
            'cc surcharge' => $ccSurchargeResult?->amount,
            'cc surcharge tax' => $ccSurchargeResult?->taxAmount,
            'charge amount' => $chargeAmount,
        ];
        log_debug("Start charging invoice" . composeSuffix($logData));

        $invoicedAuctionItemDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id);
        $input = AdminStackedTaxInvoicePaymentPayTraceChargingInput::new()->constructForCcOnFile(
            $editorUserId,
            $invoice,
            $chargeAmountFormatted,
            $invoicedAuctionItemDtos
        );
        $chargeResult = $this->createAdminStackedTaxInvoicePaymentPayTraceCharger()->charge($input);

        if ($chargeResult->hasError()) {
            $errorMessage = $chargeResult->errorMessage();
            log_error($errorMessage);
            return $errorMessage;
        }

        /**
         * Create Payment record
         */
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC_ON_FILE,
            $chargeAmount,
            $editorUserId,
            composeLogData($chargeResult->noteInfo),
            $this->getCurrentDateUtc(),
            null,
            $userBilling->CcType
        );

        /**
         * Calculate and create CC Surcharge record (InvoiceAdditional)
         */
        if (
            $ccSurchargeResult
            && Floating::gt($ccSurchargeResult->amount, 0.)
        ) {
            $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurchargeResult->name,
                $ccSurchargeResult->amount,
                $editorUserId,
                composeLogData($chargeResult->noteInfo),
                null,
                null,
                null,
                $payment->Id,
            );
        }

        $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);
        /**
         * Mark invoice as paid, if balance due is zero and transaction was successfully completed and was not held for review
         */
        $newBalanceDue = $invoice->calcRoundedBalanceDue();
        if (
            Floating::lteq($newBalanceDue, 0.)
            && !$invoice->isShipped()
        ) {
            $invoice->toPaid();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'is' => $invoice->InvoiceStatusId,
            'New balance due' => $newBalanceDue,
            'pmnt' => $payment->Id,
            'iadd' => $invoiceAdditional->Id ?? null,
        ];
        log_trace("Invoice payment successful" . composeSuffix($logData));

        /**
         * Notify user about payment by e-mail
         */
        $this->createAdminStackedTaxInvoiceChargingNotifier()->notify($invoice, $editorUserId);

        return true;
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughEway(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $balanceDue = $invoice->calcRoundedBalanceDue();
        $userLoader = $this->getUserLoader();
        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);

        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            creditCardId: $userBilling->CcType,
            amount: $balanceDue,
            taxSchemaId: null,
            accountId: $invoice->AccountId
        );

        $chargeAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $balanceDue,
            $ccSurchargeResult?->amount,
            $ccSurchargeResult?->taxAmount
        );
        $chargeAmountFormatted = $this->getNumberFormatter()->formatMoney($chargeAmount, $invoice->AccountId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'balance due' => $balanceDue,
            'cc surcharge' => $ccSurchargeResult?->amount,
            'cc surcharge tax' => $ccSurchargeResult?->taxAmount,
            'charge amount' => $chargeAmount,
        ];
        log_debug("Start charging invoice" . composeSuffix($logData));

        $invoicedAuctionItemDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id);
        $input = AdminStackedTaxInvoicePaymentEwayChargingInput::new()->constructForCcOnFile(
            $editorUserId,
            $invoice,
            $chargeAmountFormatted,
            $invoicedAuctionItemDtos
        );
        $chargeResult = $this->createAdminStackedTaxInvoicePaymentEwayCharger()->charge($input);

        if ($chargeResult->hasError()) {
            $errorMessage = $chargeResult->errorMessage();
            log_error($errorMessage);
            return $errorMessage;
        }

        /**
         * Create Payment record
         */
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC_ON_FILE,
            $chargeAmount,
            $editorUserId,
            composeLogData($chargeResult->noteInfo),
            $this->getCurrentDateUtc(),
            null,
            $userBilling->CcType
        );

        /**
         * Calculate and create CC Surcharge record (InvoiceAdditional)
         */
        if (
            $ccSurchargeResult
            && Floating::gt($ccSurchargeResult->amount, 0.)
        ) {
            $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurchargeResult->name,
                $ccSurchargeResult->amount,
                $editorUserId,
                composeLogData($chargeResult->noteInfo),
                null,
                null,
                null,
                $payment->Id,
            );
        }

        $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);
        /**
         * Mark invoice as paid, if balance due is zero and transaction was successfully completed and was not held for review
         */
        $newBalanceDue = $invoice->calcRoundedBalanceDue();
        if (
            Floating::lteq($newBalanceDue, 0.)
            && !$invoice->isShipped()
        ) {
            $invoice->toPaid();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'is' => $invoice->InvoiceStatusId,
            'New balance due' => $newBalanceDue,
            'pmnt' => $payment->Id,
            'iadd' => $invoiceAdditional->Id ?? null,
        ];
        log_trace("Invoice payment successful" . composeSuffix($logData));

        /**
         * Notify user about payment by e-mail
         */
        $this->createAdminStackedTaxInvoiceChargingNotifier()->notify($invoice, $editorUserId);

        return true;
    }

    /**
     * @param int $invoiceId
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughNmi(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $balanceDue = $invoice->calcRoundedBalanceDue();
        $userLoader = $this->getUserLoader();
        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);

        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            creditCardId: $userBilling->CcType,
            amount: $balanceDue,
            taxSchemaId: null,
            accountId: $invoice->AccountId
        );

        $chargeAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $balanceDue,
            $ccSurchargeResult?->amount,
            $ccSurchargeResult?->taxAmount
        );
        $chargeAmountFormatted = $this->getNumberFormatter()->formatMoney($chargeAmount, $invoice->AccountId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'balance due' => $balanceDue,
            'cc surcharge' => $ccSurchargeResult?->amount,
            'cc surcharge tax' => $ccSurchargeResult?->taxAmount,
            'charge amount' => $chargeAmount,
        ];
        log_debug("Start charging invoice" . composeSuffix($logData));

        $input = AdminStackedTaxInvoicePaymentNmiChargingInput::new()->constructForCcOnFile(
            $editorUserId,
            $invoice,
            $chargeAmountFormatted
        );
        $chargeResult = $this->createAdminStackedTaxInvoiceEditNmiCharger()->charge($input);

        if ($chargeResult->hasError()) {
            $errorMessage = $chargeResult->errorMessage();
            log_error($errorMessage);
            return $errorMessage;
        }

        /**
         * Create Payment record
         */
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC_ON_FILE,
            $chargeAmount,
            $editorUserId,
            composeLogData($chargeResult->noteInfo),
            $this->getCurrentDateUtc(),
            null,
            $userBilling->CcType
        );

        /**
         * Calculate and create CC Surcharge record (InvoiceAdditional)
         */
        if (
            $ccSurchargeResult
            && Floating::gt($ccSurchargeResult->amount, 0.)
        ) {
            $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurchargeResult->name,
                $ccSurchargeResult->amount,
                $editorUserId,
                composeLogData($chargeResult->noteInfo),
                null,
                null,
                null,
                $payment->Id,
            );
        }

        $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);
        /**
         * Mark invoice as paid, if balance due is zero and transaction was successfully completed and was not held for review
         */
        $newBalanceDue = $invoice->calcRoundedBalanceDue();
        if (
            Floating::lteq($newBalanceDue, 0.)
            && !$invoice->isShipped()
        ) {
            $invoice->toPaid();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'is' => $invoice->InvoiceStatusId,
            'New balance due' => $newBalanceDue,
            'pmnt' => $payment->Id,
            'iadd' => $invoiceAdditional->Id ?? null,
        ];
        log_trace("Invoice payment successful" . composeSuffix($logData));

        /**
         * Notify user about payment by e-mail
         */
        $this->createAdminStackedTaxInvoiceChargingNotifier()->notify($invoice, $editorUserId);

        return true;
    }

    /**
     * Function that process payment of invoices
     * through Opayo API Gateway
     *
     * @param int $invoiceId (int)
     * @param int $editorUserId
     * @return bool|string
     */
    public function chargeInvoiceThroughOpayo(int $invoiceId, int $editorUserId): bool|string
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            $this->handleChargeErrorInvoiceNotFound($invoiceId);
        }

        $balanceDue = $invoice->calcRoundedBalanceDue();
        $userLoader = $this->getUserLoader();
        $userBilling = $userLoader->loadUserBillingOrCreate($invoice->BidderId);

        $user = $userLoader->load($invoice->BidderId);
        if (!$user) {
            $this->handleChargeErrorInvoiceWinnerNotFound($invoice->Id, $invoice->BidderId);
        }

        $ccSurchargeResult = $this->createPaymentInvoiceAdditionalCalculator()->calcCreditCardSurcharge(
            creditCardId: $userBilling->CcType,
            amount: $balanceDue,
            taxSchemaId: null,
            accountId: $invoice->AccountId
        );

        $chargeAmount = StackedTaxInvoicePureCalculator::new()->calcPaymentAmount(
            $balanceDue,
            $ccSurchargeResult?->amount,
            $ccSurchargeResult?->taxAmount
        );
        $chargeAmountFormatted = $this->getNumberFormatter()->formatMoney($chargeAmount, $invoice->AccountId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'balance due' => $balanceDue,
            'cc surcharge' => $ccSurchargeResult?->amount,
            'cc surcharge tax' => $ccSurchargeResult?->taxAmount,
            'charge amount' => $chargeAmount,
        ];
        log_debug("Start charging invoice" . composeSuffix($logData));

        $input = AdminStackedTaxInvoicePaymentOpayoChargingInput::new()->constructForCcOnFile(
            $editorUserId,
            $invoice,
            $chargeAmountFormatted,
            $chargeAmountFormatted,
            null,
            '', // note
            '',
            session_id()
        );
        $chargeResult = $this->createAdminStackedTaxInvoicePaymentOpayoCharger()->charge($input);

        if ($chargeResult->hasError()) {
            $errorMessage = $chargeResult->errorMessage();
            log_error($errorMessage);
            return $errorMessage;
        }

        /**
         * Create Payment record
         */
        $payment = $this->getInvoicePaymentManager()->add(
            $invoice->Id,
            Constants\Payment::PM_CC_ON_FILE,
            $chargeAmount,
            $editorUserId,
            composeLogData($chargeResult->noteInfo),
            $this->getCurrentDateUtc(),
            null,
            $userBilling->CcType
        );

        /**
         * Calculate and create CC Surcharge record (InvoiceAdditional)
         */
        if (
            $ccSurchargeResult
            && Floating::gt($ccSurchargeResult->amount, 0.)
        ) {
            $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->add(
                Constants\Invoice::IA_CC_SURCHARGE,
                $invoice->Id,
                $ccSurchargeResult->name,
                $ccSurchargeResult->amount,
                $editorUserId,
                composeLogData($chargeResult->noteInfo),
                null,
                null,
                null,
                $payment->Id,
            );
        }

        $invoice = $this->getStackedTaxInvoiceSummaryCalculator()->recalculateInvoice($invoice);
        /**
         * Mark invoice as paid, if balance due is zero and transaction was successfully completed and was not held for review
         */
        $newBalanceDue = $invoice->calcRoundedBalanceDue();
        if (
            Floating::lteq($newBalanceDue, 0.)
            && !$invoice->isShipped()
        ) {
            $invoice->toPaid();
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $logData = [
            'i' => $invoice->Id,
            'u' => $invoice->BidderId,
            'is' => $invoice->InvoiceStatusId,
            'New balance due' => $newBalanceDue,
            'pmnt' => $payment->Id,
            'iadd' => $invoiceAdditional->Id ?? null,
        ];
        log_trace("Invoice payment successful" . composeSuffix($logData));

        /**
         * Notify user about payment by e-mail
         */
        $this->createAdminStackedTaxInvoiceChargingNotifier()->notify($invoice, $editorUserId);

        return true;
    }

    /**
     * @param Invoice $invoice
     * @param int $editorUserId
     * @return array
     */
    protected function getParams(Invoice $invoice, int $editorUserId): array
    {
        $bidderBilling = $this->getUserLoader()->loadUserBillingOrCreate($invoice->BidderId);
        if (
            $this->createBlockCipherProvider()->construct()->decrypt($bidderBilling->CcNumber) === ''
            || $bidderBilling->CcExpDate === ''
        ) {
            throw new RuntimeException('Bidder has no CIM and has no credit card info');
        }
        $params = [];
        $params[Constants\BillingParam::ACCOUNT_ID] = $invoice->AccountId;

        $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId);
        $params[Constants\BillingParam::BILLING_COMPANY_NAME] = $invoiceBilling->CompanyName;
        $params[Constants\BillingParam::BILLING_FIRST_NAME] = $invoiceBilling->FirstName;
        $params[Constants\BillingParam::BILLING_LAST_NAME] = $invoiceBilling->LastName;
        $params[Constants\BillingParam::BILLING_ADDRESS] = $invoiceBilling->Address;
        $params[Constants\BillingParam::BILLING_ADDRESS_2] = $invoiceBilling->Address2;
        $params[Constants\BillingParam::BILLING_ADDRESS_3] = $invoiceBilling->Address3;
        $params[Constants\BillingParam::BILLING_CITY] = $invoiceBilling->City;
        $params[Constants\BillingParam::BILLING_STATE] = $invoiceBilling->State;
        $params[Constants\BillingParam::BILLING_COUNTRY] = $invoiceBilling->Country;
        $params[Constants\BillingParam::BILLING_ZIP] = $invoiceBilling->Zip;
        $params[Constants\BillingParam::BILLING_PHONE] = $invoiceBilling->Phone;
        $params[Constants\BillingParam::BILLING_FAX] = $invoiceBilling->Fax;
        $params[Constants\BillingParam::BILLING_EMAIL] = $invoiceBilling->Email;

        $invoiceShipping = $this->getInvoiceUserLoader()
            ->loadInvoiceUserShippingOrCreatePersisted($invoice->Id, $editorUserId);
        $params[Constants\BillingParam::SHIPPING_COMPANY_NAME] = $invoiceShipping->CompanyName;
        $params[Constants\BillingParam::SHIPPING_FIRST_NAME] = $invoiceShipping->FirstName;
        $params[Constants\BillingParam::SHIPPING_LAST_NAME] = $invoiceShipping->LastName;
        $params[Constants\BillingParam::SHIPPING_ADDRESS] = $invoiceShipping->Address;
        $params[Constants\BillingParam::SHIPPING_ADDRESS_2] = $invoiceShipping->Address2;
        $params[Constants\BillingParam::SHIPPING_ADDRESS_3] = $invoiceShipping->Address3;
        $params[Constants\BillingParam::SHIPPING_CITY] = $invoiceShipping->City;
        $params[Constants\BillingParam::SHIPPING_STATE] = $invoiceShipping->State;
        $params[Constants\BillingParam::SHIPPING_COUNTRY] = $invoiceShipping->Country;
        $params[Constants\BillingParam::SHIPPING_ZIP] = $invoiceShipping->Zip;
        $params[Constants\BillingParam::SHIPPING_PHONE] = $invoiceShipping->Phone;
        $params[Constants\BillingParam::SHIPPING_FAX] = $invoiceShipping->Fax;

        [$ccExpMonth, $ccExpYear] = $this->createCcExpiryDateBuilder()->explode($bidderBilling->CcExpDate);

        $params[Constants\BillingParam::CC_TYPE] = $bidderBilling->CcType;
        $params[Constants\BillingParam::CC_NUMBER] = $this->createBlockCipherProvider()->construct()->decrypt($bidderBilling->CcNumber);
        $params[Constants\BillingParam::CC_EXP_MONTH] = $ccExpMonth;
        $params[Constants\BillingParam::CC_EXP_YEAR] = $ccExpYear;

        return $params;
    }

    /**
     * @param int $invoiceId
     */
    protected function handleChargeErrorInvoiceNotFound(int $invoiceId): void
    {
        $message = "Cannot charge payment - active invoice not found" . composeSuffix(['i' => $invoiceId]);
        log_error($message);
        throw new RuntimeException($message);
    }

    /**
     * @param int $invoiceId
     * @param int $userId
     */
    protected function handleChargeErrorInvoiceWinnerNotFound(int $invoiceId, int $userId): void
    {
        $message = "Cannot charge payment - active user of invoice winner not found"
            . composeSuffix(['i' => $invoiceId, 'u' => $userId]);
        log_error($message);
        throw new RuntimeException($message);
    }
}
