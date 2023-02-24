<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\Common\TransactionParameter;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

class TransactionParameterCollection extends CustomizableClass
{
    protected array $params = [];

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromTranParam(string $tranParam): static
    {
        $params = unserialize($tranParam, ['allowed_classes' => false]);
        $this->params = is_array($params) ? $params : [];
        return $this;
    }

    // --- Mutation logic ---

    // --- Setters for general params ---

    public function setParams(array $params): static
    {
        unset(
            $params[Constants\BillingParam::CC_NUMBER],
            $params[Constants\BillingParam::CC_NUMBER_HASH],
            $params[Constants\BillingParam::CC_EXP_MONTH],
            $params[Constants\BillingParam::CC_EXP_YEAR],
            $params[Constants\BillingParam::CC_CODE],
            $params[Constants\BillingParam::AUTH_CAPTURE_CC_NUMBER],
            $params[Constants\BillingParam::AUTH_CAPTURE_CC_EXP],
            $params[Constants\BillingParam::AUTH_CAPTURE_CC_CODE]
        );
        $this->params = $params;
        return $this;
    }

    public function setAccountId(int $accountId): static
    {
        $this->params[Constants\BillingParam::ACCOUNT_ID] = $accountId;
        return $this;
    }

    // --- Setters for Opayo params ---

    public function setAmount(float $amount): static
    {
        $this->params[Constants\BillingOpayo::P_AMOUNT] = $amount;
        return $this;
    }

    public function setInvoicePaymentEditAmount(string $amount): static
    {
        $this->params[Constants\BillingOpayo::P_INVOICE_PAYMENT_EDITABLE_FORM_AMOUNT] = $amount;
        return $this;
    }


    public function setPaymentType(string $paymentType): static
    {
        $this->params[Constants\BillingOpayo::P_PAYMENT_TYPE] = $paymentType;
        return $this;
    }

    public function setUserId(int $userId): static
    {
        $this->params[Constants\BillingOpayo::P_USER_ID] = $userId;
        return $this;
    }

    public function setPaymentUrl(string $paymentUrl): static
    {
        $this->params[Constants\BillingOpayo::P_PAYMENT_URL] = $paymentUrl;
        return $this;
    }

    public function setPaymentItemId(int $paymentItemId): static
    {
        $this->params[Constants\BillingOpayo::P_PAYMENT_ITEM_ID] = $paymentItemId;
        return $this;
    }

    public function setCreditCardId(int $creditCardId): static
    {
        $this->params[Constants\BillingOpayo::P_CREDIT_CARD_ID] = $creditCardId;
        return $this;
    }

    public function setTaxSchemaId(?int $taxSchemaId): static
    {
        $this->params[Constants\BillingOpayo::P_TAX_SCHEMA_ID] = $taxSchemaId;
        return $this;
    }

    public function setNote(string $note): static
    {
        $this->params[Constants\BillingOpayo::P_NOTE] = $note;
        return $this;
    }

    public function enableCcModified(bool $isCcModified): static
    {
        $this->params[Constants\BillingOpayo::P_CC_MODIFIED] = $isCcModified;
        return $this;
    }

    public function setAuthCapture(string $authCapture): static
    {
        $this->params[Constants\BillingOpayo::P_AUTH_CAPTURE] = $authCapture;
        return $this;
    }

    public function setCarrierMethod(string $carrierMethod): static
    {
        $this->params[Constants\BillingOpayo::P_CARRIER_METHOD] = $carrierMethod;
        return $this;
    }

    public function setThreeDCallbackData(array $data): static
    {
        $this->params[Constants\BillingOpayo::P_THREE_D_SECURE_CALLBACK_PARAMS] = $data;
        return $this;
    }

    public function setVpsTxId(string $vpstxid): static
    {
        $this->params[Constants\BillingOpayo::P_VPSTX_ID] = $vpstxid;
        return $this;
    }

    public function setPaymentGateway(string $paymentGateway): static
    {
        $this->params[Constants\SettingUser::P_PAYMENT_GATEWAY] = $paymentGateway;
        return $this;
    }

    public function setSessionId(string $sessionId): static
    {
        $this->params[Constants\BillingOpayo::P_SESSION_ID] = $sessionId;
        return $this;
    }

    public function setOpayoAuthTransactionType(int $transactionType): static
    {
        $this->params[Constants\BillingOpayo::P_OPAYO_AUTH_TRANSACTION_TYPE] = $transactionType;
        return $this;
    }

    // --- Query logic ---

    // --- General parameters getters ---

    public function getParams(): array
    {
        return $this->params;
    }

    public function getAccountId(): int
    {
        return Cast::toInt($this->params[Constants\BillingParam::ACCOUNT_ID] ?? null);
    }

    public function getPaymentGateway(): string
    {
        return (string)Cast::toString($this->params[Constants\SettingUser::P_PAYMENT_GATEWAY] ?? '');
    }

    public function getNote(): string
    {
        return (string)Cast::toString($this->params[Constants\BillingOpayo::P_NOTE] ?? '');
    }

    public function getCcType(): int
    {
        return Cast::toInt($this->params[Constants\BillingParam::CC_TYPE] ?? null);
    }

    public function getAuctionId(): ?int
    {
        return Cast::toInt($this->params['AuctionId'] ?? null);
    }

    public function getFirstName(): string
    {
        return (string)Cast::toString($this->params['first_name'] ?? '');
    }

    public function getLastName(): string
    {
        return (string)Cast::toString($this->params['last_name'] ?? '');
    }

    // --- Opayo parameters getters ---

    public function getAmount(): float
    {
        return $this->params[Constants\BillingOpayo::P_AMOUNT];
    }

    public function getInvoicePaymentEditAmount(): string
    {
        return $this->params[Constants\BillingOpayo::P_INVOICE_PAYMENT_EDITABLE_FORM_AMOUNT];
    }

    public function getAuthCapture(): string
    {
        return $this->params[Constants\BillingOpayo::P_AUTH_CAPTURE];
    }

    public function getUserId(): ?int
    {
        return Cast::toInt($this->params[Constants\BillingOpayo::P_USER_ID] ?? null);
    }

    public function getThreeDCallbackData(): array
    {
        return $this->params[Constants\BillingOpayo::P_THREE_D_SECURE_CALLBACK_PARAMS];
    }

    public function isCcModified(): ?bool
    {
        return Cast::toBool($this->params[Constants\BillingOpayo::P_CC_MODIFIED] ?? false);
    }

    public function hasPaymentUrl(): bool
    {
        return $this->getPaymentUrl() !== '';
    }

    public function getPaymentUrl(): string
    {
        return $this->params[Constants\BillingOpayo::P_PAYMENT_URL] ?? '';
    }

    public function hasVpsTxId(): bool
    {
        return isset($this->params[Constants\BillingOpayo::P_VPSTX_ID]);
    }

    public function getVpsTxId(): string
    {
        return $this->params[Constants\BillingOpayo::P_VPSTX_ID] ?: '';
    }

    public function getPaymentItemId(): ?int
    {
        return Cast::toInt($this->params[Constants\BillingOpayo::P_PAYMENT_ITEM_ID] ?? null);
    }

    public function getCreditCardId(): ?int
    {
        return Cast::toInt($this->params[Constants\BillingOpayo::P_CREDIT_CARD_ID] ?? null);
    }

    public function getTaxSchemaId(): ?int
    {
        return Cast::toInt($this->params[Constants\BillingOpayo::P_TAX_SCHEMA_ID] ?? null);
    }

    public function getPaymentType(): string
    {
        return $this->params[Constants\BillingOpayo::P_PAYMENT_TYPE] ?? '';
    }

    public function hasThreeDCallbackData(): bool
    {
        return isset($this->params[Constants\BillingOpayo::P_THREE_D_SECURE_CALLBACK_PARAMS]);
    }

    public function hasSessionId(): bool
    {
        return isset($this->params[Constants\BillingOpayo::P_SESSION_ID]);
    }

    public function getSessionId(): string
    {
        return $this->params[Constants\BillingOpayo::P_SESSION_ID] ?? '';
    }

    public function getOpayoAuthTransactionType(): int
    {
        return Cast::toInt($this->params[Constants\BillingOpayo::P_OPAYO_AUTH_TRANSACTION_TYPE]);
    }

    public function getCarrierMethod(): string
    {
        return $this->params[Constants\BillingOpayo::P_CARRIER_METHOD] ?? '';
    }

    // --- Payment type check ---

    public function shouldChargeResponsiveInvoiceView(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_RESPONSIVE_INVOICE_VIEW;
    }

    public function shouldChargeAdminInvoiceListOrEdit(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_LIST
            || $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_EDIT;
    }

    public function shouldChargeAdminInvoicePaymentEdit(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_PAYMENT_EDIT_ON_INPUT
            || $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_PAYMENT_EDIT_ON_FILE;
    }

    public function shouldChargeAdminInvoiceList(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_LIST;
    }

    public function shouldChargeAdminInvoiceEdit(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_ADMIN_INVOICE_EDIT;
    }

    public function shouldChargeSettlement(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_CHARGE_SETTLEMENT;
    }

    public function shouldAuthAuctionRegistration(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_AUTH_AUCTION_REGISTRATION;
    }

    public function shouldAuthAccountRegistration(): bool
    {
        return $this->getPaymentType() === Constants\BillingOpayo::PT_AUTH_ACCOUNT_REGISTRATION;
    }

    // ---

    public function serialize(): string
    {
        return serialize($this->params);
    }
}
