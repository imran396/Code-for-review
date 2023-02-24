<?php
/**
 * This helper service can append category array with parent categories,
 * and remove parent categories from the array of categories.
 *
 * SAM-10826: Stacked Tax. Lot categories (Stage-2)
 * SAM-12045: Stacked Tax - Stage 2: Lot categories: Lot Category and Location based tax schema detection
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\LotCategory\EffectiveCategory\Detect;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class TaxSchemaEffectiveLotCategoryDetector
 * @package Sam\Tax\StackedTax\Schema\LotCategory\EffectiveCategory\Detect
 */
class TaxSchemaEffectiveLotCategoryDetector extends CustomizableClass
{
    use LotCategoryLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Exclude parent directories from lot category ids array
     */
    public function excludeParents(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $lotCategoryRows = $this->getLotCategoryLoader()->loadSelectedByIds(
            ['lc.parent_id'],
            $lotCategoryIds,
            $isReadOnlyDb
        );
        $parentLotCategoryIds = ArrayCast::arrayColumnInt($lotCategoryRows, 'parent_id');

        $effectiveLotCategoryIds = [];
        foreach ($lotCategoryIds as $lotCategoryId) {
            if (in_array($lotCategoryId, $parentLotCategoryIds, true)) {
                continue;
            }
            $effectiveLotCategoryIds[] = $lotCategoryId;
        }
        return $effectiveLotCategoryIds;
    }

    /**
     * Enrich lot category ids array with parent lot category ids
     */
    public function includeParents(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        return $this->getLotCategoryLoader()
            ->loadCategoryWithAncestorIdsForCategoryIds($lotCategoryIds, $isReadOnlyDb);
    }
}
