<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrentSnapshot;

use RtbCurrentSnapshot;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractRtbCurrentSnapshotReadRepository
 * @method RtbCurrentSnapshot[] loadEntities()
 * @method RtbCurrentSnapshot|null loadEntity()
 */
abstract class AbstractRtbCurrentSnapshotReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_RTB_CURRENT_SNAPSHOT;
    protected string $alias = Db::A_RTB_CURRENT_SNAPSHOT;

    /**
     * Filter by rtb_current_snapshot.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.command
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCommand(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.command', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.command from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCommand(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.command', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.command
     * @return static
     */
    public function groupByCommand(): static
    {
        $this->group($this->alias . '.command');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.command
     * @param bool $ascending
     * @return static
     */
    public function orderByCommand(bool $ascending = true): static
    {
        $this->order($this->alias . '.command', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current_snapshot.command by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCommand(string $filterValue): static
    {
        $this->like($this->alias . '.command', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.rtb_current_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRtbCurrentId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_current_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.rtb_current_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRtbCurrentId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_current_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.rtb_current_id
     * @return static
     */
    public function groupByRtbCurrentId(): static
    {
        $this->group($this->alias . '.rtb_current_id');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.rtb_current_id
     * @param bool $ascending
     * @return static
     */
    public function orderByRtbCurrentId(bool $ascending = true): static
    {
        $this->order($this->alias . '.rtb_current_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.rtb_current_id
     * @param int $filterValue
     * @return static
     */
    public function filterRtbCurrentIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.rtb_current_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.rtb_current_id
     * @param int $filterValue
     * @return static
     */
    public function filterRtbCurrentIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.rtb_current_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.rtb_current_id
     * @param int $filterValue
     * @return static
     */
    public function filterRtbCurrentIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.rtb_current_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.rtb_current_id
     * @param int $filterValue
     * @return static
     */
    public function filterRtbCurrentIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.rtb_current_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_active
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotActive(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_active', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_active from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotActive(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_active', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.lot_active
     * @return static
     */
    public function groupByLotActive(): static
    {
        $this->group($this->alias . '.lot_active');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.lot_active
     * @param bool $ascending
     * @return static
     */
    public function orderByLotActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.lot_active
     * @param int $filterValue
     * @return static
     */
    public function filterLotActiveLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.new_bid_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNewBidBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.new_bid_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.new_bid_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNewBidBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.new_bid_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.new_bid_by
     * @return static
     */
    public function groupByNewBidBy(): static
    {
        $this->group($this->alias . '.new_bid_by');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.new_bid_by
     * @param bool $ascending
     * @return static
     */
    public function orderByNewBidBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.new_bid_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.new_bid_by
     * @param int $filterValue
     * @return static
     */
    public function filterNewBidByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.new_bid_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.ask_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAskBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ask_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.ask_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAskBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ask_bid', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.ask_bid
     * @return static
     */
    public function groupByAskBid(): static
    {
        $this->group($this->alias . '.ask_bid');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.ask_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAskBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.ask_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.ask_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ask_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.absentee_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.absentee_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.absentee_bid
     * @return static
     */
    public function groupByAbsenteeBid(): static
    {
        $this->group($this->alias . '.absentee_bid');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.absentee_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.auto_start
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoStart(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_start', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.auto_start from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoStart(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_start', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.auto_start
     * @return static
     */
    public function groupByAutoStart(): static
    {
        $this->group($this->alias . '.auto_start');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.auto_start
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoStart(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_start', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.auto_start
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoStartLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_start', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.next_lot
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNextLot(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.next_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.next_lot from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNextLot(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.next_lot', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.next_lot
     * @return static
     */
    public function groupByNextLot(): static
    {
        $this->group($this->alias . '.next_lot');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.next_lot
     * @param bool $ascending
     * @return static
     */
    public function orderByNextLot(bool $ascending = true): static
    {
        $this->order($this->alias . '.next_lot', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.next_lot
     * @param int $filterValue
     * @return static
     */
    public function filterNextLotLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_lot', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.increment', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.increment
     * @return static
     */
    public function groupByIncrement(): static
    {
        $this->group($this->alias . '.increment');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.increment
     * @param bool $ascending
     * @return static
     */
    public function orderByIncrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.increment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.increment
     * @param float $filterValue
     * @return static
     */
    public function filterIncrementLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.increment', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_group', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_group', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.lot_group
     * @return static
     */
    public function groupByLotGroup(): static
    {
        $this->group($this->alias . '.lot_group');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.lot_group
     * @param bool $ascending
     * @return static
     */
    public function orderByLotGroup(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_group', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current_snapshot.lot_group by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotGroup(string $filterValue): static
    {
        $this->like($this->alias . '.lot_group', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.group_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroupUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.group_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroupUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group_user', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.group_user
     * @return static
     */
    public function groupByGroupUser(): static
    {
        $this->group($this->alias . '.group_user');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.group_user
     * @param bool $ascending
     * @return static
     */
    public function orderByGroupUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.group_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.group_user
     * @param int $filterValue
     * @return static
     */
    public function filterGroupUserLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_user', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.default_increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterDefaultIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.default_increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipDefaultIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_increment', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.default_increment
     * @return static
     */
    public function groupByDefaultIncrement(): static
    {
        $this->group($this->alias . '.default_increment');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.default_increment
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultIncrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_increment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.default_increment
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultIncrementLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_increment', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.enable_decrement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableDecrement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_decrement', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.enable_decrement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableDecrement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_decrement', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.enable_decrement
     * @return static
     */
    public function groupByEnableDecrement(): static
    {
        $this->group($this->alias . '.enable_decrement');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.enable_decrement
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableDecrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_decrement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.enable_decrement
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableDecrementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_decrement', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.buyer_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyerUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.buyer_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyerUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_user', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.buyer_user
     * @return static
     */
    public function groupByBuyerUser(): static
    {
        $this->group($this->alias . '.buyer_user');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.buyer_user
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.buyer_user
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerUserLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_user', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.bid_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.bid_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_countdown', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.bid_countdown
     * @return static
     */
    public function groupByBidCountdown(): static
    {
        $this->group($this->alias . '.bid_countdown');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.bid_countdown
     * @param bool $ascending
     * @return static
     */
    public function orderByBidCountdown(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_countdown', $ascending);
        return $this;
    }

    /**
     * Filter rtb_current_snapshot.bid_countdown by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidCountdown(string $filterValue): static
    {
        $this->like($this->alias . '.bid_countdown', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_current_snapshot.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by rtb_current_snapshot.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_current_snapshot.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_current_snapshot.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_current_snapshot.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_current_snapshot.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }
}
