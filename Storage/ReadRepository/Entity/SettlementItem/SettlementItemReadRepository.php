<?php
/**
 * SAM-3695 : Settlement related repositories  https://bidpath.atlassian.net/browse/SAM-3695
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of SettlementItem filtered by criteria
 * $settlementItemRepository = \Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $settlementItemRepository->exist();
 * $count = $settlementItemRepository->count();
 * $settlementItem = $settlementItemRepository->loadEntities();
 *
 * // Sample2. Load single SettlementItem
 * $settlementItemRepository = \Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepository::new()
 *     ->filterId(1);
 * $settlementItem = $settlementItemRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementItem;

/**
 * Class SettlementItemReadRepository
 * @package Sam\Storage\ReadRepository\Entity\SettlementItem
 */
class SettlementItemReadRepository extends AbstractSettlementItemReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = li.account_id',
        'auction' => 'JOIN auction a ON a.id = si.auction_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.auction_id = si.auction_id AND ali.lot_item_id = si.lot_item_id',
        'lot_item' => 'JOIN lot_item li ON li.id = si.lot_item_id',
        'settlement' => 'JOIN settlement s ON s.id = si.settlement_id',
        'fee' => 'JOIN consignor_commission_fee ccff ON ccff.id = si.fee_id',
        'commission' => 'JOIN consignor_commission_fee ccfc ON ccfc.id = si.commission_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

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
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Filtering a.auction_status_id
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
     * @param bool $ascending
     * @return $this
     */
    public function joinAuctionOrderBySaleNo(bool $ascending = true): static
    {
        $this->joinAuction();
        $this->order('a.sale_num', $ascending);
        $this->order('a.sale_num_ext', $ascending);
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

    // --- Settlement ---

    /**
     * Join `settlement` table
     * @return static
     */
    public function joinSettlement(): static
    {
        $this->join('settlement');
        return $this;
    }

    /**
     * Define filtering by s.consignor_id
     * @param int|int[] $consignorUserId
     * @return static
     */
    public function joinSettlementFilterConsignorId(int|array|null $consignorUserId): static
    {
        $this->joinSettlement();
        $this->filterArray('s.consignor_id', $consignorUserId);
        return $this;
    }

    /**
     * @param int|int[] $settlementStatusId
     * @return static
     */
    public function joinSettlementFilterSettlementStatusId(int|array|null $settlementStatusId): static
    {
        $this->joinSettlement();
        $this->filterArray('s.settlement_status_id', $settlementStatusId);
        return $this;
    }

    /**
     * Join 'consignor commission fee' table
     * @return static
     */
    public function joinFee(): static
    {
        $this->join('fee');
        return $this;
    }

    /**
     * Join 'consignor commission fee' table
     * @return static
     */
    public function joinCommission(): static
    {
        $this->join('commission');
        return $this;
    }
}
