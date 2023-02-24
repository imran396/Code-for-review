<?php
/**
 * SAM-3694:Invoice related repositories  https://bidpath.atlassian.net/browse/SAM-3694
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 August, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of InvoiceItem filtered by criteria
 * $invoiceItemRepository = \Sam\Storage\Repository\InvoiceItemReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $invoiceItemRepository->exist();
 * $count = $invoiceItemRepository->count();
 * $invoiceItems = $invoiceItemRepository->loadEntities();
 *
 * // Sample2. Load single InvoiceItem
 * $invoiceItemRepository = \Sam\Storage\Repository\InvoiceItemReadRepository::new()
 *     ->filterId(1);
 * $invoiceItem = $invoiceItemRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceItem;

use Sam\Core\Constants;

/**
 * Class InvoiceItemReadRepository
 * @package Sam\Storage\ReadRepository\Entity\InvoiceItem
 */
class InvoiceItemReadRepository extends AbstractInvoiceItemReadRepository
{
    private const JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL = 'auction_details_cache_by_seo_url';

    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account AS acc ON acc.id = li.account_id',
        'auction' => 'JOIN auction AS a ON a.id = ii.auction_id',
        'auction_cache' => 'JOIN auction_cache AS ac ON ac.auction_id = ii.auction_id',
        'auction_lot_item' => 'JOIN auction_lot_item AS ali ON ali.lot_item_id = ii.lot_item_id AND ali.auction_id = ii.auction_id',
        'consignor_by_lot_item_consignor' => 'JOIN consignor AS cons_by_li_cons ON cons_by_li_cons.user_id = li.consignor_id',
        'invoice' => 'JOIN invoice AS i ON i.id = ii.invoice_id',
        'invoice_auction' => 'JOIN invoice_auction AS iauc ON iauc.invoice_id = ii.invoice_id AND iauc.auction_id = ii.auction_id',
        'lot_item' => 'JOIN lot_item AS li ON li.id = ii.lot_item_id',
        'user_by_lot_item_consignor' => 'JOIN user AS u_by_li_cons ON u_by_li_cons.id = li.consignor_id',
        'user_by_winning_bidder' => 'JOIN `user` u_by_wb ON u_by_wb.id = ii.winning_bidder_id',
        'hp_tax_schema_active' => 'JOIN tax_schema as ts_hp_active ON ts_hp_active.id = ii.hp_tax_schema_id AND ts_hp_active.active',
        'bp_tax_schema_active' => 'JOIN tax_schema as ts_bp_active ON ts_bp_active.id = ii.bp_tax_schema_id AND ts_bp_active.active',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $seoUrlKey = $this->escape(Constants\AuctionDetailsCache::SEO_URL);
        $this->joins[self::JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL] = "JOIN auction_details_cache adc_by_su ON adc_by_su.auction_id = ii.auction_id AND adc_by_su.`key` = {$seoUrlKey}";
        return parent::initInstance();
    }

    // /**
    //  * TODO: implement (SAM-5975)
    //  * @param bool $ascending
    //  * @return $this
    //  */
    // public function orderByLotNo(bool $ascending = true): static
    // {
    //     $this->order('ii.lot_no', $ascending);
    //     return $this;
    // }

    // --- Account ---

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->joinLotItem();
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by acc.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    // --- Auction ---

    /**
     * Join 'auction' table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Define filtering by a.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    /**
     * Define filtering by a.currency
     * @param int|int[] $currencyId
     * @return static
     */
    public function joinAuctionFilterCurrency(int|array|null $currencyId): static
    {
        $this->joinAuction();
        $this->filterArray('a.currency', $currencyId);
        return $this;
    }

    /**
     * Define ordering by a.sale_num
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionOrderBySaleNo(bool $ascending = true): static
    {
        $this->joinAuction();
        $this->order('a.sale_num', $ascending);
        $this->order('a.sale_num_ext', $ascending);
        return $this;
    }

    // --- AuctionCache ---

    /**
     * Join 'auction_cache' table
     * @return static
     */
    public function joinAuctionCache(): static
    {
        $this->join('auction_cache');
        return $this;
    }

    // --- AuctionDetailsCache ---

    /**
     * @return static
     */
    public function joinAuctionDetailsCacheBySeoUrl(): static
    {
        $this->join(self::JOIN_AUCTION_DETAILS_CACHE_BY_SEO_URL);
        return $this;
    }

    // --- AuctionLotItem ---

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
     * Replace with orderByLotNo(), when ii.lot_no will be implemented.
     * @param bool $ascending
     * @return $this
     */
    public function joinAuctionLotItemOrderByLotNo(bool $ascending = true): static
    {
        $this->joinAuctionLotItem();
        $this->order('ali.lot_num', $ascending);
        $this->order('ali.lot_num_ext', $ascending);
        $this->order('ali.lot_num_prefix', $ascending);
        return $this;
    }

    // --- Invoice ---

    /**
     * Join 'invoice' table
     * @return static
     */
    public function joinInvoice(): static
    {
        $this->join('invoice');
        return $this;
    }

    /**
     * Join 'invoice_auction' table
     * @return static
     */
    public function joinInvoiceAuction(): static
    {
        $this->join('invoice_auction');
        return $this;
    }

    public function joinInvoiceAuctionOrderBySaleNo(bool $ascending = true): static
    {
        $this->joinInvoiceAuction();
        $this->order('iauc.sale_no', $ascending);
        return $this;
    }

    /**
     * Left join user table
     * Define filtering by u.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinInvoiceFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinInvoice();
        $this->filterArray('i.account_id', $accountIds);
        return $this;
    }

    /**
     * Define filtering by i.invoice_status_id
     * @param int|int[] $invoiceStatusId
     * @return static
     */
    public function joinInvoiceFilterInvoiceStatusId(int|array|null $invoiceStatusId): static
    {
        $this->joinInvoice();
        $this->filterArray('i.invoice_status_id', $invoiceStatusId);
        return $this;
    }

    /**
     * Define ordering by i.account_id
     * @param bool $ascending
     * @return static
     */
    public function joinInvoiceOrderByAccountId(bool $ascending = true): static
    {
        $this->joinInvoice();
        $this->order('i.account_id', $ascending);
        return $this;
    }

    // --- LotItem ---

    /**
     * Join 'lot_item' table
     * @return static
     */
    public function joinLotItem(): static
    {
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
     * @param bool $ascending
     * @return $this
     */
    public function joinLotItemOrderByItemNo(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.item_num', $ascending);
        $this->order('li.item_num_ext', $ascending);
        return $this;
    }

    /**
     * Left join users table for lot item consignor
     * @return static
     */
    public function joinUserByLotItemConsignor(): static
    {
        $this->join('lot_item');
        $this->join('user_by_lot_item_consignor');
        return $this;
    }

    /**
     * Left join consignor table for lot item consignor
     * @return static
     */
    public function joinConsignorByLotItemConsignor(): static
    {
        $this->join('lot_item');
        $this->join('consignor_by_lot_item_consignor');
        return $this;
    }

    /**
     * Left join user table and filter by user status
     * Define filtering by u.user_status_id
     * @param int|int[] $userStatusIds
     * @return static
     */
    public function joinUserWinningBidderFilterUserStatusId(int|array|null $userStatusIds): static
    {
        $this->join('user_by_winning_bidder');
        $this->filterArray('u_by_wb.user_status_id', $userStatusIds);
        return $this;
    }

    public function joinHpTaxSchemaActive(): static
    {
        $this->join('hp_tax_schema_active');
        return $this;
    }

    public function joinBpTaxSchemaActive(): static
    {
        $this->join('bp_tax_schema_active');
        return $this;
    }
}
