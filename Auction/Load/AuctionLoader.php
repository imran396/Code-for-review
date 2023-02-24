<?php
/**
 * Helping methods for auction loading
 *
 * SAM-3919: Auction Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

use Auction;
use Sam\Core\Auction\SaleNo\Parse\SaleNoParsed;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Filter\EntityLoader\AuctionAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;

/**
 * Class AuctionLoader
 * @package Sam\Auction\Load
 */
class AuctionLoader extends EntityLoaderBase
{
    use AuctionAllFilterTrait;
    use ConfigRepositoryAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
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
     * Get Auction by ID
     *
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function load(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        if (!$auctionId) {
            return null;
        }

        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $auction = $this->prepareRepository($isReadOnlyDb)
                ->filterId($auctionId)
                ->loadEntity();
            return $auction;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_ID, $auctionId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $auction = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auction;
    }

    /**
     * Load predefined field set of Auction entity by id.
     * @param array $select
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $auctionId, bool $isReadOnlyDb = false): array
    {
        if (!$auctionId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($auctionId)
            ->select($select)
            ->loadRow();
    }

    /**
     * Load by event id
     * @param string $eventId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadByEventId(string $eventId, int $accountId, bool $isReadOnlyDb = false): ?Auction
    {
        $auction = $this->prepareRepository($isReadOnlyDb)
            ->filterEventId($eventId)
            ->filterAccountId($accountId)
            ->loadEntity();
        return $auction;
    }

    /**
     * Load Auction by sync key from namespace
     *
     * @param string $key entity_sync.key
     * @param int|null $namespaceId entity_sync.namespace_id
     * @param int|null $accountId auction.account_id
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadBySyncKey(string $key, ?int $namespaceId, ?int $accountId, bool $isReadOnlyDb = false): ?Auction
    {
        if (!$key || !$namespaceId || !$accountId) {
            return null;
        }

        $auction = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->joinAuctionSyncFilterSyncNamespaceId($namespaceId)
            ->joinAuctionSyncFilterKey($key)
            ->loadEntity();
        return $auction;
    }

    /**
     * Get Auction by sale number
     *
     * @param int $saleNum auction.sale_num
     * @param string $saleNumExt auction.sale_num_ext
     * @param ?int $accountId auction.account_id default main
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadBySaleNo(int $saleNum, string $saleNumExt = '', ?int $accountId = null, bool $isReadOnlyDb = false): ?Auction
    {
        if ($accountId === null) {
            $accountId = $this->cfg()->get('core->portal->mainAccountId');
        }
        $auction = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterSaleNum($saleNum)
            ->filterSaleNumExt($saleNumExt)
            ->loadEntity();
        return $auction;
    }

    /**
     * @param SaleNoParsed $saleNoParsed
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadBySaleNoParsed(SaleNoParsed $saleNoParsed, ?int $accountId = null, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->loadBySaleNo($saleNoParsed->saleNum, $saleNoParsed->saleNumExtension, $accountId, $isReadOnlyDb);
    }

    /**
     * Get the auction ids where this lot is assigned and has respective status.
     * When limit = 1, then return single auction id.
     * TODO: looks to be specific function, since it is called in \Sam\View\Admin\Panel\LotInfoPanel::initCurrencySign() only.
     * TODO: Possibly need to move, e.g. to \Sam\View\Form\Admin\LotInfoPanel namespace.
     *
     * @param int|null $lotItemId lot_item.id null when new lot added
     * @param int[]|null $lotStatus
     * @param int|null $limit
     * @param bool $isReadOnlyDb
     * @return int[] - int[] $auctionIds
     */
    public function loadIdsByAssignedLot(
        ?int $lotItemId,
        ?array $lotStatus = null,
        ?int $limit = null,
        bool $isReadOnlyDb = false
    ): array {
        if (!$lotItemId) {
            return [];
        }

        $repo = $this->prepareRepository($isReadOnlyDb)
            ->joinAuctionLotItemFilterLotStatusId($lotStatus)
            ->joinAuctionLotItemFilterLotItemId($lotItemId)
            ->joinLotItemFilterActive(true)
            ->select(['a.id AS id'])
            ->orderByCreatedOn();
        $repo->limit($limit);
        $rows = $repo->loadRows();
        $auctionIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $auctionIds;
    }

    /**
     * Load auctions by ids
     * @param int[] $auctionIds
     * @param bool $isReadOnlyDb
     * @return Auction[]
     */
    public function loadEntities(array $auctionIds, bool $isReadOnlyDb = false): array
    {
        $auctionIds = ArrayCast::castInt($auctionIds, Constants\Type::F_INT_POSITIVE);
        $auctions = $this->prepareRepository($isReadOnlyDb)
            ->filterId($auctionIds)
            ->loadEntities();
        return $auctions;
    }

    public function loadRows(array $auctionIds, bool $isReadOnlyDb = false): array
    {
        $auctionIds = ArrayCast::castInt($auctionIds, Constants\Type::F_INT_POSITIVE);
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->filterId($auctionIds)
            ->loadRows();
        return $rows;
    }

    public function loadSelectedRows(array $select, array $auctionIds, bool $isReadOnlyDb = false): array
    {
        $auctionIds = ArrayCast::castInt($auctionIds, Constants\Type::F_INT_POSITIVE);
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->filterId($auctionIds)
            ->select($select)
            ->loadRows();
        return $rows;
    }
}
