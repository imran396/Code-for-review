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

namespace Sam\Tax\StackedTax\Schema\Edit\Save\Internal\Load;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchemaTaxDefinition\TaxSchemaTaxDefinitionReadRepositoryCreateTrait;
use Sam\Tax\StackedTax\Schema\Exception\CouldNotFindTaxSchema;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use TaxSchema;

/**
 * Class DataProvider
 * @package Sam\Tax\StackedTax\Schema\Edit\Save\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use TaxSchemaLoaderCreateTrait;
    use TaxSchemaTaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadTaxSchema(int $taxSchemaId, bool $isReadOnlyDb = false): TaxSchema
    {
        $taxSchema = $this->createTaxSchemaLoader()->load($taxSchemaId, $isReadOnlyDb);
        if (!$taxSchema) {
            throw CouldNotFindTaxSchema::withId($taxSchemaId);
        }
        return $taxSchema;
    }

    public function loadTaxDefinitionIds(int $schemaId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createTaxSchemaTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTaxSchemaId($schemaId)
            ->select(['tax_definition_id'])
            ->loadRows();
        $ids = ArrayHelper::flattenArray($rows);
        $ids = ArrayCast::castInt($ids);
        return $ids;
    }

    public function newTaxSchema(): TaxSchema
    {
        return $this->createEntityFactory()->taxSchema();
    }
}
