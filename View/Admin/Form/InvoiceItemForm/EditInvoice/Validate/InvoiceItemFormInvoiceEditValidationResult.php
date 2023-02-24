<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class InvoiceItemFormInvoiceEditValidationResult
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate
 */
class InvoiceItemFormInvoiceEditValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_SHIPPING_AMOUNT_INVALID = 1;
    public const ERR_PAYMENT_AMOUNT_INVALID = 2;
    public const ERR_CHARGE_AMOUNT_INVALID = 3;
    public const ERR_PAYMENT_METHOD_UNDEFINED = 4;
    public const ERR_CREDIT_CARD_TYPE_UNDEFINED = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_SHIPPING_AMOUNT_INVALID => 'Invalid shipping amount',
        self::ERR_PAYMENT_AMOUNT_INVALID => 'Invalid payment amount',
        self::ERR_CHARGE_AMOUNT_INVALID => 'Invalid charge amount',
        self::ERR_PAYMENT_METHOD_UNDEFINED => 'Payment method undefined',
        self::ERR_CREDIT_CARD_TYPE_UNDEFINED => 'Credit card type undefined',
    ];

    private const PKEY_INDEX = 'index';

    protected const PAYMENT_ERRORS = [
        self::ERR_PAYMENT_AMOUNT_INVALID,
        self::ERR_PAYMENT_METHOD_UNDEFINED,
        self::ERR_CREDIT_CARD_TYPE_UNDEFINED,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addPaymentError(int $code, int $index): static
    {
        $this->getResultStatusCollector()->addError($code, null, [self::PKEY_INDEX => $index]);
        return $this;
    }

    public function addChargeError(int $code, int $index): static
    {
        $this->getResultStatusCollector()->addError($code, null, [self::PKEY_INDEX => $index]);
        return $this;
    }


    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasShippingAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_SHIPPING_AMOUNT_INVALID);
    }

    public function hasPaymentError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::PAYMENT_ERRORS);
    }

    public function hasPaymentAmountErrorForIndex(int $index): bool
    {
        return in_array($index, $this->getFailedPaymentAmountIndexes(), true);
    }

    public function hasPaymentMethodErrorForIndex(int $index): bool
    {
        return in_array($index, $this->getFailedPaymentMethodIndexes(), true);
    }

    public function hasPaymentCreditCardErrorForIndex(int $index): bool
    {
        return in_array($index, $this->getFailedPaymentCreditCardIndexes(), true);
    }

    public function hasChargeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CHARGE_AMOUNT_INVALID);
    }

    public function getFailedPaymentIndexes(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::PAYMENT_ERRORS);
        $indexes = ArrayCast::arrayColumnInt($payloads, self::PKEY_INDEX);
        return $indexes;
    }

    public function getFailedPaymentAmountIndexes(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_PAYMENT_AMOUNT_INVALID);
        $indexes = ArrayCast::arrayColumnInt($payloads, self::PKEY_INDEX);
        return $indexes;
    }

    public function getFailedPaymentMethodIndexes(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_PAYMENT_METHOD_UNDEFINED);
        $indexes = ArrayCast::arrayColumnInt($payloads, self::PKEY_INDEX);
        return $indexes;
    }

    public function getFailedPaymentCreditCardIndexes(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_CREDIT_CARD_TYPE_UNDEFINED);
        $indexes = ArrayCast::arrayColumnInt($payloads, self::PKEY_INDEX);
        return $indexes;
    }

    public function getFailedChargeIndexes(): array
    {
        $charges = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_CHARGE_AMOUNT_INVALID);
        $indexes = ArrayCast::arrayColumnInt($charges, self::PKEY_INDEX);
        return $indexes;
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }

        return '';
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCodes(),
                'error message' => $this->errorMessage()
            ];
        }
        return $logData;
    }
}
