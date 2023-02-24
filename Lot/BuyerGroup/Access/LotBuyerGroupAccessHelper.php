<?php
/**
 * SAM-4439 : Move lot's buyer group logic to Sam\Lot\BuyerGroup namespace
 * https://bidpath.atlassian.net/browse/SAM-4439
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Access;

use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup\LotCategoryBuyerGroupReadRepositoryCreateTrait;

/**
 * Class LotBuyerGroupAccessHelper
 * @package Sam\Lot\BuyerGroup\Access
 */
class LotBuyerGroupAccessHelper extends CustomizableClass
{
    use BuyerGroupReadRepositoryCreateTrait;
    use BuyerGroupUserReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotCategoryBuyerGroupReadRepositoryCreateTrait;
    use LotCategoryLoaderAwareTrait;
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
     * Check, if user is restricted by buyer group
     * @param int|null $userId
     * @param int|null $lotItemId null leads to false
     * @return bool
     */
    public function isRestrictedBuyerGroup(?int $userId, ?int $lotItemId): bool
    {
        if (!$lotItemId) {
            log_debug('Cannot determine buyer group restriction for absent lot item');
            return false;
        }

        $hasBuyerGroupMemCacheKey = 'hasBuyersGroup'; // extend by item account id if we ever extend this to not just be main account only
        $hasBuyerGroupMemCacheTtl = 5 * 60;

        // Are there any buyer groups in the first place?
        if ($this->getMemoryCacheManager()->has($hasBuyerGroupMemCacheKey)) {
            if ($this->getMemoryCacheManager()->get($hasBuyerGroupMemCacheKey) === false) {
                return false;
            }
        } else {
            // First check, if there are buyer groups at all before querying categories
            $hasBuyerGroup = $this->createBuyerGroupReadRepository()
                ->filterActive(true)
                ->exist();
            $this->getMemoryCacheManager()
                ->set($hasBuyerGroupMemCacheKey, $hasBuyerGroup, $hasBuyerGroupMemCacheTtl);
            if (!$hasBuyerGroup) {
                return false;
            }
        }

        $lotCategoryIds = $this->loadLotCategoryForBuyerGroup($lotItemId);
        log_trace(composeSuffix(['li' => $lotItemId, 'lc' => $lotCategoryIds]));
        if (count($lotCategoryIds) > 0) {
            $rows = $this->createLotCategoryBuyerGroupReadRepository()
                ->filterActive(true)
                ->filterLotCategoryId($lotCategoryIds)
                ->joinBuyerGroupFilterActive(true)
                ->select(['DISTINCT(lcbg.buyer_group_id)'])
                ->loadRows();
            $buyerGroupIds = ArrayCast::arrayColumnInt($rows, 'buyer_group_id');
            log_trace('Get lot category buyer group restriction: lcbg.lot_category_id IN (' . implode(',', $lotCategoryIds) . ')');
            if (count($buyerGroupIds) > 0) { // Has buyer group restriction
                if (!$userId) {
                    return true;
                }
                $isFound = $this->createBuyerGroupUserReadRepository()
                    ->filterActive(true)
                    ->filterBuyerGroupId($buyerGroupIds)
                    ->filterUserId($userId)
                    ->exist();
                log_trace('Check user if member of the buyer group allowed' . composeSuffix(['u' => $userId]));
                return !$isFound;
            }
        }
        return false;
    }

    /**
     * Returns unique active buyer group ids from lot item.
     *
     * @param int $lotItemId
     * @return int[]
     */
    public function loadBuyerGroupIds(int $lotItemId): array
    {
        $lotCategoryIds = $this->loadLotCategoryForBuyerGroup($lotItemId);
        log_trace(composeSuffix(['li' => $lotItemId, 'lc' => $lotCategoryIds]));
        $buyerGroupIds = [];
        if (count($lotCategoryIds) > 0) {
            $rows = $this->createLotCategoryBuyerGroupReadRepository()
                ->filterActive(true)
                ->filterLotCategoryId($lotCategoryIds)
                ->joinBuyerGroupFilterActive(true)
                ->select(['DISTINCT(lcbg.buyer_group_id)'])
                ->loadRows();
            $buyerGroupIds = ArrayCast::arrayColumnInt($rows, 'buyer_group_id');
            log_trace('Get lot category buyer group restriction: lcbg.lot_category_id IN (' . implode(',', $lotCategoryIds) . ')');
        }
        return $buyerGroupIds;
    }

    /**
     * Removes cache data by using namespace and cache key for specific lot item.
     *
     * @param int $lotItemId
     * @return bool
     */
    public function deleteLotCategoryBuyerGroupCacheData(int $lotItemId): bool
    {
        $isDeleted = $this->prepareCacheManager()
            ->delete($this->getCacheKey($lotItemId));
        return $isDeleted;
    }

    /**
     * Returns lot categories id if cache data available then from cache otherwise from databases and save in cache.
     *
     * @param int $lotItemId
     * @return int[]
     */
    public function loadLotCategoryForBuyerGroup(int $lotItemId): array
    {
        $lotCategoryIds = null;
        $cacheKey = $this->getCacheKey($lotItemId);
        $cacheManager = $this->prepareCacheManager();
        $isCached = $cacheManager->has($cacheKey);
        $cacheMTime = $this->prepareCacheManager()->getFileMtime($cacheKey);
        if ($isCached && $cacheMTime) {
            if (time() > $cacheMTime + $this->cfg()->get('core->lot->category->fullTreeCacheLifetime') * 60) {
                $this->deleteLotCategoryBuyerGroupCacheData($lotItemId);
            } else {
                $lotCategoryIds = $cacheManager->get($cacheKey);
            }
        }

        // categories are not fetched from cache - build array and save in cache
        if ($lotCategoryIds === null) {
            $lotCategoryIds = $this->getLotCategoryLoader()->loadCategoryWithAncestorIdsForLot($lotItemId);
            $cacheManager->set($cacheKey, $lotCategoryIds);
        }
        return $lotCategoryIds;
    }

    /**
     * @param int $lotItemId
     * @return string
     */
    protected function getCacheKey(int $lotItemId): string
    {
        return 'BuyerGroup-LotCategoryIds-LotItemId-' . $lotItemId;
    }

    /**
     * @return FilesystemCacheManager
     */
    protected function prepareCacheManager(): FilesystemCacheManager
    {
        return $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('lot-categories-buyer-group');
    }
}
