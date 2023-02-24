<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchemaTaxDefinition\TaxSchemaTaxDefinitionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\TaxSchema\TaxSchemaWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TaxSchemaTaxDefinition\TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;

/**
 * Class TaxSchemaDeleter
 * @package Sam\Tax\StackedTax\Schema\Delete
 */
class TaxSchemaDeleter extends CustomizableClass
{
    use TaxSchemaLoaderCreateTrait;
    use TaxSchemaTaxDefinitionReadRepositoryCreateTrait;
    use TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;
    use TaxSchemaWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $taxSchemaId, int $editorUserId): void
    {
        $taxSchema = $this->createTaxSchemaLoader()->load($taxSchemaId);
        if (!$taxSchema) {
            log_error("Available tax schema not found" . composeSuffix(['id' => $taxSchemaId]));
            return;
        }
        $taxSchema->toSoftDeleted();
        $this->getTaxSchemaWriteRepository()->saveWithModifier($taxSchema, $editorUserId);
    }

    public function removeTaxSchemaTaxDefinition(int $taxSchemaId, int $taxDefinitionId, int $editorUserId): void
    {
        $taxSchemaTaxDefinition = $this->createTaxSchemaTaxDefinitionReadRepository()
            ->filterActive(true)
            ->filterTaxSchemaId($taxSchemaId)
            ->filterTaxDefinitionId($taxDefinitionId)
            ->loadEntity();
        if (!$taxSchemaTaxDefinition) {
            log_error(
                "Available tax schema tax definition not found" .
                composeSuffix(['tax schema' => $taxSchemaId, 'tax definition' => $taxDefinitionId])
            );
            return;
        }
        $taxSchemaTaxDefinition->toSoftDeleted();
        $this->getTaxSchemaTaxDefinitionWriteRepository()->saveWithModifier($taxSchemaTaxDefinition, $editorUserId);
    }
}
