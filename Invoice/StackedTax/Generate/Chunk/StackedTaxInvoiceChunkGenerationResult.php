<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25-08-2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Item\Multiple\StackedTaxMultipleInvoiceItemProductionResult;

/**
 * Class InvoiceChunkGeneratorResult
 * @package Sam\Invoice\StackedTax\Generate\Chunk
 */
class StackedTaxInvoiceChunkGenerationResult extends CustomizableClass
{
    public bool $hasRemaining = false;
    /** @var int[] */
    public array $invoiceIds = [];
    /** @var int[] */
    public array $generatedCounts = [];
    /** @var string[][] */
    public array $reportMessages = [];
    /** @var string[][] */
    public array $errorMessages = [];
    /** @var StackedTaxMultipleInvoiceItemProductionResult[] */
    public array $multipleInvoiceItemProductionResults = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Mutation ---

    public function initAccountIds(array $accountIds): static
    {
        foreach ($accountIds as $accountId) {
            $this->setGeneratedCountForAccount($accountId, 0);
        }
        return $this;
    }

    public function setHasRemaining(bool $hasRemaining): static
    {
        $this->hasRemaining = $hasRemaining;
        return $this;
    }

    public function setInvoiceIds(array $invoiceIds): static
    {
        $this->invoiceIds = $invoiceIds;
        return $this;
    }

    public function addInvoiceId(int $invoiceId): static
    {
        $this->invoiceIds[] = $invoiceId;
        return $this;
    }

    public function addInvoiceIds(array $invoiceIds): static
    {
        if ($invoiceIds) {
            $this->invoiceIds = array_merge($this->invoiceIds, $invoiceIds);
        }
        return $this;
    }

    public function setGeneratedCounts(array $generatedCounts): static
    {
        $this->generatedCounts = $generatedCounts;
        return $this;
    }

    public function setGeneratedCountForAccount(int $accountId, int $count): static
    {
        $this->generatedCounts[$accountId] = $count;
        return $this;
    }

    public function addGeneratedCountForAccount(int $accountId, int $count): static
    {
        $this->generatedCounts[$accountId] += $count;
        return $this;
    }

    public function setReportMessages(array $reportMessages): static
    {
        $this->reportMessages = $reportMessages;
        return $this;
    }

    public function addReportMessagesForAccount(int $accountId, array $reportMessages): static
    {
        $this->reportMessages[$accountId] = array_merge($this->reportMessages[$accountId] ??= [], $reportMessages);
        $this->reportMessages[$accountId] = array_unique($this->reportMessages[$accountId]);
        return $this;
    }

    public function setErrorMessages(array $errorMessages): static
    {
        $this->errorMessages = $errorMessages;
        return $this;
    }

    public function addErrorMessagesForAccount(int $accountId, array $errorMessages): static
    {
        $this->errorMessages[$accountId] = array_merge($this->errorMessages[$accountId] ??= [], $errorMessages);
        return $this;
    }

    public function addResultForAccount(int $accountId, self $result): static
    {
        $this->addInvoiceIds($result->invoiceIds);
        $this->addGeneratedCountForAccount($accountId, $result->getGeneratedCountForAccount($accountId));
        $this->addReportMessagesForAccount($accountId, $result->getReportMessagesForAccount($accountId));
        $this->addErrorMessagesForAccount($accountId, $result->getErrorMessagesForAccount($accountId));
        $this->addMultipleInvoiceItemProductionResultForAccount($accountId, $result->getMultipleInvoiceItemProductionResultForAccount($accountId));
        return $this;
    }

    public function addMultipleInvoiceItemProductionResultForAccount(int $accountId, ?StackedTaxMultipleInvoiceItemProductionResult $multipleInvoiceItemProductionResult): static
    {
        $this->multipleInvoiceItemProductionResults[$accountId] = $multipleInvoiceItemProductionResult;
        return $this;
    }

    // --- Query ---

    public function getGeneratedCountForAccount(int $accountId): int
    {
        return $this->generatedCounts[$accountId] ?? 0;
    }

    public function hasReportMessagesForAccount(int $accountId): bool
    {
        return !empty($this->reportMessages[$accountId]);
    }

    public function getReportMessagesForAccount(int $accountId): array
    {
        return $this->reportMessages[$accountId] ?? [];
    }

    public function hasErrorMessagesForAccount(int $accountId): bool
    {
        return !empty($this->errorMessages[$accountId]);
    }

    public function getErrorMessagesForAccount(int $accountId): array
    {
        return $this->errorMessages[$accountId] ?? [];
    }

    public function getMultipleInvoiceItemProductionResultForAccount(int $accountId): ?StackedTaxMultipleInvoiceItemProductionResult
    {
        return $this->multipleInvoiceItemProductionResults[$accountId] ?? null;
    }

    public function hasErrorThatMustStopInvoiceGeneration(): bool
    {
        foreach (array_filter($this->multipleInvoiceItemProductionResults) as $multipleInvoiceItemProductionResult) {
            if ($multipleInvoiceItemProductionResult->hasErrorThatMustStopInvoiceGeneration()) {
                return true;
            }
        }
        return false;
    }

    public function hasError(): bool
    {
        return count(array_filter($this->errorMessages));
    }
}
