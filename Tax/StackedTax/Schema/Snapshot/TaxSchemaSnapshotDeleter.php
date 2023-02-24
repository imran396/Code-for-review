<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Delete\TaxDefinitionDeleterCreateTrait;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Delete\TaxSchemaDeleterCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;

/**
 * Class TaxSchemaSnapshotDeleter
 * @package Sam\Tax\StackedTax\Schema\Snapshot
 */
class TaxSchemaSnapshotDeleter extends CustomizableClass
{
    use TaxDefinitionDeleterCreateTrait;
    use TaxDefinitionLoaderCreateTrait;
    use TaxSchemaDeleterCreateTrait;
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $snapshotTaxSchemaId, int $editorUserId): void
    {
        $snapshotTaxSchema = $this->createTaxSchemaLoader()->load($snapshotTaxSchemaId);
        if (!$snapshotTaxSchema) {
            log_warning("Tax schema snapshot with id {$snapshotTaxSchemaId} doesn't exist");
            return;
        }
        if (!$snapshotTaxSchema->isSnapshot()) {
            log_warning("Tax schema with id {$snapshotTaxSchemaId} is not a snapshot");
            return;
        }

        $taxDefinitionDeleter = $this->createTaxDefinitionDeleter();
        $snapshotTaxDefinitions = $this->createTaxDefinitionLoader()->loadForTaxSchema($snapshotTaxSchemaId);
        foreach ($snapshotTaxDefinitions as $snapshotTaxDefinition) {
            $taxDefinitionDeleter->delete($snapshotTaxDefinition->Id, $editorUserId);
        }

        $this->createTaxSchemaDeleter()->delete($snapshotTaxSchemaId, $editorUserId);
    }
}
