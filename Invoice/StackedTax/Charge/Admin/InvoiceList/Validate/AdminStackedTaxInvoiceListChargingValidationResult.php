<?php
/**
 * SAM-11525: Stacked Tax. Actions at the Admin Invoice List page. Extract general validation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class AdminStackedTaxInvoiceListChargingValidationResult
 * @package Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate
 */
class AdminStackedTaxInvoiceListChargingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_NOT_FOUND = 1;
    public const ERR_INVOICE_DELETED = 2;
    public const ERR_INVOICE_CANCELED = 3;
    public const ERR_INVOICE_BALANCE_DUE_ZERO = 4;
    public const ERR_INVOICE_NOT_OPERABLE = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NOT_FOUND => 'Invoice not found',
        self::ERR_INVOICE_DELETED => 'Invoice deleted',
        self::ERR_INVOICE_CANCELED => 'Invoice canceled',
        self::ERR_INVOICE_BALANCE_DUE_ZERO => 'Invoice balance due is zero',
        self::ERR_INVOICE_NOT_OPERABLE => 'Invoice not operable',
    ];

    public ?Invoice $invoice = null;

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
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES
        );
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function setInvoice(Invoice $invoice): static
    {
        $this->invoice = $invoice;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return int|null
     */
    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasInvoiceNotFoundError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_NOT_FOUND]);
    }

    public function hasInvoiceDeletedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_DELETED]);
    }

    public function hasInvoiceCanceledError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_CANCELED]);
    }

    public function hasInvoiceBalanceDueZeroError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_BALANCE_DUE_ZERO]);
    }

    public function hasInvoiceNotOperableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_INVOICE_NOT_OPERABLE]);
    }
}
