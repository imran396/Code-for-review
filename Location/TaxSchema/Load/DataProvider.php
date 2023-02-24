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


namespace Sam\Location\TaxSchema\Load;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LocationTaxSchema\LocationTaxSchemaReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Location\TaxSchema\Load;
 */
class DataProvider extends CustomizableClass
{
    use LocationTaxSchemaReadRepositoryCreateTrait;


    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLocationTaxSchemaIds(int $locationId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createLocationTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLocationId($locationId)
            ->select(['tax_schema_id'])
            ->loadRows();
        $taxSchemaIds = ArrayHelper::flattenArray($rows);
        return ArrayCast::castInt($taxSchemaIds);
    }
}
