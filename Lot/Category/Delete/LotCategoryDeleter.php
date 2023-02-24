<?php
/**
 * Help methods for deleting Lot Category
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 22, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Delete;

use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Lot\Category\CustomField\DataDeleterAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\DeleteRepository\Entity\LotItemCategory\LotItemCategoryDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotCategory\LotCategoryWriteRepositoryAwareTrait;

/**
 * Class LotCategoryDeleter
 * @package Sam\Lot\Category
 */
class LotCategoryDeleter extends CustomizableClass
{
    use CurrentDateTrait;
    use DataDeleterAwareTrait;
    use LotBuyerGroupAccessHelperAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryReadRepositoryCreateTrait;
    use LotCategoryWriteRepositoryAwareTrait;
    use LotItemCategoryDeleteRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete category and its children (marking lot_category.active=false)
     * @param int $lotCategoryId
     * @param int $editorUserId
     * @return void
     */
    public function deleteCategoryWithDescendants(int $lotCategoryId, int $editorUserId): void
    {
        $baseCategory = null;
        $lotCategoryIgnoreIds = [];
        $lotCategories = $this->getLotCategoryLoader()->loadCategoryWithDescendants($lotCategoryId);
        foreach ($lotCategories as $lotCategory) {
            $this->getDataDeleter()->deleteForCategoryId($lotCategory->Id, $editorUserId);
            $lotCategoryIgnoreIds[] = $lotCategory->Id;
            $lotCategory->Active = false;
            if ($lotCategory->SiblingOrder) {
                // sibling order can't be negative
                $lotCategory->SiblingOrder -= $lotCategory->SiblingOrder;
            }
            $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
            if ($lotCategory->Id === $lotCategoryId) {
                $baseCategory = $lotCategory;
            }
        }
        if ($baseCategory) {
            $this->modifyOrderOfHighSiblings($baseCategory, -1, $editorUserId, $lotCategoryIgnoreIds);
        }
    }

    /**
     * @param int $lotItemId
     * @return void
     */
    public function unassignAllFromLot(int $lotItemId): void
    {
        $this->createLotItemCategoryDeleteRepository()
            ->filterLotItemId($lotItemId)
            ->delete();
        $this->getLotBuyerGroupAccessHelper()->deleteLotCategoryBuyerGroupCacheData($lotItemId);
    }

    /**
     * Modify sibling_order numbers of high ordered category siblings with adding modification value
     *
     * @param LotCategory $lotCategory
     * @param int $modifyValue
     * @param int $editorUserId
     * @param int[] $lotCategoriesIgnoreIds
     * @return void
     */
    protected function modifyOrderOfHighSiblings(
        LotCategory $lotCategory,
        int $modifyValue,
        int $editorUserId,
        array $lotCategoriesIgnoreIds = []
    ): void {
        $repo = $this->createLotCategoryReadRepository()
            ->filterParentId($lotCategory->ParentId)
            ->filterActive(true);
        $repo->filterSiblingOrderGreater((int)$lotCategory->SiblingOrder);

        if (count($lotCategoriesIgnoreIds)) {
            $repo = $repo->skipId($lotCategoriesIgnoreIds);
        }

        $categories = $repo->loadEntities();

        foreach ($categories as $category) {
            $category->SiblingOrder += $modifyValue;
            $this->getLotCategoryWriteRepository()->saveWithModifier($category, $editorUserId);
        }
    }
}
