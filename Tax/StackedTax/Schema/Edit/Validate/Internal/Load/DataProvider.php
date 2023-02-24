<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use TaxSchemaReadRepositoryCreateTrait;
    use TaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isTaxSchemaExists(int $taxSchemaId, bool $isReadOnlyDb = false): bool
    {
        return $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($taxSchemaId)
            ->exist();
    }

    public function isTaxSchemaNameUnique(string $name, ?int $skipTaxDefinitionId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $repository = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($accountId)
            ->filterName($name);
        if ($skipTaxDefinitionId) {
            $repository = $repository->skipId($skipTaxDefinitionId);
        }
        return !$repository->exist();
    }

    /**
     * @return TaxSchemaTaxDefinitionDto[]
     */
    public function loadTaxSchemaTaxDefinitions(array $ids, int $accountId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createTaxDefinitionReadRepository()
            ->filterActive(true)
            ->joinTaxSchemaTaxDefinition()
            ->filterId($ids)
            ->filterAccountId($accountId)
            ->select([
                'tdef.id as tax_definition_id',
                'tstd.id as tax_schema_tax_definition_id',
                'name',
                'country',
                'state',
                'county',
                'city',
                'geo_type',
            ])
            ->loadRows();
        $taxSchemaTaxDefinitionDtos = array_map(TaxSchemaTaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $taxSchemaTaxDefinitionDtos;
    }
}
