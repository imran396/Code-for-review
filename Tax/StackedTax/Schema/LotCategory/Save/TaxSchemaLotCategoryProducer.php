<?php
/**
 * SAM-10826: Stacked Tax. Lot categories (Stage-2)
 * SAM-12045: Stacked Tax - Stage 2: Lot categories: Lot Category and Location based tax schema detection
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\LotCategory\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxSchemaLotCategory\TaxSchemaLotCategoryWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\LotCategory\Delete\TaxSchemaLotCategoryDeleterCreateTrait;
use Sam\Tax\StackedTax\Schema\LotCategory\Load\TaxSchemaLotCategoryLoaderCreateTrait;

/**
 * Class TaxSchemaLotCategoryReferenceProducer
 * @package Sam\Tax\StackedTax\Schema\LotCategory\Save
 */
class TaxSchemaLotCategoryProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use TaxSchemaLotCategoryDeleterCreateTrait;
    use TaxSchemaLotCategoryLoaderCreateTrait;
    use TaxSchemaLotCategoryWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(int $taxSchemaId, array $lotCategoryIds, int $editorUserId): void
    {
        $taxSchemaLotCategoryLoader = $this->createTaxSchemaLotCategoryLoader();

        $existingLotCategoryIds = $taxSchemaLotCategoryLoader->loadLotCategoryIds($taxSchemaId);
        $addedLotCategoryIds = array_diff($lotCategoryIds, $existingLotCategoryIds);
        $this->bindLotCategories($taxSchemaId, $addedLotCategoryIds, $editorUserId);

        $deletedLotCategoryIds = array_diff($existingLotCategoryIds, $lotCategoryIds);
        $this->unbindLotCategories($taxSchemaId, $deletedLotCategoryIds, $editorUserId);
    }

    protected function bindLotCategories(int $taxSchemaId, array $addedLotCategoryIds, int $editorUserId): void
    {
        foreach ($addedLotCategoryIds as $lotCategoryId) {
            $taxSchemaLotCategory = $this->createEntityFactory()->taxSchemaLotCategory();
            $taxSchemaLotCategory->Active = true;
            $taxSchemaLotCategory->LotCategoryId = $lotCategoryId;
            $taxSchemaLotCategory->TaxSchemaId = $taxSchemaId;
            $this->getTaxSchemaLotCategoryWriteRepository()->saveWithModifier($taxSchemaLotCategory, $editorUserId);
        }
    }

    protected function unbindLotCategories(int $taxSchemaId, array $deletedLotCategoryIds, int $editorUserId): void
    {
        $this->createTaxSchemaLotCategoryDeleter()->removeTaxSchemaLotCategories($taxSchemaId, $deletedLotCategoryIds, $editorUserId);
    }
}
