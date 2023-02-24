<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearchCustom;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractMySearchCustomDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_MY_SEARCH_CUSTOM;
    protected string $alias = Db::A_MY_SEARCH_CUSTOM;

    /**
     * Filter by my_search_custom.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.my_search_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMySearchId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.my_search_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.my_search_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMySearchId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.my_search_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.lot_item_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.lot_item_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.min_field
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMinField(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.min_field', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.min_field from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMinField(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.min_field', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.max_field
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMaxField(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.max_field', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.max_field from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMaxField(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.max_field', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_custom.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_custom.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
