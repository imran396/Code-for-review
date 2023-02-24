<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustField;

use LotItemCustField;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLotItemCustFieldReadRepository
 * @method LotItemCustField[] loadEntities()
 * @method LotItemCustField|null loadEntity()
 */
abstract class AbstractLotItemCustFieldReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_LOT_ITEM_CUST_FIELD;
    protected string $alias = Db::A_LOT_ITEM_CUST_FIELD;

    /**
     * Filter by lot_item_cust_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter lot_item_cust_field.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.type
     * @return static
     */
    public function groupByType(): static
    {
        $this->group($this->alias . '.type');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.type
     * @param bool $ascending
     * @return static
     */
    public function orderByType(bool $ascending = true): static
    {
        $this->order($this->alias . '.type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_catalog', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.in_catalog
     * @return static
     */
    public function groupByInCatalog(): static
    {
        $this->group($this->alias . '.in_catalog');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.in_catalog
     * @param bool $ascending
     * @return static
     */
    public function orderByInCatalog(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_catalog', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInCatalogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_catalog', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInCatalogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_catalog', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInCatalogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_catalog', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInCatalogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_catalog', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.search_field
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSearchField(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_field', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.search_field from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSearchField(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_field', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.search_field
     * @return static
     */
    public function groupBySearchField(): static
    {
        $this->group($this->alias . '.search_field');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.search_field
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchField(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_field', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.search_field
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchFieldGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_field', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.search_field
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchFieldGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_field', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.search_field
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchFieldLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_field', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.search_field
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchFieldLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_field', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.parameters
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterParameters(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parameters', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.parameters from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipParameters(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parameters', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.parameters
     * @return static
     */
    public function groupByParameters(): static
    {
        $this->group($this->alias . '.parameters');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.parameters
     * @param bool $ascending
     * @return static
     */
    public function orderByParameters(bool $ascending = true): static
    {
        $this->order($this->alias . '.parameters', $ascending);
        return $this;
    }

    /**
     * Filter lot_item_cust_field.parameters by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeParameters(string $filterValue): static
    {
        $this->like($this->alias . '.parameters', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.access', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.access', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.access
     * @return static
     */
    public function groupByAccess(): static
    {
        $this->group($this->alias . '.access');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.access
     * @param bool $ascending
     * @return static
     */
    public function orderByAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.access', $ascending);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.unique
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUnique(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.unique', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.unique from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUnique(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.unique', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.unique
     * @return static
     */
    public function groupByUnique(): static
    {
        $this->group($this->alias . '.unique');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.unique
     * @param bool $ascending
     * @return static
     */
    public function orderByUnique(bool $ascending = true): static
    {
        $this->order($this->alias . '.unique', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.unique
     * @param bool $filterValue
     * @return static
     */
    public function filterUniqueGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.unique', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.unique
     * @param bool $filterValue
     * @return static
     */
    public function filterUniqueGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.unique', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.unique
     * @param bool $filterValue
     * @return static
     */
    public function filterUniqueLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.unique', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.unique
     * @param bool $filterValue
     * @return static
     */
    public function filterUniqueLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.unique', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBarcode(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBarcode(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.barcode
     * @return static
     */
    public function groupByBarcode(): static
    {
        $this->group($this->alias . '.barcode');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.barcode
     * @param bool $ascending
     * @return static
     */
    public function orderByBarcode(bool $ascending = true): static
    {
        $this->order($this->alias . '.barcode', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.barcode
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.barcode
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.barcode
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.barcode
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBarcodeType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode_type', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBarcodeType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode_type', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.barcode_type
     * @return static
     */
    public function groupByBarcodeType(): static
    {
        $this->group($this->alias . '.barcode_type');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.barcode_type
     * @param bool $ascending
     * @return static
     */
    public function orderByBarcodeType(bool $ascending = true): static
    {
        $this->order($this->alias . '.barcode_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.barcode_type
     * @param int $filterValue
     * @return static
     */
    public function filterBarcodeTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.barcode_type
     * @param int $filterValue
     * @return static
     */
    public function filterBarcodeTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.barcode_type
     * @param int $filterValue
     * @return static
     */
    public function filterBarcodeTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.barcode_type
     * @param int $filterValue
     * @return static
     */
    public function filterBarcodeTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode_auto_populate
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulate(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode_auto_populate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode_auto_populate from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBarcodeAutoPopulate(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode_auto_populate', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.barcode_auto_populate
     * @return static
     */
    public function groupByBarcodeAutoPopulate(): static
    {
        $this->group($this->alias . '.barcode_auto_populate');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.barcode_auto_populate
     * @param bool $ascending
     * @return static
     */
    public function orderByBarcodeAutoPopulate(bool $ascending = true): static
    {
        $this->order($this->alias . '.barcode_auto_populate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.barcode_auto_populate
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulateGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_auto_populate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.barcode_auto_populate
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulateGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_auto_populate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.barcode_auto_populate
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulateLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_auto_populate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.barcode_auto_populate
     * @param bool $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulateLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.barcode_auto_populate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_invoices
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInInvoices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_invoices', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_invoices from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInInvoices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_invoices', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.in_invoices
     * @return static
     */
    public function groupByInInvoices(): static
    {
        $this->group($this->alias . '.in_invoices');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.in_invoices
     * @param bool $ascending
     * @return static
     */
    public function orderByInInvoices(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_invoices', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_settlements
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInSettlements(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_settlements', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_settlements from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInSettlements(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_settlements', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.in_settlements
     * @return static
     */
    public function groupByInSettlements(): static
    {
        $this->group($this->alias . '.in_settlements');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.in_settlements
     * @param bool $ascending
     * @return static
     */
    public function orderByInSettlements(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_settlements', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_admin_search
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminSearch(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_search', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_admin_search from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminSearch(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_search', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.in_admin_search
     * @return static
     */
    public function groupByInAdminSearch(): static
    {
        $this->group($this->alias . '.in_admin_search');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.in_admin_search
     * @param bool $ascending
     * @return static
     */
    public function orderByInAdminSearch(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_admin_search', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.search_index
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSearchIndex(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_index', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.search_index from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSearchIndex(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_index', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.search_index
     * @return static
     */
    public function groupBySearchIndex(): static
    {
        $this->group($this->alias . '.search_index');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.search_index
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchIndex(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_index', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.search_index
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchIndexGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_index', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.search_index
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchIndexGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_index', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.search_index
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchIndexLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_index', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.search_index
     * @param bool $filterValue
     * @return static
     */
    public function filterSearchIndexLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.search_index', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_admin_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_admin_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_catalog', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.in_admin_catalog
     * @return static
     */
    public function groupByInAdminCatalog(): static
    {
        $this->group($this->alias . '.in_admin_catalog');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.in_admin_catalog
     * @param bool $ascending
     * @return static
     */
    public function orderByInAdminCatalog(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_admin_catalog', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.fck_editor
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFckEditor(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fck_editor', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.fck_editor from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFckEditor(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fck_editor', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.fck_editor
     * @return static
     */
    public function groupByFckEditor(): static
    {
        $this->group($this->alias . '.fck_editor');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.fck_editor
     * @param bool $ascending
     * @return static
     */
    public function orderByFckEditor(bool $ascending = true): static
    {
        $this->order($this->alias . '.fck_editor', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.fck_editor
     * @param bool $filterValue
     * @return static
     */
    public function filterFckEditorGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.fck_editor', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.fck_editor
     * @param bool $filterValue
     * @return static
     */
    public function filterFckEditorGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.fck_editor', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.fck_editor
     * @param bool $filterValue
     * @return static
     */
    public function filterFckEditorLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.fck_editor', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.fck_editor
     * @param bool $filterValue
     * @return static
     */
    public function filterFckEditorLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.fck_editor', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.auto_complete
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoComplete(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_complete', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.auto_complete from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoComplete(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_complete', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.auto_complete
     * @return static
     */
    public function groupByAutoComplete(): static
    {
        $this->group($this->alias . '.auto_complete');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.auto_complete
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoComplete(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_complete', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.auto_complete
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCompleteGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_complete', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.auto_complete
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCompleteGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_complete', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.auto_complete
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCompleteLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_complete', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.auto_complete
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoCompleteLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_complete', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.lot_num_auto_fill
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotNumAutoFill(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num_auto_fill', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.lot_num_auto_fill from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotNumAutoFill(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num_auto_fill', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.lot_num_auto_fill
     * @return static
     */
    public function groupByLotNumAutoFill(): static
    {
        $this->group($this->alias . '.lot_num_auto_fill');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.lot_num_auto_fill
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNumAutoFill(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_num_auto_fill', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.lot_num_auto_fill
     * @param bool $filterValue
     * @return static
     */
    public function filterLotNumAutoFillGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_auto_fill', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.lot_num_auto_fill
     * @param bool $filterValue
     * @return static
     */
    public function filterLotNumAutoFillGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_auto_fill', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.lot_num_auto_fill
     * @param bool $filterValue
     * @return static
     */
    public function filterLotNumAutoFillLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_auto_fill', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.lot_num_auto_fill
     * @param bool $filterValue
     * @return static
     */
    public function filterLotNumAutoFillLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_auto_fill', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item_cust_field.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by lot_item_cust_field.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
