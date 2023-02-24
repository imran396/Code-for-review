<?php
/**
 * Help methods for management all categories together
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

namespace Sam\Lot\Category\Tree;

use LotCategory;
use Sam\Core\Constants;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class LotCategoryFullTreeManager
 * @package Sam\Lot\Category\Tree
 */
class LotCategoryFullTreeManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FilesystemCacheManagerAwareTrait;
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
     * Get array of all categories with descendant from cache
     * Or build cache, if it doesn't exist
     *
     * @return LotCategory[]
     */
    public function load(): array
    {
        $lotCategories = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('lot-categories')
            ->get(Constants\MemoryCache::LOT_CATEGORY_ALL);
        if ($lotCategories) {
            log_trace('All Lot Category Tree unserialize data from cache');
        } else {
            $lotCategories = $this->saveCached();
        }
        return $lotCategories;
    }

    /**
     * Delete cache of all categories tree
     *
     * @return void
     */
    public function deleteCache(): void
    {
        $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('lot-categories')
            ->delete(Constants\MemoryCache::LOT_CATEGORY_ALL);
    }

    /**
     * Save in cache all categories tree
     *
     * @return LotCategory[]
     */
    protected function saveCached(): array
    {
        $option = ['order' => ['SiblingOrder' => true]];
        $lotCategories = $this->getLotCategoryLoader()->loadCategoryWithDescendants(null, $option, true);
        $cacheTime = $this->cfg()->get('core->lot->category->fullTreeCacheLifetime') * 60;
        $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('lot-categories')
            ->set(Constants\MemoryCache::LOT_CATEGORY_ALL, $lotCategories, $cacheTime);
        log_trace('All Lot Category Tree saved in cache');
        return $lotCategories;
    }
}
