<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrent;

use RtbCurrent;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractRtbCurrentReadRepository
 * @method RtbCurrent[] loadEntities()
 * @method RtbCurrent|null loadEntity()
 */
abstract class AbstractRtbCurrentReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_RTB_CURRENT;
    protected string $alias = Db::A_RTB_CURRENT;

    /**
     * Filter by rtb_current.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by rtb_current.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by rtb_current.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by rtb_current.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.lot_active
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotActive(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_active', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_active from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotActive(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_active', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.lot_active
     * @return static
     */
    public function groupByLotActive(): static
    {
        $this->group($this->alias . '.lot_active');
        return $this;
    }

    /**
     * Order by rtb_current.lot_active
     * @param bool $ascending
     * @return static
     */
    public function orderByLotActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.new_bid_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNewBidBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.new_bid_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.new_bid_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNewBidBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.new_bid_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.new_bid_by
     * @return static
     */
    public function groupByNewBidBy(): static
    {
        $this->group($this->alias . '.new_bid_by');
        return $this;
    }

    /**
     * Order by rtb_current.new_bid_by
     * @param bool $ascending
     * @return static
     */
    public function orderByNewBidBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.new_bid_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.ask_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAskBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ask_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.ask_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAskBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ask_bid', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.ask_bid
     * @return static
     */
    public function groupByAskBid(): static
    {
        $this->group($this->alias . '.ask_bid');
        return $this;
    }

    /**
     * Order by rtb_current.ask_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAskBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.ask_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.absentee_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.absentee_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.absentee_bid
     * @return static
     */
    public function groupByAbsenteeBid(): static
    {
        $this->group($this->alias . '.absentee_bid');
        return $this;
    }

    /**
     * Order by rtb_current.absentee_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.auto_start
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoStart(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_start', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.auto_start from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoStart(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_start', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.auto_start
     * @return static
     */
    public function groupByAutoStart(): static
    {
        $this->group($this->alias . '.auto_start');
        return $this;
    }

    /**
     * Order by rtb_current.auto_start
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoStart(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_start', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.enter_floor_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEnterFloorNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enter_floor_no', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.enter_floor_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEnterFloorNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enter_floor_no', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.enter_floor_no
     * @return static
     */
    public function groupByEnterFloorNo(): static
    {
        $this->group($this->alias . '.enter_floor_no');
        return $this;
    }

    /**
     * Order by rtb_current.enter_floor_no
     * @param bool $ascending
     * @return static
     */
    public function orderByEnterFloorNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.enter_floor_no', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.enter_floor_no by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEnterFloorNo(string $filterValue): static
    {
        $this->like($this->alias . '.enter_floor_no', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.next_lot
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNextLot(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.next_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.next_lot from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNextLot(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.next_lot', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.next_lot
     * @return static
     */
    public function groupByNextLot(): static
    {
        $this->group($this->alias . '.next_lot');
        return $this;
    }

    /**
     * Order by rtb_current.next_lot
     * @param bool $ascending
     * @return static
     */
    public function orderByNextLot(bool $ascending = true): static
    {
        $this->order($this->alias . '.next_lot', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.increment', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.increment
     * @return static
     */
    public function groupByIncrement(): static
    {
        $this->group($this->alias . '.increment');
        return $this;
    }

    /**
     * Order by rtb_current.increment
     * @param bool $ascending
     * @return static
     */
    public function orderByIncrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.increment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.lot_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_group', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_group', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.lot_group
     * @return static
     */
    public function groupByLotGroup(): static
    {
        $this->group($this->alias . '.lot_group');
        return $this;
    }

    /**
     * Order by rtb_current.lot_group
     * @param bool $ascending
     * @return static
     */
    public function orderByLotGroup(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_group', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.lot_group by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotGroup(string $filterValue): static
    {
        $this->like($this->alias . '.lot_group', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.group_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroupUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.group_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroupUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group_user', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.group_user
     * @return static
     */
    public function groupByGroupUser(): static
    {
        $this->group($this->alias . '.group_user');
        return $this;
    }

    /**
     * Order by rtb_current.group_user
     * @param bool $ascending
     * @return static
     */
    public function orderByGroupUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.group_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.default_increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterDefaultIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.default_increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipDefaultIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_increment', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.default_increment
     * @return static
     */
    public function groupByDefaultIncrement(): static
    {
        $this->group($this->alias . '.default_increment');
        return $this;
    }

    /**
     * Order by rtb_current.default_increment
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultIncrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_increment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.enable_decrement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableDecrement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_decrement', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.enable_decrement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableDecrement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_decrement', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.enable_decrement
     * @return static
     */
    public function groupByEnableDecrement(): static
    {
        $this->group($this->alias . '.enable_decrement');
        return $this;
    }

    /**
     * Order by rtb_current.enable_decrement
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableDecrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_decrement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.buyer_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyerUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.buyer_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyerUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_user', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.buyer_user
     * @return static
     */
    public function groupByBuyerUser(): static
    {
        $this->group($this->alias . '.buyer_user');
        return $this;
    }

    /**
     * Order by rtb_current.buyer_user
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.bid_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.bid_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_countdown', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.bid_countdown
     * @return static
     */
    public function groupByBidCountdown(): static
    {
        $this->group($this->alias . '.bid_countdown');
        return $this;
    }

    /**
     * Order by rtb_current.bid_countdown
     * @param bool $ascending
     * @return static
     */
    public function orderByBidCountdown(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_countdown', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.bid_countdown by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidCountdown(string $filterValue): static
    {
        $this->like($this->alias . '.bid_countdown', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by rtb_current.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by rtb_current.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.lot_end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_end_date', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.lot_end_date
     * @return static
     */
    public function groupByLotEndDate(): static
    {
        $this->group($this->alias . '.lot_end_date');
        return $this;
    }

    /**
     * Order by rtb_current.lot_end_date
     * @param bool $ascending
     * @return static
     */
    public function orderByLotEndDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_end_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.lot_end_date
     * @param string $filterValue
     * @return static
     */
    public function filterLotEndDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_end_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.lot_end_date
     * @param string $filterValue
     * @return static
     */
    public function filterLotEndDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_end_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.lot_end_date
     * @param string $filterValue
     * @return static
     */
    public function filterLotEndDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_end_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.lot_end_date
     * @param string $filterValue
     * @return static
     */
    public function filterLotEndDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_end_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.pause_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPauseDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pause_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pause_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPauseDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pause_date', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.pause_date
     * @return static
     */
    public function groupByPauseDate(): static
    {
        $this->group($this->alias . '.pause_date');
        return $this;
    }

    /**
     * Order by rtb_current.pause_date
     * @param bool $ascending
     * @return static
     */
    public function orderByPauseDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.pause_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.pause_date
     * @param string $filterValue
     * @return static
     */
    public function filterPauseDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pause_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.pause_date
     * @param string $filterValue
     * @return static
     */
    public function filterPauseDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pause_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.pause_date
     * @param string $filterValue
     * @return static
     */
    public function filterPauseDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pause_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.pause_date
     * @param string $filterValue
     * @return static
     */
    public function filterPauseDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pause_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.fair_warning_sec
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterFairWarningSec(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warning_sec', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.fair_warning_sec from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipFairWarningSec(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warning_sec', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.fair_warning_sec
     * @return static
     */
    public function groupByFairWarningSec(): static
    {
        $this->group($this->alias . '.fair_warning_sec');
        return $this;
    }

    /**
     * Order by rtb_current.fair_warning_sec
     * @param bool $ascending
     * @return static
     */
    public function orderByFairWarningSec(bool $ascending = true): static
    {
        $this->order($this->alias . '.fair_warning_sec', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.fair_warning_sec
     * @param int $filterValue
     * @return static
     */
    public function filterFairWarningSecGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fair_warning_sec', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.fair_warning_sec
     * @param int $filterValue
     * @return static
     */
    public function filterFairWarningSecGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fair_warning_sec', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.fair_warning_sec
     * @param int $filterValue
     * @return static
     */
    public function filterFairWarningSecLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fair_warning_sec', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.fair_warning_sec
     * @param int $filterValue
     * @return static
     */
    public function filterFairWarningSecLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fair_warning_sec', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.pending_action
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPendingAction(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pending_action from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPendingAction(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.pending_action
     * @return static
     */
    public function groupByPendingAction(): static
    {
        $this->group($this->alias . '.pending_action');
        return $this;
    }

    /**
     * Order by rtb_current.pending_action
     * @param bool $ascending
     * @return static
     */
    public function orderByPendingAction(bool $ascending = true): static
    {
        $this->order($this->alias . '.pending_action', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.pending_action
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.pending_action
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.pending_action
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.pending_action
     * @param int $filterValue
     * @return static
     */
    public function filterPendingActionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.pending_action_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPendingActionDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pending_action_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPendingActionDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action_date', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.pending_action_date
     * @return static
     */
    public function groupByPendingActionDate(): static
    {
        $this->group($this->alias . '.pending_action_date');
        return $this;
    }

    /**
     * Order by rtb_current.pending_action_date
     * @param bool $ascending
     * @return static
     */
    public function orderByPendingActionDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.pending_action_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.pending_action_date
     * @param string $filterValue
     * @return static
     */
    public function filterPendingActionDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.pending_action_date
     * @param string $filterValue
     * @return static
     */
    public function filterPendingActionDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.pending_action_date
     * @param string $filterValue
     * @return static
     */
    public function filterPendingActionDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.pending_action_date
     * @param string $filterValue
     * @return static
     */
    public function filterPendingActionDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pending_action_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.running_interval
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRunningInterval(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.running_interval', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.running_interval from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRunningInterval(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.running_interval', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.running_interval
     * @return static
     */
    public function groupByRunningInterval(): static
    {
        $this->group($this->alias . '.running_interval');
        return $this;
    }

    /**
     * Order by rtb_current.running_interval
     * @param bool $ascending
     * @return static
     */
    public function orderByRunningInterval(bool $ascending = true): static
    {
        $this->order($this->alias . '.running_interval', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current.running_interval by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRunningInterval(string $filterValue): static
    {
        $this->like($this->alias . '.running_interval', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current.extend_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.extend_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.extend_time
     * @return static
     */
    public function groupByExtendTime(): static
    {
        $this->group($this->alias . '.extend_time');
        return $this;
    }

    /**
     * Order by rtb_current.extend_time
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendTime(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_time', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.lot_start_gap_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStartGapTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_start_gap_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStartGapTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.lot_start_gap_time
     * @return static
     */
    public function groupByLotStartGapTime(): static
    {
        $this->group($this->alias . '.lot_start_gap_time');
        return $this;
    }

    /**
     * Order by rtb_current.lot_start_gap_time
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStartGapTime(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_start_gap_time', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by rtb_current.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by rtb_current.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by rtb_current.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by rtb_current.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by rtb_current.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
