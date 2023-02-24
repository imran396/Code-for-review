<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AbsenteeBid;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAbsenteeBidDeleteRepository extends DeleteRepositoryBase
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
}
