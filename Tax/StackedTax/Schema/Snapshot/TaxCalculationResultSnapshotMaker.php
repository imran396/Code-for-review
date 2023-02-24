<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxDefinition\TaxDefinitionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TaxDefinitionRange\TaxDefinitionRangeWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TaxSchema\TaxSchemaWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TaxSchemaTaxDefinition\TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Calculate\StackedTaxCalculationResult;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionRangeLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Snapshot\Internal\UniqueNameGeneratorCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use TaxDefinition;
use TaxSchema;

/**
 * Class TaxCalculationResultSnapshotMaker
 * @package Sam\Tax\StackedTax\Schema\Snapshot
 */
class TaxCalculationResultSnapshotMaker extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use EntityFactoryCreateTrait;
    use TaxDefinitionRangeLoaderCreateTrait;
    use TaxDefinitionRangeWriteRepositoryAwareTrait;
    use TaxDefinitionWriteRepositoryAwareTrait;
    use TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;
    use TaxSchemaWriteRepositoryAwareTrait;
    use UniqueNameGeneratorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function forInvoiceItem(
        StackedTaxCalculationResult $calculationResult,
        int $invoiceItemId,
        int $invoiceId,
        int $lotItemId,
        int $editorUserId,
        string $language
    ): TaxSchema {
        $taxSchema = $calculationResult->getTaxSchema();
        if ($taxSchema->isSnapshot()) {
            $this->updateTaxDefinitionCollectedAmounts($calculationResult, $editorUserId);
            return $taxSchema;
        }

        $taxSchemaSnapshot = $taxSchema->createSnapshot();
        $taxSchemaSnapshot->InvoiceItemId = $invoiceItemId;
        $taxSchemaSnapshot->Name = $this->makeTaxSchemaSnapshotNameForInvoiceItem($taxSchema, $invoiceId, $lotItemId, $language);
        $this->getTaxSchemaWriteRepository()->saveWithModifier($taxSchemaSnapshot, $editorUserId);

        foreach ($calculationResult->getTaxDefinitions() as $taxDefinition) {
            $taxDefinitionSnapshotName = $this->makeTaxDefinitionSnapshotNameForInvoiceItem(
                sourceTaxDefinition: $taxDefinition,
                sourceTaxSchemaId: $taxSchema->Id,
                invoiceId: $invoiceId,
                lotItemId: $lotItemId,
                language: $language
            );
            $collectedAmount = $calculationResult->getTaxAmountForDefinition($taxDefinition);
            $taxDefinitionSnapshot = $this->makeTaxDefinitionSnapshotWithAmount(
                sourceTaxDefinition: $taxDefinition,
                snapshotName: $taxDefinitionSnapshotName,
                collectedAmount: $collectedAmount,
                editorUserId: $editorUserId
            );
            $this->bindTaxDefinitionToSchema($taxDefinitionSnapshot->Id, $taxSchemaSnapshot->Id, $editorUserId);
        }

        return $taxSchemaSnapshot;
    }

    public function forInvoiceServiceFee(
        StackedTaxCalculationResult $calculationResult,
        int $invoiceAdditionalId,
        int $invoiceId,
        int $editorUserId,
        string $language
    ): TaxSchema {
        $taxSchema = $calculationResult->getTaxSchema();
        if ($taxSchema->isSnapshot()) {
            $this->updateTaxDefinitionCollectedAmounts($calculationResult, $editorUserId);
            return $taxSchema;
        }

        $taxSchemaSnapshot = $taxSchema->createSnapshot();
        $taxSchemaSnapshot->InvoiceAdditionalId = $invoiceAdditionalId;
        $taxSchemaSnapshot->Name = $this->makeTaxSchemaSnapshotNameForInvoiceServiceFee($taxSchema, $invoiceId, $invoiceAdditionalId, $language);
        $this->getTaxSchemaWriteRepository()->saveWithModifier($taxSchemaSnapshot, $editorUserId);

        foreach ($calculationResult->getTaxDefinitions() as $taxDefinition) {
            $taxDefinitionSnapshotName = $this->makeTaxDefinitionSnapshotNameForInvoiceServiceFee(
                sourceTaxDefinition: $taxDefinition,
                sourceTaxSchemaId: $taxSchema->Id,
                invoiceId: $invoiceId,
                invoiceAdditionalId: $invoiceAdditionalId,
                language: $language
            );
            $collectedAmount = $calculationResult->getTaxAmountForDefinition($taxDefinition);
            $taxDefinitionSnapshot = $this->makeTaxDefinitionSnapshotWithAmount(
                sourceTaxDefinition: $taxDefinition,
                snapshotName: $taxDefinitionSnapshotName,
                collectedAmount: $collectedAmount,
                editorUserId: $editorUserId
            );
            $this->bindTaxDefinitionToSchema($taxDefinitionSnapshot->Id, $taxSchemaSnapshot->Id, $editorUserId);
        }

        return $taxSchemaSnapshot;
    }

    protected function updateTaxDefinitionCollectedAmounts(StackedTaxCalculationResult $calculationResult, int $editorUserId): void
    {
        foreach ($calculationResult->getTaxDefinitions() as $taxDefinition) {
            $taxDefinition->CollectedAmount = $calculationResult->getTaxAmountForDefinition($taxDefinition);
            $this->getTaxDefinitionWriteRepository()->saveWithModifier($taxDefinition, $editorUserId);
        }
    }

    protected function makeTaxDefinitionSnapshotWithAmount(
        TaxDefinition $sourceTaxDefinition,
        string $snapshotName,
        float $collectedAmount,
        int $editorUserId
    ): TaxDefinition {
        $taxDefinitionSnapshot = $sourceTaxDefinition->createSnapshot();
        $taxDefinitionSnapshot->Name = $snapshotName;
        $taxDefinitionSnapshot->CollectedAmount = $collectedAmount;
        $this->getTaxDefinitionWriteRepository()->saveWithModifier($taxDefinitionSnapshot, $editorUserId);
        $this->makeTaxDefinitionRangesSnapshot($sourceTaxDefinition->Id, $taxDefinitionSnapshot->Id, $editorUserId);
        return $taxDefinitionSnapshot;
    }

    protected function bindTaxDefinitionToSchema(int $taxDefinitionId, int $taxSchemaId, int $editorUserId): void
    {
        $taxSchemaTaxDefinition = $this->createEntityFactory()->taxSchemaTaxDefinition();
        $taxSchemaTaxDefinition->TaxSchemaId = $taxSchemaId;
        $taxSchemaTaxDefinition->TaxDefinitionId = $taxDefinitionId;
        $this->getTaxSchemaTaxDefinitionWriteRepository()->saveWithModifier($taxSchemaTaxDefinition, $editorUserId);
    }

    protected function makeTaxDefinitionRangesSnapshot(int $sourceTaxDefinitionId, $targetTaxDefinitionId, int $editorUserId): void
    {
        $ranges = $this->createTaxDefinitionRangeLoader()->loadForTaxDefinition($sourceTaxDefinitionId);
        foreach ($ranges as $range) {
            $rangeSnapshot = $range->createSnapshot($targetTaxDefinitionId);
            $this->getTaxDefinitionRangeWriteRepository()->saveWithModifier($rangeSnapshot, $editorUserId);
        }
    }

    protected function makeTaxSchemaSnapshotNameForInvoiceItem(
        TaxSchema $sourceTaxSchema,
        int $invoiceId,
        int $lotItemId,
        string $language
    ): string {
        $name = $this->getAdminTranslator()->trans(
            'snapshot.schema.name.invoice_item.template',
            [
                'originalName' => $sourceTaxSchema->Name,
                'invoiceId' => $invoiceId,
                'lotItemId' => $lotItemId,
            ],
            'admin_stacked_tax',
            $language
        );
        $name = $this->createUniqueNameGenerator()->generateUniqueTaxSchemaName($name, $sourceTaxSchema->AccountId);
        return $name;
    }

    protected function makeTaxSchemaSnapshotNameForInvoiceServiceFee(
        TaxSchema $sourceTaxSchema,
        int $invoiceId,
        int $invoiceAdditionalId,
        string $language
    ): string {
        $name = $this->getAdminTranslator()->trans(
            'snapshot.schema.name.service_fee.template',
            [
                'originalName' => $sourceTaxSchema->Name,
                'invoiceId' => $invoiceId,
                'invoiceAdditionalId' => $invoiceAdditionalId,
            ],
            'admin_stacked_tax',
            $language
        );
        $name = $this->createUniqueNameGenerator()->generateUniqueTaxSchemaName($name, $sourceTaxSchema->AccountId);
        return $name;
    }

    protected function makeTaxDefinitionSnapshotNameForInvoiceItem(
        TaxDefinition $sourceTaxDefinition,
        int $sourceTaxSchemaId,
        int $invoiceId,
        int $lotItemId,
        string $language
    ): string {
        $name = $this->getAdminTranslator()->trans(
            'snapshot.definition.name.invoice_item.template',
            [
                'taxSchemaId' => $sourceTaxSchemaId,
                'originalName' => $sourceTaxDefinition->Name,
                'invoiceId' => $invoiceId,
                'lotItemId' => $lotItemId,
            ],
            'admin_stacked_tax',
            $language
        );
        $name = $this->createUniqueNameGenerator()->generateUniqueTaxDefinitionName($name, $sourceTaxDefinition->AccountId);
        return $name;
    }

    protected function makeTaxDefinitionSnapshotNameForInvoiceServiceFee(
        TaxDefinition $sourceTaxDefinition,
        int $sourceTaxSchemaId,
        int $invoiceId,
        int $invoiceAdditionalId,
        string $language,
    ): string {
        $name = $this->getAdminTranslator()->trans(
            'snapshot.definition.name.service_fee.template',
            [
                'taxSchemaId' => $sourceTaxSchemaId,
                'originalName' => $sourceTaxDefinition->Name,
                'invoiceId' => $invoiceId,
                'invoiceAdditionalId' => $invoiceAdditionalId,
            ],
            'admin_stacked_tax',
            $language
        );
        $name = $this->createUniqueNameGenerator()->generateUniqueTaxDefinitionName($name, $sourceTaxDefinition->AccountId);
        return $name;
    }
}
