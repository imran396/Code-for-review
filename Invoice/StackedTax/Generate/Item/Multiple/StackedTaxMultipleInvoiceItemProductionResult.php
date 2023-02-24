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

namespace Sam\Invoice\StackedTax\Generate\Item\Multiple;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionResult;

/**
 * Class StackedTaxMultipleInvoiceItemProductionResult
 * @package Sam\Invoice\StackedTax\Generate\Item\Multiple
 */
class StackedTaxMultipleInvoiceItemProductionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOT_ITEM_ALREADY_IN_INVOICE = 1;
    public const ERR_SINGLE_INVOICE_ITEM_PRODUCTION_FAILED = 2;

    // IK, 2023-01: Currently we don't provide invoice item skipping case, but it's possible in future.
    public const INFO_SINGLE_INVOICE_ITEM_ADDING_SKIPPED = 31;

    protected const ERROR_MESSAGES = [
        self::ERR_LOT_ITEM_ALREADY_IN_INVOICE => 'Lot item already registered in invoice',
        self::ERR_SINGLE_INVOICE_ITEM_PRODUCTION_FAILED => 'Invoice production failed',
    ];

    protected const INFO_MESSAGES = [
        self::INFO_SINGLE_INVOICE_ITEM_ADDING_SKIPPED => 'Invoice item adding skipped',
    ];

    public ?StackedTaxSingleInvoiceItemProductionResult $singleInvoiceItemProductionResult = null;
    /** @var StackedTaxSingleInvoiceItemProductionResult[] */
    public array $skippedSingleInvoiceItemProductionResults = [];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, [], [], self::INFO_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSingleInvoiceItemProductionFailedError(StackedTaxSingleInvoiceItemProductionResult $singleInvoiceItemProductionResult): static
    {
        $this->addError(self::ERR_SINGLE_INVOICE_ITEM_PRODUCTION_FAILED);
        $this->singleInvoiceItemProductionResult = $singleInvoiceItemProductionResult;
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // IK, 2023-01: Currently we don't provide invoice item skipping case, but it's possible in future.
    public function addSingleInvoiceItemProductionSkippedInfo(StackedTaxSingleInvoiceItemProductionResult $singleInvoiceItemProductionResult): static
    {
        $this->addInfo(self::INFO_SINGLE_INVOICE_ITEM_ADDING_SKIPPED);
        $this->skippedSingleInvoiceItemProductionResults[] = $singleInvoiceItemProductionResult;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasErrorThatMustStopInvoiceGeneration(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_SINGLE_INVOICE_ITEM_PRODUCTION_FAILED);
    }

    public function isSuccess(): bool
    {
        return !$this->hasError();
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = ". "): string
    {
        $message = $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
        if ($this->singleInvoiceItemProductionResult) {
            $message .= '. ' . $this->singleInvoiceItemProductionResult->errorMessage();
        }
        return $message;
    }

    public function hasInfo(): bool
    {
        return $this->getResultStatusCollector()->hasInfo();
    }

    public function hasSkippedLotItems(): bool
    {
        return !empty($this->skippedSingleInvoiceItemProductionResults);
    }
}
