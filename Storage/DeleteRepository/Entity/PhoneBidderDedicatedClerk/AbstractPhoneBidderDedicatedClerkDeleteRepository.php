<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\PhoneBidderDedicatedClerk;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractPhoneBidderDedicatedClerkDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_PHONE_BIDDER_DEDICATED_CLERK;
    protected string $alias = Db::A_PHONE_BIDDER_DEDICATED_CLERK;

    /**
     * Filter by phone_bidder_dedicated_clerk.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.assigned_clerk
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAssignedClerk(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.assigned_clerk', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.assigned_clerk from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAssignedClerk(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.assigned_clerk', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.bidder_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBidderId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.bidder_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBidderId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_id', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.created_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.created_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by phone_bidder_dedicated_clerk.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out phone_bidder_dedicated_clerk.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }
}
