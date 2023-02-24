<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MySearchCustom;

use MySearchCustom;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractMySearchCustomReadRepository
 * @method MySearchCustom[] loadEntities()
 * @method MySearchCustom|null loadEntity()
 */
abstract class AbstractMySearchCustomReadRepository extends ReadRepositoryBase
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
     * Group by my_search_custom.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by my_search_custom.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by my_search_custom.my_search_id
     * @return static
     */
    public function groupByMySearchId(): static
    {
        $this->group($this->alias . '.my_search_id');
        return $this;
    }

    /**
     * Order by my_search_custom.my_search_id
     * @param bool $ascending
     * @return static
     */
    public function orderByMySearchId(bool $ascending = true): static
    {
        $this->order($this->alias . '.my_search_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.my_search_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.my_search_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.my_search_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.my_search_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_id', $filterValue, '<=');
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
     * Group by my_search_custom.lot_item_cust_field_id
     * @return static
     */
    public function groupByLotItemCustFieldId(): static
    {
        $this->group($this->alias . '.lot_item_cust_field_id');
        return $this;
    }

    /**
     * Order by my_search_custom.lot_item_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.lot_item_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.lot_item_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.lot_item_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.lot_item_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_field_id', $filterValue, '<=');
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
     * Group by my_search_custom.min_field
     * @return static
     */
    public function groupByMinField(): static
    {
        $this->group($this->alias . '.min_field');
        return $this;
    }

    /**
     * Order by my_search_custom.min_field
     * @param bool $ascending
     * @return static
     */
    public function orderByMinField(bool $ascending = true): static
    {
        $this->order($this->alias . '.min_field', $ascending);
        return $this;
    }

    /**
     * Filter my_search_custom.min_field by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeMinField(string $filterValue): static
    {
        $this->like($this->alias . '.min_field', "%{$filterValue}%");
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
     * Group by my_search_custom.max_field
     * @return static
     */
    public function groupByMaxField(): static
    {
        $this->group($this->alias . '.max_field');
        return $this;
    }

    /**
     * Order by my_search_custom.max_field
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxField(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_field', $ascending);
        return $this;
    }

    /**
     * Filter my_search_custom.max_field by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeMaxField(string $filterValue): static
    {
        $this->like($this->alias . '.max_field', "%{$filterValue}%");
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
     * Group by my_search_custom.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by my_search_custom.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by my_search_custom.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by my_search_custom.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by my_search_custom.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by my_search_custom.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by my_search_custom.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by my_search_custom.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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

    /**
     * Group by my_search_custom.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by my_search_custom.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search_custom.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search_custom.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search_custom.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search_custom.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
