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


namespace Sam\Location\TaxSchema\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\TaxSchema\Delete\LocationTaxSchemaDeleterCreateTrait;
use Sam\Location\TaxSchema\Load\DataProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\LocationTaxSchema\LocationTaxSchemaWriteRepositoryAwareTrait;

/**
 * Class LocationTaxSchemaProducer
 * @package Sam\Location\TaxSchema\Save
 */
class LocationTaxSchemaProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use EntityFactoryCreateTrait;
    use LocationTaxSchemaWriteRepositoryAwareTrait;
    use LocationTaxSchemaDeleterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(int $locationId, array $taxSchemaIds, int $editorUserId): void
    {
        $locationSchemaIds = $this->createDataProvider()->loadLocationTaxSchemaIds($locationId);
        $addedTaxSchemaIds = array_diff($taxSchemaIds, $locationSchemaIds);
        $this->bindTaxSchema($locationId, $addedTaxSchemaIds, $editorUserId);
        $deletedTaxSchemaIds = array_diff($locationSchemaIds, $taxSchemaIds);
        $this->unbindTaxSchema($locationId, $deletedTaxSchemaIds, $editorUserId);
    }

    protected function bindTaxSchema(int $locationId, array $addedTaxSchemaIds, int $editorUserId): void
    {
        foreach ($addedTaxSchemaIds as $taxSchemaId) {
            $locationTaxSchema = $this->createEntityFactory()->locationTaxSchema();
            $locationTaxSchema->LocationId = $locationId;
            $locationTaxSchema->TaxSchemaId = $taxSchemaId;
            $locationTaxSchema->Active = true;
            $this->getLocationTaxSchemaWriteRepository()->saveWithModifier($locationTaxSchema, $editorUserId);
        }
    }

    protected function unbindTaxSchema(int $locationId, array $deletedTaxSchemaIds, int $editorUserId): void
    {
        $deleter = $this->createLocationTaxSchemaDeleter();
        array_walk(
            $deletedTaxSchemaIds,
            static fn(int $taxSchemaId) => $deleter->removeLocationTaxSchema($locationId, $taxSchemaId, $editorUserId)
        );
    }
}
