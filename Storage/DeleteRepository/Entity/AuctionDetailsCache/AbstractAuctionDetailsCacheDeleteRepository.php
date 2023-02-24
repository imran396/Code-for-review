<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionDetailsCache;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionDetailsCacheDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_DETAILS_CACHE;
    protected string $alias = Db::A_AUCTION_DETAILS_CACHE;

    /**
     * Filter by auction_details_cache.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.key
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterKey(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.key', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.key from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipKey(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.key', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.value
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterValue(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.value', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.value from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipValue(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.value', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.calculated_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterCalculatedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.calculated_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.calculated_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipCalculatedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.calculated_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_details_cache.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_details_cache.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
