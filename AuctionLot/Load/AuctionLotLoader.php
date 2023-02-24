<?php
/**
 * Help methods for AuctionLot loading
 *
 * SAM-4021: Lot Loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 21, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load;

use AuctionLotItem;
use Generator;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\AuctionLotAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;

/**
 * Class AuctionLotLoader
 * @package Sam\AuctionLot\Load
 */
class AuctionLotLoader extends EntityLoaderBase
{
    use AuctionLotAllFilterTrait;
    use EntityMemoryCacheManagerAwareTrait;

    private const ORD_BY_LOT_NUM = 'lot_num';
    private const ORD_BY_ORDER = 'order';

    /** @var int */
    protected int $chunkSize = 200;
    /** @var string */
    protected string $sortColumn = '';
    /** @var bool */
    protected bool $isAscending = true;

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
     * --- Loading of single auction lot entity ---
     */

    /**
     * Load AuctionLotItem by auction.id and lot_item.id, filtered by available statuses
     *
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function load(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return null;
        }

        $fn = function () use ($lotItemId, $auctionId, $isReadOnlyDb) {
            $auctionLot = $this->prepareRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->loadEntity();
            return $auctionLot;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::AUCTION_LOT_ITEM_LOT_ITEM_ID_AUCTION_ID,
            [$lotItemId, $auctionId]
        );
        $filterDescriptors = $this->collectFilterDescriptors();
        $auctionLot = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auctionLot;
    }

    public function loadSelected(
        array $select,
        ?int $lotItemId,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->select($select)
            ->loadRow();
    }

    /**
     * @param array $auctionLotIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadByIds(array $auctionLotIds, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($auctionLotIds)
            ->loadEntities();
    }

    /**
     * Load AuctionLot by ali.id
     * @param int|null $auctionLotId null results with null
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadById(?int $auctionLotId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        if (!$auctionLotId) {
            return null;
        }

        $fn = function () use ($auctionLotId, $isReadOnlyDb) {
            $auctionLot = $this->prepareRepository($isReadOnlyDb)
                ->filterId($auctionLotId)
                ->loadEntity();
            return $auctionLot;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_LOT_ITEM_ID, $auctionLotId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $auctionLot = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auctionLot;
    }

    /**
     * Load last assigned auction lot by lot item id.
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadRecentByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        $auctionLot = $this->prepareRepository($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->orderByCreatedOn(false)
            ->loadEntity();
        return $auctionLot;
    }

    /**
     * Load AuctionLotItem by sync key from namespace
     * @param string $key entity_sync.key
     * @param int $namespaceId entity_sync.namespace_id
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadBySyncKey(string $key, int $namespaceId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        $auctionLot = $this->prepareRepository($isReadOnlyDb)
            ->joinAuctionLotItemSyncFilterSyncNamespaceId($namespaceId)
            ->joinAuctionLotItemSyncFilterKey($key)
            ->loadEntity();
        return $auctionLot;
    }

    /**
     * --- Loading by lot# ---
     */

    /**
     * Load AuctionLotItem by lot number, extension, prefix
     * @param int|null $lotNum lot number
     * @param string $lotNumExt lot num extension
     * @param string $lotNumPrefix
     * @param int|null $auctionId auction.id - null when auction is lost by unknown reason, results to null
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadByLotNo(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): ?AuctionLotItem {
        if (
            !$auctionId
            || $lotNum === null
        ) {
            return null;
        }

        return $this->prepareRepositoryForLoadByLotNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $auctionId,
            $isReadOnlyDb
        )
            ->loadEntity();
    }

    /**
     * Load AuctionLotItem by lot# passed in DTO
     * @param LotNoParsed $lotNoParsed
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadByLotNoParsed(
        LotNoParsed $lotNoParsed,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): ?AuctionLotItem {
        return $this->loadByLotNo(
            $lotNoParsed->lotNum,
            $lotNoParsed->lotNumExtension,
            $lotNoParsed->lotNumPrefix,
            $auctionId,
            $isReadOnlyDb
        );
    }

    /**
     * Load array of selected fields of auction lot filtered by lot#
     * @param array $select
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByLotNo(
        array $select,
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        if (
            !$auctionId
            || $lotNum === null
        ) {
            return [];
        }

        return $this->prepareRepositoryForLoadByLotNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $auctionId,
            $isReadOnlyDb
        )
            ->select($select)
            ->loadRow();
    }

    public function loadSelectedByLotNoParsed(
        array $select,
        LotNoParsed $lotNoParsed,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->loadSelectedByLotNo(
            $select,
            $lotNoParsed->lotNum,
            $lotNoParsed->lotNumExtension,
            $lotNoParsed->lotNumPrefix,
            $auctionId,
            $isReadOnlyDb
        );
    }

    protected function prepareRepositoryForLoadByLotNo(
        int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        int $auctionId,
        bool $isReadOnlyDb
    ): AuctionLotItemReadRepository {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotNum($lotNum)
            ->filterLotNumExt($lotNumExt)
            ->filterLotNumPrefix($lotNumPrefix);
    }

    /**
     * --- Loading of multiple auction lot entities ---
     */

    /**
     * Allow to tune chunk size, that is used for chunk-separated loading
     * @param int $chunkSize
     * @return $this
     */
    public function setChunkSize(int $chunkSize): static
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * Apply ordering by lot#
     * @param bool $isAscending
     * @return $this
     */
    public function orderByLotNum(bool $isAscending): static
    {
        $this->sortColumn = self::ORD_BY_LOT_NUM;
        $this->isAscending = $isAscending;
        return $this;
    }

    /**
     * Apply ordering by auction order options
     * @param bool $isAscending
     * @return $this
     */
    public function orderByOrder(bool $isAscending): static
    {
        $this->sortColumn = self::ORD_BY_ORDER;
        $this->isAscending = $isAscending;
        return $this;
    }

    /**
     * Load all lots of auction via generator
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return Generator|AuctionLotItem[]
     */
    public function yieldByAuctionId(int $auctionId, bool $isReadOnlyDb = false): Generator
    {
        $repo = $this->prepareRepositoryForArray($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->setChunkSize($this->chunkSize);
        return $repo->yieldEntities();
    }

    /**
     * Load available lots by item id. It should be single lot for $availableLotStatuses, but we may change filtering status
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return array|AuctionLotItem[]
     */
    public function loadByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $auctionLots = $this->prepareRepository($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->orderByCreatedOn(false)
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * Load Auction lots by their lot item ids and auction id
     * @param int[] $lotItemIds [] results with []
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadByLotItemIds(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        if (!$lotItemIds) {
            return [];
        }

        $auctionLots = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemIds)
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * Load lots for specific consignor in auction
     * @param int|null $auctionId - null when auction unknown
     * @param int $consignorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadByAuctionAndConsignor(?int $auctionId, int $consignorUserId, bool $isReadOnlyDb = false): array
    {
        $repo = $this->prepareRepositoryForArray($isReadOnlyDb)
            ->joinLotItemFilterConsignorId($consignorUserId)
            ->orderByOrder();
        if ($auctionId) {
            $repo->filterAuctionId($auctionId);
        } else {
            $repo->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses);
        }
        $auctionLots = $repo->loadEntities();
        return $auctionLots;
    }

    /**
     * Prepare repository for loading collection of entities.
     * Apply regular filtering to repository and additional ordering options.
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareRepositoryForArray(bool $isReadOnlyDb): AuctionLotItemReadRepository
    {
        $repo = $this->prepareRepository($isReadOnlyDb);
        if ($this->sortColumn === self::ORD_BY_LOT_NUM) {
            $repo
                ->orderByLotNumPrefix($this->isAscending)
                ->orderByLotNum($this->isAscending)
                ->orderByLotNumExt($this->isAscending);
        } else { // $this->sortColumn === self::ORD_BY_ORDER
            $repo->orderByOrder($this->isAscending);
        }
        return $repo;
    }
}
