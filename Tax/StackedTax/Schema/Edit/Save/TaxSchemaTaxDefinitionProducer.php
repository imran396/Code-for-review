<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxSchemaTaxDefinition\TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Delete\TaxSchemaDeleterCreateTrait;
use Sam\Tax\StackedTax\Schema\Edit\Save\Internal\Load\DataProviderCreateTrait;

/**
 * Class TaxSchemaTaxDefinitionProducer
 * @package Sam\Tax\StackedTax\Schema\Edit\Save
 */
class TaxSchemaTaxDefinitionProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use EntityFactoryCreateTrait;
    use TaxSchemaDeleterCreateTrait;
    use TaxSchemaTaxDefinitionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(int $taxSchemaId, array $taxDefinitionIds, int $editorUserId): void
    {
        $schemaTaxDefinitionIds = $this->createDataProvider()->loadTaxDefinitionIds($taxSchemaId);
        $addedTaxDefinitionIds = array_diff($taxDefinitionIds, $schemaTaxDefinitionIds);
        $this->bindTaxDefinitions($taxSchemaId, $addedTaxDefinitionIds, $editorUserId);
        $deletedTaxDefinitionIds = array_diff($schemaTaxDefinitionIds, $taxDefinitionIds);
        $this->unbindTaxDefinitions($taxSchemaId, $deletedTaxDefinitionIds, $editorUserId);
    }

    protected function bindTaxDefinitions(int $taxSchemaId, array $taxDefinitionIds, int $editorUserId): void
    {
        foreach ($taxDefinitionIds as $taxDefinitionId) {
            $taxSchemaTaxDefinition = $this->createEntityFactory()->taxSchemaTaxDefinition();
            $taxSchemaTaxDefinition->TaxSchemaId = $taxSchemaId;
            $taxSchemaTaxDefinition->TaxDefinitionId = $taxDefinitionId;
            $taxSchemaTaxDefinition->Active = true;
            $this->getTaxSchemaTaxDefinitionWriteRepository()->saveWithModifier($taxSchemaTaxDefinition, $editorUserId);
        }
    }

    protected function unbindTaxDefinitions(int $taxSchemaId, array $taxDefinitionIds, int $editorUserId): void
    {
        $deleter = $this->createTaxSchemaDeleter();
        array_walk(
            $taxDefinitionIds,
            static fn(int $taxDefinitionId) => $deleter->removeTaxSchemaTaxDefinition($taxSchemaId, $taxDefinitionId, $editorUserId)
        );
    }
}
