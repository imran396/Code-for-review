<?php
/**
 * SAM-3621: category filtering in advanced search results
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Tree;


use LotCategory;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class LotCategoryTreeLotsQuantityManager
 * @package Sam\Lot\Category\Tree
 */
class LotCategoryTreeLotsQuantityManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotCategoryLoaderAwareTrait;

    protected const CACHE_KEY = 'categories-lots-quantity';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->getFilesystemCacheManager()
            ->setExtension('php')
            ->setNamespace('categories-lots-quantity');
        return $this;
    }

    /**
     * Count assigned to category lots for account
     *
     * @param int $lotCategoryId
     * @param int|null $accountId If null - count for all accounts
     * @param int|null $auctionId If null - count for all auctions
     * @return int
     */
    public function count(int $lotCategoryId, ?int $accountId = null, ?int $auctionId = null): int
    {
        $categoriesLotsQuantity = $this->countForAllCategories($accountId, $auctionId);
        return $categoriesLotsQuantity[$lotCategoryId] ?? 0;
    }

    /**
     * Count assigned to all categories lots for account
     *
     * @param int|null $accountId
     * @param int|null $auctionId
     * @return array
     */
    public function countForAllCategories(?int $accountId = null, ?int $auctionId = null): array
    {
        $lotsQty = $this->getFromCache($accountId, $auctionId);
        if (!$lotsQty) {
            $lotsQty = $this->calcForAllCategoriesWithDescendants($accountId, $auctionId);
            $this->setToCache($lotsQty, $accountId, $auctionId);
        }
        return $lotsQty;
    }

    public function clearCache(): void
    {
        $this->getFilesystemCacheManager()->clear();
    }

    /**
     * @param int|null $accountId
     * @param int|null $auctionId
     * @return array
     */
    protected function calcForAllCategoriesWithDescendants(?int $accountId = null, ?int $auctionId = null): array
    {
        $categories = $this->loadSortedByLevelCategories();
        $lotCategoryIds = array_keys($categories);
        $assignedLotItemsQty = $this->getLotCategoryLoader()->loadAssignedLotItemsQuantity($lotCategoryIds, $accountId, $auctionId);
        foreach ($categories as $category) {
            if ($category->ParentId !== null) {
                if (!array_key_exists($category->ParentId, $assignedLotItemsQty)) {
                    log_warning("Data inconsistency. Category with id {$category->Id} has a deleted parent category");
                    continue;
                }
                $assignedLotItemsQty[$category->ParentId] += $assignedLotItemsQty[$category->Id];
            }
        }
        return $assignedLotItemsQty;
    }

    /**
     * @return LotCategory[]
     */
    protected function loadSortedByLevelCategories(): array
    {
        $categories = $this->getLotCategoryLoader()->loadAll(true);
        $categories = ArrayHelper::indexEntities($categories, 'Id');
        uasort(
            $categories,
            static function (LotCategory $left, LotCategory $right) {
                return $right->Level - $left->Level;
            }
        );
        return $categories;
    }

    /**
     * @param int|null $accountId
     * @param int|null $auctionId
     * @return array|null
     */
    protected function getFromCache(?int $accountId, ?int $auctionId): ?array
    {
        $cacheKey = $this->makeCacheKey($accountId, $auctionId);
        return $this->getFilesystemCacheManager()->get($cacheKey);
    }

    /**
     * @param array $categoriesLotsQty
     * @param int|null $accountId
     * @param int|null $auctionId
     * @return bool
     */
    protected function setToCache(array $categoriesLotsQty, ?int $accountId, ?int $auctionId): bool
    {
        $cacheTime = $this->cfg()->get('core->lot->category->lotQuantityCacheLifetime') * 60;
        $cacheKey = $this->makeCacheKey($accountId, $auctionId);
        return $this->getFilesystemCacheManager()->set($cacheKey, $categoriesLotsQty, $cacheTime);
    }

    /**
     * @param int|null $accountId
     * @return string
     */
    protected function makeCacheKey(?int $accountId, ?int $auctionId): string
    {
        return self::CACHE_KEY . '-' . ($accountId ?? 'all') . '-' . ($auctionId ?? 'all');
    }
}
