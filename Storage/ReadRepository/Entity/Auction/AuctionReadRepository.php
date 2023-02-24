<?php
/**
 * General repository for AuctionReadRepository Parameters entity
 *
 * SAM-3688:  Auction related repositories  https://bidpath.atlassian.net/browse/SAM-3688
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionRepository = \Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionStatusId($auctionStatusIds)
 *      ->filterAccountId($auctionIds);
 * $isFound = $auctionRepository->exist();
 * $count = $auctionRepository->count();
 * $item = $auctionRepository->loadEntity();
 * $items = $auctionRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\Auction;

use Sam\Core\Constants;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;

/**
 * Class AuctionReadRepository
 */
class AuctionReadRepository extends AbstractAuctionReadRepository
{
    use CurrentDateTrait;
    use CurrencyLoaderAwareTrait;

    private const JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL = 'auction_details_cache_by_seo_url';

    protected array $joins = [
        'absentee_bid' => 'JOIN absentee_bid ab ON ab.auction_id = a.id AND ab.lot_item_id = ali.lot_item_id',
        'account' => 'JOIN account acc ON acc.id = a.account_id',
        'auction_bidder' => 'JOIN auction_bidder aub ON aub.auction_id = a.id',
        'auction_cache' => 'JOIN auction_cache ac ON ac.auction_id = a.id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON a.id = ali.auction_id',
        'auction_rtbd' => 'JOIN auction_rtbd artbd ON artbd.auction_id = a.id',
        'auction_timezone' => 'JOIN timezone atz ON atz.id = a.timezone_id',
        'bid_transaction' => 'JOIN bid_transaction bt ON bt.auction_id = a.id AND bt.lot_item_id = ali.lot_item_id',
        'currency' => 'JOIN currency curr ON curr.id = a.currency',
        'entity_sync' => 'JOIN entity_sync esync ON (a.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_AUCTION . ')',
        'invoice_item' => 'JOIN invoice_item AS ii ON ii.auction_id = a.id',
        'invoice_location' => 'JOIN location loc ON loc.id = a.invoice_location_id',
        'lot_item' => 'JOIN lot_item li ON ali.lot_item_id = li.id',
        'rtb_current' => 'JOIN rtb_current rtbc ON rtbc.auction_id = a.id',
        'settlement_item' => 'JOIN settlement_item AS si ON si.auction_id = a.id',
        'consignor_commission' => 'JOIN consignor_commission_fee ccf_cc ON ccf_cc.id = a.consignor_commission_id',
        'consignor_sold_fee' => 'JOIN consignor_commission_fee ccf_csf ON ccf_csf.id = a.consignor_sold_fee_id',
        'consignor_unsold_fee' => 'JOIN consignor_commission_fee ccf_cuf ON ccf_cuf.id = a.consignor_unsold_fee_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $seoUrlKey = $this->escape(Constants\AuctionDetailsCache::SEO_URL);
        $this->joins[self::JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL] = "JOIN auction_details_cache adc_by_su ON adc_by_su.auction_id = a.id AND adc_by_su.`key` = {$seoUrlKey}";
        return parent::initInstance();
    }

    /**
     * Define filtering by a.currency if is set, primary currency otherwise
     * @param int|int[] $currency
     * @return static
     */
    public function filterDefaultCurrency(int|array $currency): static
    {
        $primaryCurrencyId = $this->getCurrencyLoader()->findPrimaryCurrencyId();
        $this->filterArray("IFNULL(a.currency, {$primaryCurrencyId})", $currency);
        return $this;
    }

    /**
     * Filter closed
     * @return static
     */
    public function filterClosed(): static
    {
        $dateNowIso = $this->getCurrentDateUtcIso();
        $timed = Constants\Auction::TIMED;
        $live = Constants\Auction::LIVE;
        $hybrid = Constants\Auction::HYBRID;
        $etScheduled = Constants\Auction::ET_SCHEDULED;
        $asClosed = Constants\Auction::AS_CLOSED;
        $this->inlineCondition(
            "(SELECT (a.auction_type = '{$timed}'"
            . " AND a.event_type = {$etScheduled}"
            . " AND a.end_date < {$this->escape($dateNowIso)}"
            . ")"
            . " OR ((a.auction_type = '{$live}' OR a.auction_type = '{$hybrid}')"
            . " AND a.auction_status_id = {$asClosed})"
            . ")"
        );
        return $this;
    }

    /**
     * Define filtering by greater or equal than a.start_bidding_date or a.start_closing_date (depends on auction type)
     * @param string $date
     * @return static
     */
    public function filterAuctionStartDateGreaterOrEqual(string $date): static
    {
        $timed = Constants\Auction::TIMED;
        $this->filterInequality("IF(a.auction_type = '{$timed}', a.start_bidding_date, a.start_closing_date)", $date, '>=');
        return $this;
    }

    /**
     * Define filtering by less than a.start_bidding_date or a.start_closing_date (depends on auction type)
     * @param string $date
     * @return static
     */
    public function filterAuctionStartDateLess(string $date): static
    {
        $timed = Constants\Auction::TIMED;
        $this->filterInequality("IF(a.auction_type = '{$timed}', a.start_bidding_date, a.start_closing_date)", $date, '<');
        return $this;
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * @return static
     */
    public function joinAuctionCache(): static
    {
        $this->join('auction_cache');
        return $this;
    }

    /**
     * @return static
     */
    public function joinAuctionDetailsCacheBySeoUrl(): static
    {
        $this->join(self::JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL);
        return $this;
    }

    /**
     * Define filtering by ac.total_lots
     * @param int $value
     * @return static
     */
    public function joinAuctionCacheFilterTotalLotsGreater(int $value): static
    {
        $this->joinAuctionCache();
        $this->filterInequality('ac.total_lots', $value, '>');
        return $this;
    }

    /**
     * Join `auction_lot_item` table
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * Define filtering by ali.lot_status_id
     * @param int|int[] $lotStatusIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    /**
     * Define filtering by ali.lot_item_id
     * @param int|int[] $lotItemIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotItemId(int|array|null $lotItemIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_item_id', $lotItemIds);
        return $this;
    }

    /**
     * @return static
     */
    public function joinAuctionRtbd(): static
    {
        $this->join('auction_rtbd');
        return $this;
    }

    /**
     * Define filtering by a.rtbd_name
     * @param string|string[] $rtbdName
     * @return static
     */
    public function joinAuctionRtbdFilterRtbdName(string|array|null $rtbdName): static
    {
        $this->joinAuctionRtbd();
        $this->filterArray('artbd.rtbd_name', $rtbdName);
        return $this;
    }

    /**
     * Skip by a.rtbd_name
     * @param string|string[] $rtbdName
     * @return static
     */
    public function joinAuctionRtbdSkipRtbdName(string|array|null $rtbdName): static
    {
        $this->joinAuctionRtbd();
        $this->skipArray('artbd.rtbd_name', $rtbdName);
        return $this;
    }

    /**
     * Join `entity_sync` table
     * @return static
     */
    public function joinAuctionSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * Define filtering by esync.sync_namespace_id
     * @param int|int[] $syncNamespaceId
     * @return static
     */
    public function joinAuctionSyncFilterSyncNamespaceId(int|array|null $syncNamespaceId): static
    {
        $this->joinAuctionSync();
        $this->filterArray('esync.sync_namespace_id', $syncNamespaceId);
        return $this;
    }

    /**
     * Define filtering by esync.key
     * @param string|string[] $key
     * @return static
     */
    public function joinAuctionSyncFilterKey(string|array|null $key): static
    {
        $this->joinAuctionSync();
        $this->filterArray('esync.`key`', $key);
        return $this;
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->joinAuctionLotItem();
        $this->join('lot_item');
        return $this;
    }

    /**
     * Define filtering by li.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $active): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
    }

    /**
     * @return static
     */
    public function innerJoinRtbCurrent(): static
    {
        $this->innerJoin('rtb_current');
        return $this;
    }

    /**
     * @return $this
     */
    public function joinRtbCurrent(): static
    {
        $this->join('rtb_current');
        return $this;
    }

    /**
     * @param bool|array $active
     * @return $this
     */
    public function joinRtbCurrentFilterLotActive(bool|array|null $active): static
    {
        $this->joinRtbCurrent();
        $this->filterArray('rtbc.lot_active', $active);
        return $this;
    }

    /**
     * @return static
     */
    public function joinAuctionTimezone(): static
    {
        $this->join('auction_timezone');
        return $this;
    }

    /**
     * join currency table
     * @return static
     */
    public function joinCurrency(): static
    {
        $this->join('currency');
        return $this;
    }

    public function joinAbsenteeBid(): static
    {
        $this->join('absentee_bid');
        return $this;
    }

    /**
     * Join `auction_bidder` table
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Define filtering by aub.user_id
     * @param int|int[] $userId
     * @return static
     */
    public function joinAuctionBidderFilterUserId(int|array|null $userId): static
    {
        $this->joinAuctionBidder();
        $this->filterArray('aub.user_id', $userId);
        return $this;
    }

    public function joinBidTransaction(): static
    {
        $this->join('bid_transaction');
        return $this;
    }

    /**
     * Join `invoice_item` table
     * @return static
     */
    public function joinInvoiceItem(): static
    {
        $this->join('invoice_item');
        return $this;
    }

    /**
     * @param int|int[] $invoiceId
     * @return static
     */
    public function joinInvoiceItemFilterInvoiceId(int|array|null $invoiceId): static
    {
        $this->joinInvoiceItem();
        $this->filterArray('ii.invoice_id', $this->escape($invoiceId));
        return $this;
    }

    /**
     * @param bool $active
     * @return static
     */
    public function joinInvoiceItemFilterActive(bool|array|null $active): static
    {
        $this->joinInvoiceItem();
        $this->filterArray('ii.active', $active);
        return $this;
    }

    /**
     * Join `settlement_item` table
     * @return static
     */
    public function joinSettlementItem(): static
    {
        $this->join('settlement_item');
        return $this;
    }

    /**
     * @param int|int[] $settlementId
     * @return static
     */
    public function joinSettingsItemFilterSettlementId(int|array|null $settlementId): static
    {
        $this->join('settlement_item');
        $this->filterArray('si.settlement_id', $settlementId);
        return $this;
    }

    /**
     * Define LIKE filter condition for full sale# (sale_num + sale_num_ext concatenated)
     * @param string $searchKey
     * @return static
     */
    public function likeSaleNoConcatenated(string $searchKey): static
    {
        $this->like('CONCAT(IFNULL(a.sale_num, ""), IFNULL(a.sale_num_ext, ""))', "%{$searchKey}%");
        return $this;
    }

    /**
     * Define ordering by a.start_bidding_date or a.start_closing_date (depends on auction type)
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionStartDate(bool $ascending = true): static
    {
        $timed = Constants\Auction::TIMED;
        $this->order("IF(a.auction_type = '{$timed}', a.start_bidding_date, a.start_closing_date)", $ascending);
        return $this;
    }

    public function joinConsignorCommission(): static
    {
        $this->join('consignor_commission');
        return $this;
    }

    public function joinConsignorCommissionFilterActive(bool|array $active): static
    {
        $this->joinConsignorCommission();
        $this->filterArray('ccf_cc.active', $active);
        return $this;
    }

    public function joinConsignorSoldFee(): static
    {
        $this->join('consignor_sold_fee');
        return $this;
    }

    public function joinConsignorSoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorSoldFee();
        $this->filterArray('ccf_csf.active', $active);
        return $this;
    }

    public function joinConsignorUnsoldFee(): static
    {
        $this->join('consignor_unsold_fee');
        return $this;
    }

    public function joinConsignorUnsoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorUnsoldFee();
        $this->filterArray('ccf_cuf.active', $active);
        return $this;
    }
}
