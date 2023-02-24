<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionFieldConfig;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionFieldConfigDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_FIELD_CONFIG;
    protected string $alias = Db::A_AUCTION_FIELD_CONFIG;

    /**
     * Filter by auction_field_config.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.index
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIndex(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.index', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.index from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIndex(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.index', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.visible
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterVisible(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.visible', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.visible from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipVisible(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.visible', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.required
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequired(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.required', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.required from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequired(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.required', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_field_config.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_field_config.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
