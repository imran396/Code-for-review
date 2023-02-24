<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\AddLot;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionResult;

/**
 * Class StackedTaxInvoiceSoldLotAddingResult
 * @package Sam\Invoice\StackedTax\AddLot
 */
class StackedTaxInvoiceSoldLotAddingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_PRODUCTION_FAILED = 1;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_PRODUCTION_FAILED => 'Unable to produce invoice',
    ];

    public ?StackedTaxSingleInvoiceItemProductionResult $invoiceProductionResult = null;
    public int $addedLotCount = 0;

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

    public function addInvoiceProductionError(StackedTaxSingleInvoiceItemProductionResult $invoiceProductionResult): static
    {
        $this->addError(self::ERR_INVOICE_PRODUCTION_FAILED);
        $this->invoiceProductionResult = $invoiceProductionResult;
        return $this;
    }

    public function setAddedLotCount(int $count): static
    {
        $this->addedLotCount = $count;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasInvoiceProductionError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVOICE_PRODUCTION_FAILED);
    }

    public function hasHpTaxSchemaNotFoundError(): bool
    {
        return $this->invoiceProductionResult->hasHpTaxSchemaNotFoundError();
    }

    public function getHpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->invoiceProductionResult->getHpTaxSchemaNotFoundClarificationData();
    }

    public function hasBpTaxSchemaNotFoundError(): bool
    {
        return $this->invoiceProductionResult->hasBpTaxSchemaNotFoundError();
    }

    public function getBpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->invoiceProductionResult->getBpTaxSchemaNotFoundClarificationData();
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
}
