<?php
/**
 * LotItem existence checker
 *
 * SAM-4348: Lot validators
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\LotItemAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class LotItemExistenceChecker
 * @package Sam\Lot\Validate
 */
class LotItemExistenceChecker extends CustomizableClass
{
    use EntityMemoryCacheManagerAwareTrait;
    use LotItemAllFilterTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Check if lot item exist by id and account id
     * @param int $lotItemId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByIdAndAccountId(int $lotItemId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        if (
            !$lotItemId
            || !$accountId
        ) {
            return false;
        }

        $fn = function () use ($lotItemId, $accountId, $isReadOnlyDb) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterAccountId($accountId)
                ->filterId($lotItemId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::LOT_ITEM_ID, $lotItemId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }

    /**
     * Check if lot number exists for account, considering excluded lot item ids
     *
     * @param int $itemNum
     * @param string $itemNumExt
     * @param int $accountId
     * @param int[] $skipLotItemIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByItemNum(
        int $itemNum,
        string $itemNumExt,
        int $accountId,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterItemNum($itemNum)
            ->filterItemNumExt($itemNumExt)
            ->skipId($skipLotItemIds)
            ->exist();
        return $isFound;
    }
}
