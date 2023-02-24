<?php
/**
 * Help methods for Lot Category renderer
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Renderer;

use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;

/**
 * Class LotCategoryRenderer
 * @package Sam\Lot\Category\Renderer
 */
class LotCategoryRenderer extends CustomizableClass
{
    use LotCategoryOrdererAwareTrait;
    use LotCategoryLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get text of all category trees for lot, which nesting level may be limited by $maxLevel
     *
     * @param int $lotItemId
     * @param string $branchSeparator
     * @return string
     */
    public function getCategoriesText(int $lotItemId, string $branchSeparator = '; '): string
    {
        $categoryPath = '';
        $lotCategories = $this->getLotCategoryLoader()->loadForLot($lotItemId, true);
        if (count($lotCategories) > 0) {
            $branches = [];
            foreach ($lotCategories as $lotCategory) {
                $branches[] = $this->getCategoryTreeText($lotCategory);
            }
            $categoryPath = implode($branchSeparator, $branches);
        }
        return $categoryPath;
    }

    public function renderTaxSchemaCategoriesText(int $taxSchemaId, string $branchSeparator = '; '): string
    {
        $categoryPath = '';
        $lotCategories = $this->getLotCategoryLoader()->loadForTaxSchema($taxSchemaId, true);
        if (count($lotCategories) > 0) {
            $branches = [];
            foreach ($lotCategories as $lotCategory) {
                $branches[] = $this->getCategoryTreeText($lotCategory);
            }
            $categoryPath = implode($branchSeparator, $branches);
        }
        return $categoryPath;
    }

    /**
     * Return names of category and its ancestors
     *
     * @param LotCategory $lotCategory
     * @return string
     */
    public function getCategoryTreeText(LotCategory $lotCategory): string
    {
        $lotCategories = $this->getLotCategoryLoader()
            ->loadCategoryWithAncestors($lotCategory, true);
        $lotCategoryWithAncestors = array_reverse($lotCategories);
        $categoryTreeText = $this->buildCategoryTreeText($lotCategoryWithAncestors);
        return $categoryTreeText;
    }

    /**
     * @param LotCategory[] $lotCategoryWithAncestors
     * @return string
     */
    public function buildCategoryTreeText(array $lotCategoryWithAncestors): string
    {
        $maxLevel = $this->getLotCategoryOrderer()->getMaxLevel();
        $categoryPath = '';
        $level = 0;
        foreach ($lotCategoryWithAncestors as $lotCategory) {
            if ($level > $maxLevel) {
                break;
            }
            $categoryPath .= ee($lotCategory->Name) . ' > ';
            $level++;
        }
        $categoryTreeText = rtrim($categoryPath, ' > ');
        return $categoryTreeText;
    }
}
