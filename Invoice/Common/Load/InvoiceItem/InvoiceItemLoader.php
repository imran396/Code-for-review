<?php
/**
 * Help methods for Invoice Item entity loading
 * We don't filter by related entities, eg. li.active, ali.lot_status_id, a.auction_status_id, acc.active, u.user_status_id
 *
 * SAM-4337: Invoice Loader class
 * SAM-6004: Invoice data loading optimization
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load\InvoiceItem;

use InvoiceItem;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Availability\FilterInvoiceAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterInvoiceItemAvailabilityAwareTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoicedAuctionDto;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoiceItemDto;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class InvoiceItemLoader
 * @package Sam\Invoice\Common\Load
 */
class InvoiceItemLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use FilterInvoiceAvailabilityAwareTrait;
    use FilterInvoiceItemAvailabilityAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;

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
        $this->filterInvoiceItemActive(true);
        $this->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterInvoice();
        $this->clearFilterInvoiceItem();
        return $this;
    }

    // --- Load single invoice item record ---

    /**
     * @param int|null $invoiceItemId
     * @param bool $isReadOnlyDb
     * @return InvoiceItem|null
     */
    public function load(?int $invoiceItemId, bool $isReadOnlyDb = false): ?InvoiceItem
    {
        if (!$invoiceItemId) {
            return null;
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($invoiceItemId)
            ->loadEntity();
    }

    public function loadSelected(array $select, ?int $invoiceItemId, bool $isReadOnlyDb = false): array
    {
        if (!$invoiceItemId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($invoiceItemId)
            ->select($select)
            ->loadRow();
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return InvoiceItem|null
     */
    public function loadByLotItemIdAndAuctionId(
        ?int $lotItemId,
        ?int $auctionId = null,
        bool $isReadOnlyDb = false
    ): ?InvoiceItem {
        $invoiceItem = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterRelease(false)
            ->loadEntity();
        return $invoiceItem;
    }

    /**
     * Load single InvoiceItem by lot item, auction and hammer price
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param float|null $hammerPrice
     * @param bool $isReadOnlyDb
     * @return InvoiceItem|null
     */
    public function loadByAuctionLotHammerPrice(
        ?int $lotItemId,
        ?int $auctionId,
        ?float $hammerPrice,
        bool $isReadOnlyDb = false
    ): ?InvoiceItem {
        $invoiceItem = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterHammerPrice($hammerPrice)
            ->filterLotItemId($lotItemId)
            ->loadEntity();
        return $invoiceItem;
    }

    /**
     * function to get the first sale id
     * from the invoice, with the assumption
     * that there is only a single sale in an
     * invoice
     * @param int $invoiceId the invoice id
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function findFirstInvoicedAuctionId(int $invoiceId, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->prepareRepository($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->skipAuctionId(null)
            ->select(['ii.auction_id'])
            ->loadRow();
        return Cast::toInt($row['auction_id'] ?? null);
    }

    // --- Load multiple invoice item records ---

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceItem[]
     */
    public function loadByInvoiceId(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepositoryForLoadByInvoiceId($invoiceId, $isReadOnlyDb)
            ->loadEntities();
    }

    public function loadSelectedByInvoiceId(array $select, int $invoiceId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepositoryForLoadByInvoiceId($invoiceId, $isReadOnlyDb)
            ->select($select)
            ->loadRows();
    }

    protected function prepareRepositoryForLoadByInvoiceId(int $invoiceId, bool $isReadOnlyDb = false): InvoiceItemReadRepository
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->extendJoinCondition('auction_lot_item', 'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')')
            ->filterInvoiceId($invoiceId)
            ->joinAuctionOrderBySaleNo() // primary ordering
            ->joinAuctionLotItemOrderByLotNo(); // secondary ordering
    }

    // ---

    /**
     * Load invoice items and related data, and fill result DTO objects.
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceItemDto[]
     */
    public function loadDtos(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'a.auction_status_id',
            'a.auction_type',
            'a.end_date',
            'a.event_type',
            'a.name',
            'a.sale_num',
            'a.sale_num_ext',
            'a.start_closing_date',
            'a.test_auction',
            'a.timezone_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'ali.lot_status_id',
            'ali.quantity',
            'iauc.sale_date',
            'ii.auction_id',
            'ii.buyers_premium',
            'ii.hammer_price',
            'ii.id',
            'ii.lot_item_id',
            'ii.lot_name as lot_name',
            'ii.release',
            'ii.sales_tax',
            'ii.tax_application',
            'li.account_id',
            'li.item_num',
            'li.item_num_ext',
            'COALESCE(
                ali.quantity_digits, 
                li.quantity_digits, 
                (SELECT lc.quantity_digits
                 FROM lot_category lc
                   INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                 WHERE lic.lot_item_id = li.id
                   AND lc.active = 1
                 ORDER BY lic.id
                 LIMIT 1), 
                (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
            ) as quantity_scale',
        ];
        $rows = $this
            ->prepareRepository($isReadOnlyDb)
            ->extendJoinCondition('auction_lot_item', 'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')')
            ->filterInvoiceId($invoiceId)
            ->joinAuctionOrderBySaleNo() // primary ordering
            ->joinAuctionLotItemOrderByLotNo() // secondary ordering
            ->joinInvoiceAuction()
            ->joinLotItem()
            ->select($select)
            ->loadRows();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = InvoiceItemDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    // ---

    /**
     * Return auctions of lot items, which are in invoice
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoicedAuctionDto[]
     */
    public function loadInvoicedAuctionDtos(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'a.account_id',
            'a.auction_info_link',
            'a.auction_type',
            'a.end_date',
            'a.event_type',
            'a.id',
            'a.invoice_location_id',
            'a.name',
            'a.sale_num',
            'a.sale_num_ext',
            'a.start_closing_date',
            'a.test_auction',
            'a.timezone_id',
            'adc_by_su.value AS auction_seo_url',
            'iauc.sale_date',
        ];
        $invoiceItemRepository = $this->prepareRepository($isReadOnlyDb)
            ->enableDistinct(true)
            ->filterInvoiceId($invoiceId)
            ->joinAuction()
            ->joinAuctionDetailsCacheBySeoUrl()
            ->joinInvoiceAuction()
            ->groupByAuctionId()
            ->joinAuctionOrderBySaleNo()
            ->select($select);
        $rows = $invoiceItemRepository->loadRows();
        $dtos = array_map(
            static function (array $row) {
                return InvoicedAuctionDto::new()->fromDbRow($row);
            },
            $rows
        );
        return $dtos;
    }

    /**
     * Return array of auction ids to which lots from invoice are assigned
     *
     * @param int|null $invoiceId invoice.id
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadInvoicedAuctionIds(?int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $invoiceItems = $this->prepareRepository($isReadOnlyDb)
            ->enableDistinct(true)
            ->filterInvoiceId($invoiceId)
            ->select(['ii.auction_id'])
            ->loadRows();
        $auctionIds = ArrayCast::arrayColumnInt($invoiceItems, 'auction_id');
        return $auctionIds;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return InvoiceItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): InvoiceItemReadRepository
    {
        $repo = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterInvoiceItemActive()) {
            $repo->filterActive($this->getFilterInvoiceItemActive());
        }
        if ($this->hasFilterInvoiceStatusId()) {
            $repo->joinInvoiceFilterInvoiceStatusId($this->getFilterInvoiceStatusId());
        }
        return $repo;
    }
}
