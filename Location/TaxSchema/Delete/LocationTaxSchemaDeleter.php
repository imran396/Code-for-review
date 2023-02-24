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

namespace Sam\Location\TaxSchema\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Location\TaxSchema\Load\DataProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LocationTaxSchema\LocationTaxSchemaReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LocationTaxSchema\LocationTaxSchemaWriteRepositoryAwareTrait;

/**
 * Class TaxSchemaDeleter
 * @package Sam\Location\TaxSchema\Delete
 */
class LocationTaxSchemaDeleter extends CustomizableClass
{
    use DataProviderCreateTrait;
    use LocationTaxSchemaReadRepositoryCreateTrait;
    use LocationTaxSchemaWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function removeLocationTaxSchema(int $locationId, int $taxSchemaId, int $editorUserId): void
    {
        $locationTaxSchema = $this->createLocationTaxSchemaReadRepository()
            ->filterActive(true)
            ->filterLocationId($locationId)
            ->filterTaxSchemaId($taxSchemaId)
            ->loadEntity();

        if (!$locationTaxSchema) {
            log_error(
                "Available location tax schema not found" .
                composeSuffix(['tax schema' => $taxSchemaId, 'location id' => $locationId])
            );
            return;
        }
        $locationTaxSchema->toSoftDeleted();
        $this->getLocationTaxSchemaWriteRepository()->saveWithModifier($locationTaxSchema, $editorUserId);
    }

    public function deleteByLocationId(int $locationId, int $editorUserId): void
    {
        $taxSchemaIds = $this->createDataProvider()->loadLocationTaxSchemaIds($locationId);
        foreach ($taxSchemaIds as $taxSchemaId) {
            $this->removeLocationTaxSchema($locationId, $taxSchemaId, $editorUserId);
        }
    }
}
