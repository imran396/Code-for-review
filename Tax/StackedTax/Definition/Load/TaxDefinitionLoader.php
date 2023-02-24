<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;
use TaxDefinition;

/**
 * Class TaxDefinitionLoader
 * @package Sam\Tax\StackedTax\Definition\Load
 */
class TaxDefinitionLoader extends CustomizableClass
{
    use TaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(?int $taxDefinitionId, bool $isReadOnlyDb = false): ?TaxDefinition
    {
        if (!$taxDefinitionId) {
            return null;
        }

        return $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($taxDefinitionId)
            ->loadEntity();
    }

    public function loadLastSnapshotByName(string $name, ?int $accountId = null, bool $isReadOnlyDb = false): ?TaxDefinition
    {
        return $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($accountId)
            ->likeName($name)
            ->orderById(false)
            ->loadEntity();
    }

    /**
     * @return TaxDefinition[]
     */
    public function loadForTaxSchema(?int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }
        return $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->joinTaxSchemaTaxDefinitionFilterActive(true)
            ->joinTaxSchemaTaxDefinitionFilterTaxSchemaId($taxSchemaId)
            ->loadEntities();
    }
}
