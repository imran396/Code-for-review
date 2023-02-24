<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AbsenteeBid;

use AbsenteeBid;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAbsenteeBidReadRepository
 * @method AbsenteeBid[] loadEntities()
 * @method AbsenteeBid|null loadEntity()
 */
abstract class AbstractAbsenteeBidReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_ABSENTEE_BID;
    protected string $alias = Db::A_ABSENTEE_BID;

    /**
     * Filter by absentee_bid.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by absentee_bid.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by absentee_bid.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by absentee_bid.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by absentee_bid.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.max_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.max_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_bid', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.max_bid
     * @return static
     */
    public function groupByMaxBid(): static
    {
        $this->group($this->alias . '.max_bid');
        return $this;
    }

    /**
     * Order by absentee_bid.max_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.or_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.or_id', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.or_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.or_id', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.or_id
     * @return static
     */
    public function groupByOrId(): static
    {
        $this->group($this->alias . '.or_id');
        return $this;
    }

    /**
     * Order by absentee_bid.or_id
     * @param bool $ascending
     * @return static
     */
    public function orderByOrId(bool $ascending = true): static
    {
        $this->order($this->alias . '.or_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.or_id
     * @param int $filterValue
     * @return static
     */
    public function filterOrIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.or_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.or_id
     * @param int $filterValue
     * @return static
     */
    public function filterOrIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.or_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.or_id
     * @param int $filterValue
     * @return static
     */
    public function filterOrIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.or_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.or_id
     * @param int $filterValue
     * @return static
     */
    public function filterOrIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.or_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.bid_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBidType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_type', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.bid_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBidType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_type', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.bid_type
     * @return static
     */
    public function groupByBidType(): static
    {
        $this->group($this->alias . '.bid_type');
        return $this;
    }

    /**
     * Order by absentee_bid.bid_type
     * @param bool $ascending
     * @return static
     */
    public function orderByBidType(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.bid_type
     * @param int $filterValue
     * @return static
     */
    public function filterBidTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.bid_type
     * @param int $filterValue
     * @return static
     */
    public function filterBidTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.bid_type
     * @param int $filterValue
     * @return static
     */
    public function filterBidTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.bid_type
     * @param int $filterValue
     * @return static
     */
    public function filterBidTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.assigned_clerk
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAssignedClerk(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.assigned_clerk', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.assigned_clerk from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAssignedClerk(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.assigned_clerk', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.assigned_clerk
     * @return static
     */
    public function groupByAssignedClerk(): static
    {
        $this->group($this->alias . '.assigned_clerk');
        return $this;
    }

    /**
     * Order by absentee_bid.assigned_clerk
     * @param bool $ascending
     * @return static
     */
    public function orderByAssignedClerk(bool $ascending = true): static
    {
        $this->order($this->alias . '.assigned_clerk', $ascending);
        return $this;
    }

    /**
     * Filter absentee_bid.assigned_clerk by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAssignedClerk(string $filterValue): static
    {
        $this->like($this->alias . '.assigned_clerk', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by absentee_bid.placed_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPlacedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.placed_on', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.placed_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPlacedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.placed_on', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.placed_on
     * @return static
     */
    public function groupByPlacedOn(): static
    {
        $this->group($this->alias . '.placed_on');
        return $this;
    }

    /**
     * Order by absentee_bid.placed_on
     * @param bool $ascending
     * @return static
     */
    public function orderByPlacedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.placed_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.placed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPlacedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.placed_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.placed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPlacedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.placed_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.placed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPlacedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.placed_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.placed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPlacedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.placed_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by absentee_bid.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by absentee_bid.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by absentee_bid.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by absentee_bid.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by absentee_bid.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by absentee_bid.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter absentee_bid.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by absentee_bid.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by absentee_bid.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter absentee_bid.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by absentee_bid.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out absentee_bid.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by absentee_bid.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by absentee_bid.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than absentee_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than absentee_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than absentee_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than absentee_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
