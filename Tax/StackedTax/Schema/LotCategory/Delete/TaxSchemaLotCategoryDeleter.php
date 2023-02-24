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

namespace Sam\Tax\StackedTax\Schema\LotCategory\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxSchemaLotCategory\TaxSchemaLotCategoryWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\LotCategory\Load\TaxSchemaLotCategoryLoaderCreateTrait;

/**
 * Class TaxSchemaLotCategoryDeleter
 * @package Sam\Tax\StackedTax\Schema\LotCategory\Delete
 */
class TaxSchemaLotCategoryDeleter extends CustomizableClass
{
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

    public function removeTaxSchemaLotCategory(int $taxSchemaId, int $lotCategoryId, int $editorUserId): void
    {
        $taxSchemaLotCategory = $this->createTaxSchemaLotCategoryLoader()
            ->loadByTaxSchemaIdAndLotCategoryId($taxSchemaId, $lotCategoryId);
        if (!$taxSchemaLotCategory) {
            log_error(
                "Available tax schema lot category not found"
                . composeSuffix(['ts' => $taxSchemaId, 'lc' => $lotCategoryId])
            );
            return;
        }

        $taxSchemaLotCategory->toSoftDeleted();
        $this->getTaxSchemaLotCategoryWriteRepository()->saveWithModifier($taxSchemaLotCategory, $editorUserId);
    }

    public function removeTaxSchemaLotCategories(int $taxSchemaId, array $lotCategoryIds, int $editorUserId): void
    {
        foreach ($lotCategoryIds as $lotCategoryId) {
            $this->removeTaxSchemaLotCategory($taxSchemaId, $lotCategoryId, $editorUserId);
        }
    }
}
