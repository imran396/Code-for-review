<?php
/**
 * Help methods for Lot loading
 *
 * SAM-4021: Lot Loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 8, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Load;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Filter\EntityLoader\LotItemAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepositoryCreateTrait;

/**
 * Class LotItemLoader
 * @package Sam\Lot\Load
 */
class LotItemLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use LotItemAllFilterTrait;
    use LotItemCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
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
     * Load Lot Item
     * @param int|null $lotItemId null results with null
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function load(?int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        if (!$lotItemId) {
            return null;
        }

        $fn = function () use ($lotItemId, $isReadOnlyDb) {
            $lotItem = $this->prepareRepository($isReadOnlyDb)
                ->filterId($lotItemId)
                ->loadEntity();
            return $lotItem;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::LOT_ITEM_ID, $lotItemId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $lotItem = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $lotItem;
    }

    /**
     * Load predefined field set of LotItem entity by id.
     * @param array $select
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($lotItemId)
            ->select($select)
            ->loadRow();
    }

    /**
     * @param int[] $lotItemIds
     * @param bool $isReadOnlyDb
     * @return array|LotItem[]
     */
    public function loadEntities(array $lotItemIds, bool $isReadOnlyDb = false): array
    {
        $lotItems = $this->prepareRepository($isReadOnlyDb)
            ->filterId($lotItemIds)
            ->loadEntities();
        return $lotItems;
    }

    /**
     * Get ids of lot items from category
     * @param int $lotCategoryId
     * @return int[]
     */
    public function loadIdsByCategory(int $lotCategoryId): array
    {
        $rows = $this->createLotItemCategoryReadRepository()
            ->select(['lic.lot_item_id'])
            ->joinLotItemFilterActive(true)
            ->filterLotCategoryId($lotCategoryId)
            ->loadRows();
        $ids = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
        return $ids;
    }

    /**
     * Load LotItem by sync key from namespace
     *
     * @param string $key
     * @param int|null $namespaceId null results with null entity
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function loadBySyncKey(string $key, ?int $namespaceId, ?int $accountId, bool $isReadOnlyDb = false): ?LotItem
    {
        $lotItem = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->joinLotItemSyncFilterSyncNamespaceId($namespaceId)
            ->joinLotItemSyncFilterKey($key)
            ->loadEntity();
        return $lotItem;
    }

    /**
     * @param int $itemNum
     * @param string $itemNumExt
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function loadByItemNo(
        int $itemNum,
        string $itemNumExt,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?LotItem {
        $lotItem = $this->prepareRepositoryForLoadByItemNo(
            $itemNum,
            $itemNumExt,
            $accountId,
            $isReadOnlyDb
        )
            ->loadEntity();
        return $lotItem;
    }

    /**
     * @param LotItemNoParsed $itemNoParsed
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function loadByItemNoParsed(
        LotItemNoParsed $itemNoParsed,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?LotItem {
        return $this->loadByItemNo(
            $itemNoParsed->itemNum,
            $itemNoParsed->itemNumExtension,
            $accountId,
            $isReadOnlyDb
        );
    }

    public function loadSelectedByLotNo(
        array $select,
        int $itemNum,
        string $itemNumExt,
        int $accountId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepositoryForLoadByItemNo(
            $itemNum,
            $itemNumExt,
            $accountId,
            $isReadOnlyDb
        )
            ->select($select)
            ->loadRow();
    }

    public function loadSelectedByItemNoParsed(
        array $select,
        LotItemNoParsed $itemNoParsed,
        int $accountId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->loadSelectedByLotNo(
            $select,
            $itemNoParsed->itemNum,
            $itemNoParsed->itemNumExtension,
            $accountId,
            $isReadOnlyDb
        );
    }

    protected function prepareRepositoryForLoadByItemNo(
        int $itemNum,
        string $itemNumExt,
        int $accountId,
        bool $isReadOnlyDb = false
    ): LotItemReadRepository {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterItemNum($itemNum)
            ->filterItemNumExt($itemNumExt);
    }
}
