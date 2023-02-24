<?php
/**
 * Helping methods for settlement loading
 *
 * SAM-4339: Settlement Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

use Sam\Core\Filter\EntityLoader\SettlementAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Settlement;
use Sam\Core\Constants;

/**
 * Class SettlementLoader
 * @package Sam\Settlement\Load
 */
class SettlementLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use SettlementAllFilterTrait;

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
     * Get  Settlement by ID
     *
     * @param int|null $settlementId settlement.id - it should be always positive integer but from url param value, it can be null
     * @param bool $isReadOnlyDb
     * @return Settlement|null
     */
    public function load(?int $settlementId, bool $isReadOnlyDb = false): ?Settlement
    {
        if (!$settlementId) {
            return null;
        }

        $fn = function () use ($settlementId, $isReadOnlyDb) {
            $auction = $this->prepareRepository($isReadOnlyDb)
                ->filterId($settlementId)
                ->loadEntity();
            return $auction;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::SETTLEMENT_ID, $settlementId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $auction = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auction;
    }

    /**
     * Load array of available settlements by ids
     * @param int[] $settlementIds
     * @param bool $isReadOnlyDb
     * @return Settlement[]
     */
    public function loadEntities(array $settlementIds, bool $isReadOnlyDb = false): array
    {
        if (!$settlementIds) {
            return [];
        }

        $settlements = $this->prepareRepository($isReadOnlyDb)
            ->filterId($settlementIds)
            ->loadEntities();
        return $settlements;
    }

    /**
     * Load predefined field set of Settlement entity by id.
     * @param array $select
     * @param int|null $settlementId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $settlementId, bool $isReadOnlyDb = false): array
    {
        if (!$settlementId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($settlementId)
            ->select($select)
            ->loadRow();
    }

    /**
     * Load predefined field set of Settlement entity by ids.
     * @param array $select
     * @param array $settlementIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedRows(array $select, array $settlementIds, bool $isReadOnlyDb = false): array
    {
        if (!$settlementIds) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($settlementIds)
            ->select($select)
            ->loadRows();
    }

    /**
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return Settlement[]
     */
    public function loadByLotItemId(?int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }

        $settlements = $this->prepareRepository($isReadOnlyDb)
            ->joinSettlementItemFilterActive(true)
            ->joinSettlementItemFilterLotItemId($lotItemId)
            ->loadEntities();
        return $settlements;
    }

    /**
     * @param array $settlementCheckIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadBySettlementCheckIds(array $settlementCheckIds, bool $isReadOnlyDb = false): array
    {
        if (!$settlementCheckIds) {
            return [];
        }

        $settlements = $this->prepareRepository($isReadOnlyDb)
            ->joinSettlementCheckFilterId($settlementCheckIds)
            ->loadEntities();
        return $settlements;
    }
}
