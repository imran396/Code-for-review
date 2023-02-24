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

namespace Sam\View\Admin\Form\TaxSchemaEditForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;

/**
 * Class TaxDefinitionDataLoader
 * @package Sam\View\Admin\Form\TaxSchemaEditForm\Load
 */
class TaxDefinitionDataLoader extends CustomizableClass
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

    /**
     * @param int[] $ids
     * @param bool $isReadOnlyDb
     * @return TaxDefinitionDto[]
     */
    public function loadById(array $ids, bool $isReadOnlyDb = false): array
    {
        if (!$ids) {
            return [];
        }
        $rows = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($ids)
            ->orderByGeoType()
            ->orderByTaxType()
            ->select(['id', 'name', 'geo_type', 'tax_type', 'country'])
            ->loadRows();
        $dtos = array_map(TaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $dtos;
    }

    public function loadForTaxSchema(?int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }
        $rows = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->joinTaxSchemaTaxDefinitionFilterTaxSchemaId($taxSchemaId)
            ->joinTaxSchemaTaxDefinitionFilterActive(true)
            ->orderByGeoType()
            ->orderByTaxType()
            ->select(['tdef.id', 'name', 'geo_type', 'tax_type', 'country'])
            ->loadRows();
        $dtos = array_map(TaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $dtos;
    }
}
