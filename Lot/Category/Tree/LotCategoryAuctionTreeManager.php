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
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class LotCategoryAuctionTreeManager
 * @package Sam\Lot\Category\Tree
 */
class LotCategoryAuctionTreeManager extends CustomizableClass
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
     * Categories with ancestors array used for auction lots (keeping hierarchy)
     * Data cached for each auction
     *
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotCategory[]
     */
    public function load(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('auction-lot-category');
        $lotCategories = $cacheManager->get((string)$auctionId);
        if ($lotCategories) {
            log_trace('Auction Lot Category Tree fetch data from cache' . composeSuffix(['a' => $auctionId]));
        } else {
            log_trace('Auction Lot Category Tree fetch from database' . composeSuffix(['a' => $auctionId]));
            $lotCategories = $this->getLotCategoryLoader()->loadAllInAuction($auctionId, $isReadOnlyDb);
            $lotCategories = $this->getLotCategoryLoader()->loadCategoryWithAncestorsForCategories($lotCategories, $isReadOnlyDb);
            $cacheTime = $this->cfg()->get('core->lot->category->auctionTreeCacheLifetime') * 60;
            $cacheManager->set((string)$auctionId, $lotCategories, $cacheTime);
            log_trace('Auction Lot Category Tree save in cache' . composeSuffix(['a' => $auctionId]));
        }
        return $lotCategories;
    }

    /**
     * Delete cache of all auction lot categories tree
     * @param int $auctionId
     * @return void
     */
    public function deleteCache(int $auctionId): void
    {
        $this->getFilesystemCacheManager()
            ->setNamespace('auction-lot-category')
            ->setExtension('txt')
            ->delete((string)$auctionId);
    }
}
