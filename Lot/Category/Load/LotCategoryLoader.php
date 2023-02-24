<?php
/**
 * Help methods for Lot Category loading
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jav 8, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Load;

use LotCategory;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepository;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;

/**
 * Class LotCategoryLoader
 * @package Sam\Lot\Category\Load
 */
class LotCategoryLoader extends EntityLoaderBase
{
    use InvoiceItemLoaderAwareTrait;
    use LotCategoryOrdererAwareTrait;
    use LotCategoryReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load active LotCategory
     * @param int|null $lotCategoryId null results to null
     * @param bool $isReadOnlyDb
     * @return LotCategory|null
     */
    public function load(?int $lotCategoryId, bool $isReadOnlyDb = false): ?LotCategory
    {
        $lotCategoryId = Cast::toInt($lotCategoryId, Constants\Type::F_INT_POSITIVE);
        if (!$lotCategoryId) {
            return null;
        }
        $lotCategory = $this->prepareRepository($isReadOnlyDb)
            ->filterId($lotCategoryId)
            ->loadEntity();
        return $lotCategory;
    }

    /**
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return LotCategory|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?LotCategory
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        $fn = function () use ($name, $isReadOnlyDb) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterName($name)
                ->loadEntity();
        };

        $cacheKey = sprintf(Constants\MemoryCache::LOT_CATEGORY_NAME, $name);
        $lotCategory = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $lotCategory;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->orderBySiblingOrder()
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Load root (first level) categories for rendering cases.
     * Since it is rendering case, we can load from read-only DB and sorting in ascending direction by sibling order number.
     * @return array
     */
    public function loadAllRootForRender(): array
    {
        return $this->loadAllRootOrderBySibling(true, true);
    }

    public function loadAllRootOrderBySibling(bool $isAscending = true, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterParentId(null)
            ->orderBySiblingOrder($isAscending)
            ->loadEntities();
    }

    public function loadAllRootOrderByName(bool $isAscending = true, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterParentId(null)
            ->orderByName($isAscending)
            ->loadEntities();
    }

    /**
     * @param array $repoOptions
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadAllRoot(array $repoOptions = [], bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->filterParentId(null)
            ->applyOrderOptions($repoOptions)
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Load root (first level) category specific fields for rendering cases.
     * Since it is rendering case, we can load from read-only DB and sorting in ascending direction by sibling order number.
     * @param string[] $selected
     * @return array
     */
    public function loadSelectedAllRootForRender(array $selected): array
    {
        return $this->loadSelectedAllRootOrderBySibling($selected, true, true);
    }

    /**
     * @param string[] $selected
     * @param bool $isAscending
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedAllRootOrderBySibling(
        array $selected,
        bool $isAscending = true,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepository($isReadOnlyDb)
            ->select($selected)
            ->filterParentId(null)
            ->orderBySiblingOrder($isAscending)
            ->loadRows();
    }

    /**
     * Return all categories used in one particular auction
     *
     * @param int|null $auctionId auction.id null leads to empty array
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadAllInAuction(?int $auctionId = null, bool $isReadOnlyDb = false): array
    {
        if (!$auctionId) {
            return [];
        }
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->enableDistinct(true)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusIds(Constants\Lot::$availableLotStatuses)
            ->joinLotItemCategory()
            ->joinLotItemFilterActive(true)
            ->orderByGlobalOrder()
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Return all child categories used in the system
     *
     * @param int|null $parentId when null, then function would perform like if we would call loadAllRoot() with ordering by siblings
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadAllChildren(?int $parentId, bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->filterParentId($parentId)
            ->orderBySiblingOrder()
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Return categories for lot item
     *
     * @param int|null $lotItemId null leads to empty array
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadForLot(?int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->joinLotItemCategoryFilterLotItemId($lotItemId)
            ->orderByLotItemCategoryId()
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * @param int|null $lotItemId null leads to empty array
     * @param bool $isReadOnlyDb
     * @return string[]
     */
    public function loadNamesForLot(?int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->joinLotItemCategoryFilterLotItemId($lotItemId)
            ->orderByLotItemCategoryId()
            ->select(['lc.name'])
            ->loadRows();
        $names = array_column($rows, 'name');
        return $names;
    }

    /**
     * Return category ids for lot item
     *
     * @param int|null $lotItemId null leads to empty array
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadIdsForLot(?int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->joinLotItemCategoryFilterLotItemId($lotItemId)
            ->orderByLotItemCategoryId()
            ->select(['lc.id'])
            ->loadRows();
        $lotCategoryIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $lotCategoryIds;
    }

    /**
     * Return the first category for lot item
     *
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotCategory|null
     */
    public function loadFirstForLot(?int $lotItemId, bool $isReadOnlyDb = false): ?LotCategory
    {
        if (!$lotItemId) {
            return null;
        }
        $lotCategory = $this->prepareRepository($isReadOnlyDb)
            ->joinLotItemCategoryFilterLotItemId($lotItemId)
            ->orderByLotItemCategoryId()
            ->loadEntity();
        return $lotCategory;
    }


    /**
     * Return categories for Tax Schema
     *
     * @param int|null $taxSchemaId null leads to empty array
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadForTaxSchema(?int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->joinTaxSchemaLotCategoryFilterActive(true)
            ->joinTaxSchemaLotCategoryFilterTaxSchemaId($taxSchemaId)
            ->loadEntities();
    }

    public function loadLotCategoryIdsForTaxSchema(?int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }

        $rows = $this->prepareRepository($isReadOnlyDb)
            ->joinTaxSchemaLotCategoryFilterActive(true)
            ->joinTaxSchemaLotCategoryFilterTaxSchemaId($taxSchemaId)
            ->select(['lc.id'])
            ->loadRows();
        $lotCategoryIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $lotCategoryIds;
    }

    /**
     * Return categories loaded by ids
     *
     * @param int[] $lotCategoryIds
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadByIds(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        if (!$lotCategoryIds) {
            return [];
        }

        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->orderBySiblingOrder()
            ->filterId($lotCategoryIds)
            ->loadEntities();
        return $lotCategories;
    }

    public function loadSelectedByIds(array $select, array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->orderBySiblingOrder()
            ->filterId($lotCategoryIds)
            ->select($select)
            ->loadRows();
        return $rows;
    }

    /**
     * Return category with all level children in array keeping hierarchy.
     * Each category level is ordered by order number by default
     * Children are placed after parent in array.
     * If $lotCategoryId is null - all active categories returned (starting from root level).
     * Method could be used for category selection lists.
     *
     * @param int|null $lotCategoryId null for root
     * @param array $repoOptions
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadCategoryWithDescendants(?int $lotCategoryId = null, array $repoOptions = [], bool $isReadOnlyDb = false): array
    {
        $lotCategories = [];
        $parentCategories = [];
        if (!isset($lotCategoryId)) {
            $parentCategories = $this->loadAllRoot($repoOptions, $isReadOnlyDb);
        } else {
            $lotCategory = $this->load($lotCategoryId, $isReadOnlyDb);
            if ($lotCategory) {
                $lotCategories[] = $lotCategory;
                $parentCategories[] = $lotCategory;
            }
        }

        while (!empty($parentCategories)) {
            $parentCategoryIds = [];
            foreach ($parentCategories as $parentCategory) {
                $lotCategories = $this->placeCategoryAmongSiblings($lotCategories, $parentCategory);
                $parentCategoryIds[] = $parentCategory->Id;
            }
            if ($parentCategories[0]->Level < $this->getLotCategoryOrderer()->getMaxLevel()) {
                $parentCategories = $this->loadChildrenOfCategoryIds($parentCategoryIds, $repoOptions);
            } else {
                $parentCategories = null;
            }
        }
        return $lotCategories;
    }

    /**
     * Place category among sibling in array
     *
     * @param LotCategory[] $lotCategories
     * @param LotCategory $lotCategory
     * @param string|null $orderingType Possible values:
     *                             'name'  - category ordered by name (when we cannot sort categories before)
     *                             null    - category added after last sibling (supposed, categories were already ordered before passing by any conditions)
     * @return LotCategory[]
     */
    protected function placeCategoryAmongSiblings(array $lotCategories, LotCategory $lotCategory, ?string $orderingType = null): array
    {
        // find nearest sibling according to order option
        $nearestBroCategoryId = null;
        $nearestBroIndex = 0;
        foreach ($lotCategories as $index => $category) {
            $isOrderCheck = true;
            if ($orderingType === 'name') {
                $isOrderCheck = (mb_strtolower($category->Name) < mb_strtolower($lotCategory->Name));
            }
            if (
                $lotCategory->ParentId === $category->ParentId
                && $isOrderCheck
            ) {
                $nearestBroCategoryId = $category->Id;
                $nearestBroIndex = $index;
            }
        }

        // find category id after which input category must be placed
        if ($nearestBroCategoryId === null) {   // sibling not found, place after parent
            $placeAfterId = $lotCategory->ParentId;
        } else {
            // find last descendant for nearest sibling
            $lastBroDescendantId = null;
            for (
                $i = $nearestBroIndex + 1, $total = count($lotCategories);
                $i < $total && $lotCategories[$i]->Level > $lotCategories[$nearestBroIndex]->Level;
                $i++
            ) {
                $lastBroDescendantId = $lotCategories[$i]->Id;
            }
            $placeAfterId = $lastBroDescendantId ?? $nearestBroCategoryId;
        }

        // place into array
        if ($placeAfterId === null) {
            array_unshift($lotCategories, $lotCategory);
        } else {
            $resultCategories = [];
            foreach ($lotCategories as $category) {
                $resultCategories[] = $category;
                if ($placeAfterId === $category->Id) {
                    $resultCategories[] = $lotCategory;
                }
            }
            $lotCategories = $resultCategories;
        }

        $lotCategories = ArrayHelper::uniqueEntities($lotCategories, 'Id');

        return $lotCategories;
    }

    /**
     * Return all level child ids for passed categories together with passed categories
     * @param int[] $lotCategoryIds
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCategoryWithDescendantIds(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $lotCategoryIds = array_merge($lotCategoryIds, $this->loadDescendantIds($lotCategoryIds, $isReadOnlyDb));
        return array_unique($lotCategoryIds);
    }

    /**
     * @param int|null $lotCategoryId null for loading root level categories
     * @param array $options
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadChildrenOfCategoryId(?int $lotCategoryId, array $options = [], bool $isReadOnlyDb = false): array
    {
        return $this->loadChildrenOfCategoryIds([$lotCategoryId], $options, $isReadOnlyDb);
    }

    /**
     * @param int[] $lotCategoryIds
     * @param array $options
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadChildrenOfCategoryIds(array $lotCategoryIds, array $options = [], bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->prepareRepository($isReadOnlyDb)
            ->filterParentId($lotCategoryIds)
            ->applyOrderOptions($options)
            ->loadEntities();
        return $lotCategories;
    }

    /**
     * Return child category ids (one level descendants) for passed category ids
     * @param int|null $lotCategoryId null for loading root level categories.
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadChildrenIdsOfCategoryId(?int $lotCategoryId, bool $isReadOnlyDb = false): array
    {
        return $this->loadChildrenIdsOfCategoryIds([$lotCategoryId], $isReadOnlyDb);
    }

    /**
     * Return child category ids (one level descendants) for passed category ids
     * @param int[] $lotCategoryIds
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadChildrenIdsOfCategoryIds(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->filterParentId($lotCategoryIds)
            ->select(['lc.id'])
            ->loadRows();
        $childCategoryIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $childCategoryIds;
    }

    /**
     * Return array of passed category with its ancestors
     * Ordering: passed category - first, root category - last
     * @param LotCategory $lotCategory
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadCategoryWithAncestors(LotCategory $lotCategory, bool $isReadOnlyDb = false): array
    {
        $lotCategories = [$lotCategory];
        while ($lotCategory
            && $lotCategory->Id !== $lotCategory->ParentId
            && $lotCategory->ParentId > 0
        ) {
            $lotCategory = $this->load($lotCategory->ParentId, $isReadOnlyDb);
            if ($lotCategory) {
                $lotCategories[] = $lotCategory;
            }
        }
        return $lotCategories;
    }

    /**
     * Return array of passed category id with its ancestor ids
     * @param int|null $lotCategoryId null results with empty array
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCategoryWithAncestorIds(?int $lotCategoryId, bool $isReadOnlyDb = false): array
    {
        if (!$lotCategoryId) {
            return [];
        }
        $lotCategoryIds = [];
        do {
            $tmpLotCategoryId = $lotCategoryId;
            $lotCategoryIds[] = $lotCategoryId;
            $lotCategoryId = (int)$this->loadParentId($lotCategoryId, $isReadOnlyDb);
        } while ($tmpLotCategoryId !== $lotCategoryId && $lotCategoryId > 0);
        return $lotCategoryIds;
    }

    /**
     * Return parent id of category passed by id
     * @param int|null $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    protected function loadParentId(?int $lotCategoryId, bool $isReadOnlyDb = false): ?int
    {
        if (!$lotCategoryId) {
            return null;
        }

        $row = $this->prepareRepository($isReadOnlyDb)
            ->select(['parent_id'])
            ->filterId($lotCategoryId)
            ->loadRow();
        return Cast::toInt($row['parent_id'] ?? null);
    }

    /**
     * Return categories with ancestors in array keeping hierarchy for passed category(ies)
     * @param LotCategory|LotCategory[] $lotCategories
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function loadCategoryWithAncestorsForCategories(LotCategory|array $lotCategories, bool $isReadOnlyDb = false): array
    {
        $initialCategories = is_array($lotCategories) ? $lotCategories : [$lotCategories];
        $resultCategoryIds = [];
        $resultCategories = [];
        foreach ($initialCategories as $initialCategory) {
            $categoriesWithAncestors = array_reverse($this->loadCategoryWithAncestors($initialCategory, $isReadOnlyDb));
            foreach ($categoriesWithAncestors as $lotCategory) {
                if (!in_array($lotCategory->Id, $resultCategoryIds, true)) {
                    $resultCategoryIds[] = $lotCategory->Id;
                    $resultCategories = $this->placeCategoryAmongSiblings($resultCategories, $lotCategory);
                }
            }
        }
        return $resultCategories;
    }

    /**
     * Return category id with ancestors ids
     * @param LotCategory|LotCategory[] $lotCategories
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCategoryWithAncestorIdsForCategories(LotCategory|array $lotCategories, bool $isReadOnlyDb = false): array
    {
        $initialCategories = is_array($lotCategories) ? $lotCategories : [$lotCategories];
        $categoriesWithAncestors = $this->loadCategoryWithAncestorsForCategories($initialCategories, $isReadOnlyDb);
        $lotCategoryIds = [];
        foreach ($categoriesWithAncestors as $lotCategory) {
            $lotCategoryIds[] = $lotCategory->Id;
        }
        return $lotCategoryIds;
    }

    public function loadCategoryWithAncestorIdsForCategoryIds(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->loadByIds($lotCategoryIds, $isReadOnlyDb);
        return $this->loadCategoryWithAncestorIdsForCategories($lotCategories, $isReadOnlyDb);
    }

    /**
     * Return array of category ids with their ancestor ids for lot
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCategoryWithAncestorIdsForLot(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $lotCategories = $this->loadForLot($lotItemId, $isReadOnlyDb);
        return $this->loadCategoryWithAncestorIdsForCategories($lotCategories, $isReadOnlyDb);
    }

    /**
     * Return highest sibling_order number of descendants for the parent category lotCategoryId
     * @param int|null $lotCategoryId null results with null
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function loadChildHighestSiblingOrder(?int $lotCategoryId, bool $isReadOnlyDb = false): ?int
    {
        if (!$lotCategoryId) {
            return null;
        }
        $category = $this->prepareRepository($isReadOnlyDb)
            ->filterParentId($lotCategoryId)
            ->orderBySiblingOrder(false)
            ->loadEntity();
        $siblingOrder = $category->SiblingOrder ?? null;
        return $siblingOrder;
    }

    /**
     * Return all level child ids for passed categories
     * @param int[] $lotCategoryIds
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadDescendantIds(array $lotCategoryIds, bool $isReadOnlyDb = false): array
    {
        $resultCategoryIds = [];
        while (!empty($lotCategoryIds)) {
            $childIds = $this->loadChildrenIdsOfCategoryIds($lotCategoryIds, $isReadOnlyDb);
            $lotCategoryIds = [];
            if (!empty($childIds)) {
                foreach ($childIds as $childId) {
                    if (!in_array($childId, $resultCategoryIds, true)) {
                        $resultCategoryIds[] = $childId;
                        $lotCategoryIds[] = $childId;
                    }
                }
            }
        }
        return $resultCategoryIds;
    }

    /**
     * Pass head categories to get their with ancestors array,
     * where root category leads array (has "0" index), head category is placed in array tail
     * [
     *    0 => [<root category>, <category 1 level>, .. , <head category[0]>]
     *    1 => [<root category>, <category 1 level>, .. , <head category[1]>]
     * ]
     * @param LotCategory|LotCategory[] $lotCategories
     * @param bool $isReadOnlyDb
     * @return array<LotCategory[]>
     */
    public function loadBranches(LotCategory|array $lotCategories, bool $isReadOnlyDb = false): array
    {
        $lotCategories = is_array($lotCategories) ? $lotCategories : [$lotCategories];
        $branches = [];
        foreach ($lotCategories as $lotCategory) {
            $branches[] = [$lotCategory];
        }
        do {
            $parentIds = [];
            foreach ($lotCategories as $lotCategory) {
                if ($lotCategory->ParentId) {
                    $parentIds[] = $lotCategory->ParentId;
                }
            }
            $lotCategories = [];
            $parentIds = array_unique($parentIds);
            if ($parentIds) {
                $lotCategories = $this->loadByIds($parentIds, $isReadOnlyDb);
                foreach ($lotCategories as $lotCategory) {
                    foreach ($branches as &$branch) {
                        if ($branch[0]->ParentId === $lotCategory->Id) {
                            array_unshift($branch, $lotCategory);
                        }
                    }
                }
            }
        } while ($lotCategories);
        return $branches;
    }

    /**
     * Get category name by id
     *
     * @param int|null $lotCategoryId null results to empty string
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function getCategoryNameById(?int $lotCategoryId, bool $isReadOnlyDb = false): string
    {
        if (!$lotCategoryId) {
            return '';
        }
        $category = $this->prepareRepository($isReadOnlyDb)
            ->filterId($lotCategoryId)
            ->loadEntity();
        $categoryName = $category ? ee($category->Name) : '';
        return $categoryName;
    }

    /**
     * Return lot items quantity assigned to lot category
     *
     * @param array $lotCategoryIds
     * @param int|null $accountId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadAssignedLotItemsQuantity(array $lotCategoryIds, ?int $accountId, ?int $auctionId, bool $isReadOnlyDb = false): array
    {
        $repository = $this->prepareRepository($isReadOnlyDb)
            ->select(['lc.id', 'COUNT(*) as qty'])
            ->filterId($lotCategoryIds)
            ->groupById()
            ->joinLotItemFilterActive(true);
        if ($accountId !== null) {
            $repository->joinLotItemFilterAccountId($accountId);
        }
        if ($auctionId !== null) {
            $repository->joinAuctionLotItemFilterAuctionId($auctionId);
        }
        $rows = $repository->loadRows();

        $lotItemsQty = array_fill_keys($lotCategoryIds, 0);
        foreach ($rows as $row) {
            $lotItemsQty[(int)$row['id']] = (int)$row['qty'];
        }
        return $lotItemsQty;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotCategoryReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): LotCategoryReadRepository
    {
        $repo = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repo;
    }

    public function loadCategoryIdsWithAncestorsForInvoice(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $lotCategoryIds = [];
        $lotItemIdRows = $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(['ii.lot_item_id'], $invoiceId, $isReadOnlyDb);
        $lotCategoriesForAllLots = [];
        foreach ($lotItemIdRows as $row) {
            $lotCategoriesForLot = $this->loadForLot((int)$row['lot_item_id'], true);
            $lotCategoriesForAllLots = array_merge($lotCategoriesForLot, $lotCategoriesForAllLots);
        }
        $branches = $this->loadBranches($lotCategoriesForAllLots);
        foreach ($branches as $branch) {
            $ids = ArrayHelper::toArrayByProperty($branch, 'Id');
            $lotCategoryIds = array_merge($lotCategoryIds, $ids);
        }

        if (count($lotCategoryIds)) {
            $lotCategoryIds = array_unique($lotCategoryIds, SORT_NUMERIC);
        }

        return $lotCategoryIds;
    }
}
