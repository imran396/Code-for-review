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

namespace Sam\Tax\StackedTax\Schema\LotCategory\Load;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchemaLotCategory\TaxSchemaLotCategoryReadRepositoryCreateTrait;
use TaxSchemaLotCategory;

/**
 * Class TaxSchemaLotCategoryLoader
 * @package Sam\Tax\StackedTax\Schema\LotCategory\Load
 */
class TaxSchemaLotCategoryLoader extends CustomizableClass
{
    use TaxSchemaLotCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotCategoryIds(int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }

        $rows = $this->createTaxSchemaLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTaxSchemaId($taxSchemaId)
            ->select(['tslc.lot_category_id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($rows, 'lot_category_id');
    }

    public function loadByTaxSchemaIdAndLotCategoryId(
        int $taxSchemaId,
        int $lotCategoryId,
        bool $isReadOnlyDb = false
    ): ?TaxSchemaLotCategory {
        if (
            !$taxSchemaId
            || !$lotCategoryId
        ) {
            return null;
        }

        return $this->createTaxSchemaLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLotCategoryId($lotCategoryId)
            ->filterTaxSchemaId($taxSchemaId)
            ->loadEntity();
    }
}
