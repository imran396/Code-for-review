<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearchCategory;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractMySearchCategoryDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_MY_SEARCH_CATEGORY;
    protected string $alias = Db::A_MY_SEARCH_CATEGORY;

    /**
     * Filter by my_search_category.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.my_search_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterMySearchId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.my_search_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.my_search_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipMySearchId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.my_search_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.category_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCategoryId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.category_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCategoryId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.category_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search_category.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search_category.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
