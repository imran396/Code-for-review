<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LocationTaxSchema\LocationTaxSchemaReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class TaxSchemaDataLoader
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load
 */
class TaxSchemaDataLoader extends CustomizableClass
{
    use TaxSchemaReadRepositoryCreateTrait;
    use LocationTaxSchemaReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadForLocation(?int $locationId, int $accountId, bool $isReadOnlyDb = false): array
    {
        if (!$locationId) {
            return [];
        }
        $rows = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($accountId)
            ->joinLocationTaxSchemaFilterLocationId($locationId)
            ->joinLocationTaxSchemaFilterActive(true)
            ->select(['name', 'ts.id', 'ts.active', 'ts.source_tax_schema_id'])
            ->loadRows();
        return array_map(TaxSchemaDto::new()->fromDbRow(...), $rows);
    }

    public function loadAvailableTaxSchema(?int $accountId, array $skipTaxSchemaIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterSourceTaxSchemaId(null)
            ->skipId($skipTaxSchemaIds)
            ->select(['name', 'id', 'active', 'source_tax_schema_id'])
            ->loadRows();

        return array_map(TaxSchemaDto::new()->fromDbRow(...), $rows);
    }

    public function loadById(array $taxSchemaIds, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaIds) {
            return [];
        }
        $rows = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterSourceTaxSchemaId(null)
            ->filterId($taxSchemaIds)
            ->select(['id', 'name', 'active', 'source_tax_schema_id'])
            ->loadRows();
        $dtos = array_map(TaxSchemaDto::new()->fromDbRow(...), $rows);
        return $dtos;
    }

    public function checkDuplicate($locationId, $taxSchemaId, $isReadOnlyDb = false): bool
    {
        $count = $this->createLocationTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLocationId($locationId)
            ->filterTaxSchemaId($taxSchemaId)
            ->filterActive(true)
            ->count();
        return $count > 1;
    }

}
