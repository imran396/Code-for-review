<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single;

use InvoiceAdditional;
use InvoiceAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\InvoiceItemSavingResult;

class StackedTaxSingleInvoiceItemProductionResult extends CustomizableClass
{
    /** @var InvoiceAdditional[] */
    public array $invoiceAdditionals = [];
    public ?InvoiceAuction $invoiceAuction;
    public InvoiceItemSavingResult $invoiceItemSavingResult;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    // --- Mutate ---

    public function setInvoiceItemSavingResult(InvoiceItemSavingResult $invoiceItemSavingResult): static
    {
        $this->invoiceItemSavingResult = $invoiceItemSavingResult;
        return $this;
    }

    public function addInvoiceAdditional(InvoiceAdditional $invoiceAdditional): static
    {
        $this->invoiceAdditionals[] = $invoiceAdditional;
        return $this;
    }

    public function setInvoiceAuction(InvoiceAuction $invoiceAuction): static
    {
        $this->invoiceAuction = $invoiceAuction;
        return $this;
    }

    // --- Query ---

    public function hasError(): bool
    {
        return $this->hasInvoiceItemSavingError();
    }

    public function hasInvoiceItemSavingError(): bool
    {
        return $this->invoiceItemSavingResult->hasError();
    }

    public function hasHpTaxSchemaNotFoundError(): bool
    {
        return $this->invoiceItemSavingResult->hasHpTaxSchemaNotFoundError();
    }

    public function getHpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->invoiceItemSavingResult->getHpTaxSchemaNotFoundClarificationData();
    }

    public function hasBpTaxSchemaNotFoundError(): bool
    {
        return $this->invoiceItemSavingResult->hasBpTaxSchemaNotFoundError();
    }

    public function getBpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->invoiceItemSavingResult->getBpTaxSchemaNotFoundClarificationData();
    }

    public function errorMessage(): string
    {
        return $this->invoiceItemSavingResult->errorMessage();
    }

    public function hasInfo(): bool
    {
        return $this->invoiceItemSavingResult->hasInfo();
    }

    public function infoMessage(): string
    {
        return $this->invoiceItemSavingResult->infoMessage();
    }
}
