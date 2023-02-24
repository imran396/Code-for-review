<?php
/**
 * SAM-10934: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Multiple Invoice Items validation and save (#invoice-save-2)
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

namespace Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Validate;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class InvoiceItemFormMultipleItemEditValidationResult
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Validate
 */
class InvoiceItemFormMultipleItemEditValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_NO_REQUIRED = 1;
    public const ERR_INVOICE_NO_INVALID = 2;
    public const ERR_INVOICE_NO_EXISTS = 3;
    public const ERR_HAMMER_PRICE_INVALID = 4;
    public const ERR_BUYERS_PREMIUM_INVALID = 5;
    public const ERR_SALES_TAX_PERCENT_INVALID = 6;
    public const ERR_TAX_CHARGES_RATE_INVALID = 7;
    public const ERR_TAX_FEES_RATE_INVALID = 8;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NO_REQUIRED => 'Invoice# required',
        self::ERR_INVOICE_NO_INVALID => 'Invoice# must be positive integer',
        self::ERR_INVOICE_NO_EXISTS => 'Invoice# already exists',
        self::ERR_HAMMER_PRICE_INVALID => 'Hammer Price invalid',
        self::ERR_BUYERS_PREMIUM_INVALID => 'Buyer\'s Premium invalid',
        self::ERR_SALES_TAX_PERCENT_INVALID => 'Sales Tax invalid',
        self::ERR_TAX_CHARGES_RATE_INVALID => 'Tax Charges Rate invalid',
        self::ERR_TAX_FEES_RATE_INVALID => 'Tax Fees Rate invalid',
    ];

    protected const PKEY_INVOICE_ITEM_ID = "invoiceItemId";

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

    public function addRowError(int $code, int $invoiceItemId): static
    {
        $this->getResultStatusCollector()->addError($code, null, [self::PKEY_INVOICE_ITEM_ID => $invoiceItemId]);
        return $this;
    }


    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasInvoiceNoRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVOICE_NO_REQUIRED);
    }

    public function hasInvoiceNoInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVOICE_NO_INVALID);
    }

    public function hasInvoiceNoExistsError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVOICE_NO_EXISTS);
    }

    public function hasHammerPriceInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_HAMMER_PRICE_INVALID);
    }

    public function hasBuyersPremiumInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BUYERS_PREMIUM_INVALID);
    }

    public function hasSalesTaxPercentInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_SALES_TAX_PERCENT_INVALID);
    }

    public function hasTaxChargesRateInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_TAX_CHARGES_RATE_INVALID);
    }

    public function hasTaxFeesRateInvalidError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_TAX_FEES_RATE_INVALID);
    }

    public function failedHammerPriceInvoiceItemIds(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_HAMMER_PRICE_INVALID);
        $invoiceItemIds = ArrayCast::arrayColumnInt($payloads, self::PKEY_INVOICE_ITEM_ID);
        return $invoiceItemIds;
    }

    public function failedBuyersPremiumInvoiceItemIds(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_BUYERS_PREMIUM_INVALID);
        $invoiceItemIds = ArrayCast::arrayColumnInt($payloads, self::PKEY_INVOICE_ITEM_ID);
        return $invoiceItemIds;
    }

    public function failedSalesTaxPercentInvoiceItemIds(): array
    {
        $payloads = $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_SALES_TAX_PERCENT_INVALID);
        $invoiceItemIds = ArrayCast::arrayColumnInt($payloads, self::PKEY_INVOICE_ITEM_ID);
        return $invoiceItemIds;
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = ", "): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function isSuccess(): bool
    {
        return !$this->hasError();
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
            if ($this->hasHammerPriceInvalidError()) {
                $logData['failed hp ii'] = $this->failedHammerPriceInvoiceItemIds();
            }
            if ($this->hasBuyersPremiumInvalidError()) {
                $logData['failed bp ii'] = $this->failedBuyersPremiumInvoiceItemIds();
            }
            if ($this->hasSalesTaxPercentInvalidError()) {
                $logData['failed sales tax ii'] = $this->failedSalesTaxPercentInvoiceItemIds();
            }
        }
        return $logData;
    }
}
