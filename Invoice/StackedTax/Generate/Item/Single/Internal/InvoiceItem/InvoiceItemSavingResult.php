<?php
/**
 * This result-object handles result data of a single invoice item value calculation and saving operation.
 * It provides several error statuses that should lead to invoice generation ending (decision depends on caller, but it works this way now).
 * It provides possibility to register lot items for skipping, but we don't use this possibility now,
 * thus result-object does not contain respective info statuses for the skipping cases.
 * We can retire this logic in further when we will know that we definitely don't need it.
 *
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

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class InvoiceItemSavingResult
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem
 */
class InvoiceItemSavingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_HP_TAX_SCHEMA_NOT_FOUND = 1;
    public const ERR_BP_TAX_SCHEMA_NOT_FOUND = 2;
    public const ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH = 3;
    public const ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH = 4;

    protected const ERROR_MESSAGES = [
        self::ERR_HP_TAX_SCHEMA_NOT_FOUND => 'Tax Schema for HP not found',
        self::ERR_BP_TAX_SCHEMA_NOT_FOUND => 'Tax Schema for BP not found',
        self::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Tax Schema for HP country mismatch',
        self::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'Tax Schema for BP country mismatch',
    ];

    public ?InvoiceItem $invoiceItem = null;

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

    public function addError(int $code, array $clarificationData = []): static
    {
        $this->getResultStatusCollector()->addError($code, null, $clarificationData);
        return $this;
    }

    public function addInfo(int $code, array $clarificationData = []): static
    {
        $this->getResultStatusCollector()->addInfo($code, null, $clarificationData);
        return $this;
    }

    public function setInvoiceItem(InvoiceItem $invoiceItem): static
    {
        $this->invoiceItem = $invoiceItem;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasHpTaxSchemaNotFoundError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_HP_TAX_SCHEMA_NOT_FOUND);
    }

    public function getHpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_HP_TAX_SCHEMA_NOT_FOUND)[0] ?? [];
    }

    public function hasBpTaxSchemaNotFoundError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BP_TAX_SCHEMA_NOT_FOUND);
    }

    public function getBpTaxSchemaNotFoundClarificationData(): array
    {
        return $this->getResultStatusCollector()->getConcreteErrorPayloads(self::ERR_BP_TAX_SCHEMA_NOT_FOUND)[0] ?? [];
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = "; "): string
    {
        $outputs = [];
        $errorStatuses = $this->getResultStatusCollector()->getErrorStatuses();
        foreach ($errorStatuses as $errorStatus) {
            $outputs[] = $errorStatus->getMessage() . composeSuffix($errorStatus->getPayload());
        }
        return implode($glue, $outputs);
    }

    public function hasInfo(): bool
    {
        return $this->getResultStatusCollector()->hasInfo();
    }

    // IK, 2023-01: Currently we don't provide invoice item skipping case, but it's possible in future.
    public function infoMessage(string $glue = "; "): string
    {
        $outputs = [];
        $infoStatuses = $this->getResultStatusCollector()->getInfoStatuses();
        foreach ($infoStatuses as $infoStatus) {
            $outputs[] = $infoStatus->getMessage() . composeSuffix($infoStatus->getPayload());
        }
        return implode($glue, $outputs);
    }
}
