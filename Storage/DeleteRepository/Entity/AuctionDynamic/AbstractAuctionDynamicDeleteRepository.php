<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionDynamic;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionDynamicDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_DYNAMIC;
    protected string $alias = Db::A_AUCTION_DYNAMIC;

    /**
     * Filter by auction_dynamic.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.extend_all_start_closing_date
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterExtendAllStartClosingDate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_all_start_closing_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.extend_all_start_closing_date from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipExtendAllStartClosingDate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_all_start_closing_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_dynamic.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_dynamic.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
