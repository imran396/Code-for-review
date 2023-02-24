<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategory;

use LotCategory;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLotCategoryReadRepository
 * @method LotCategory[] loadEntities()
 * @method LotCategory|null loadEntity()
 */
abstract class AbstractLotCategoryReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_LOT_CATEGORY;
    protected string $alias = Db::A_LOT_CATEGORY;

    /**
     * Filter by lot_category.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by lot_category.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by lot_category.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter lot_category.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_category.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by lot_category.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by lot_category.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by lot_category.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by lot_category.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by lot_category.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.parent_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterParentId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.parent_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.parent_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipParentId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.parent_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.parent_id
     * @return static
     */
    public function groupByParentId(): static
    {
        $this->group($this->alias . '.parent_id');
        return $this;
    }

    /**
     * Order by lot_category.parent_id
     * @param bool $ascending
     * @return static
     */
    public function orderByParentId(bool $ascending = true): static
    {
        $this->order($this->alias . '.parent_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.parent_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.parent_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.parent_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.parent_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.level
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLevel(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.level', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.level from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLevel(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.level', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.level
     * @return static
     */
    public function groupByLevel(): static
    {
        $this->group($this->alias . '.level');
        return $this;
    }

    /**
     * Order by lot_category.level
     * @param bool $ascending
     * @return static
     */
    public function orderByLevel(bool $ascending = true): static
    {
        $this->order($this->alias . '.level', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.sibling_order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSiblingOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sibling_order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.sibling_order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSiblingOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sibling_order', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.sibling_order
     * @return static
     */
    public function groupBySiblingOrder(): static
    {
        $this->group($this->alias . '.sibling_order');
        return $this;
    }

    /**
     * Order by lot_category.sibling_order
     * @param bool $ascending
     * @return static
     */
    public function orderBySiblingOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.sibling_order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.sibling_order
     * @param int $filterValue
     * @return static
     */
    public function filterSiblingOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sibling_order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.sibling_order
     * @param int $filterValue
     * @return static
     */
    public function filterSiblingOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sibling_order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.sibling_order
     * @param int $filterValue
     * @return static
     */
    public function filterSiblingOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sibling_order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.sibling_order
     * @param int $filterValue
     * @return static
     */
    public function filterSiblingOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sibling_order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.global_order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGlobalOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.global_order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.global_order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGlobalOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.global_order', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.global_order
     * @return static
     */
    public function groupByGlobalOrder(): static
    {
        $this->group($this->alias . '.global_order');
        return $this;
    }

    /**
     * Order by lot_category.global_order
     * @param bool $ascending
     * @return static
     */
    public function orderByGlobalOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.global_order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.global_order
     * @param int $filterValue
     * @return static
     */
    public function filterGlobalOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.global_order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.global_order
     * @param int $filterValue
     * @return static
     */
    public function filterGlobalOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.global_order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.global_order
     * @param int $filterValue
     * @return static
     */
    public function filterGlobalOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.global_order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.global_order
     * @param int $filterValue
     * @return static
     */
    public function filterGlobalOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.global_order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.buy_now_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyNowAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.buy_now_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyNowAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_amount', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.buy_now_amount
     * @return static
     */
    public function groupByBuyNowAmount(): static
    {
        $this->group($this->alias . '.buy_now_amount');
        return $this;
    }

    /**
     * Order by lot_category.buy_now_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.starting_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterStartingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.starting_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipStartingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.starting_bid
     * @return static
     */
    public function groupByStartingBid(): static
    {
        $this->group($this->alias . '.starting_bid');
        return $this;
    }

    /**
     * Order by lot_category.starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.quantity_digits
     * @return static
     */
    public function groupByQuantityDigits(): static
    {
        $this->group($this->alias . '.quantity_digits');
        return $this;
    }

    /**
     * Order by lot_category.quantity_digits
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityDigits(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_digits', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.image_link
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterImageLink(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_link', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.image_link from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipImageLink(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_link', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.image_link
     * @return static
     */
    public function groupByImageLink(): static
    {
        $this->group($this->alias . '.image_link');
        return $this;
    }

    /**
     * Order by lot_category.image_link
     * @param bool $ascending
     * @return static
     */
    public function orderByImageLink(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_link', $ascending);
        return $this;
    }

    /**
     * Filter lot_category.image_link by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeImageLink(string $filterValue): static
    {
        $this->like($this->alias . '.image_link', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_category.consignment_commission
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignmentCommission(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignment_commission', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.consignment_commission from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignmentCommission(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignment_commission', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.consignment_commission
     * @return static
     */
    public function groupByConsignmentCommission(): static
    {
        $this->group($this->alias . '.consignment_commission');
        return $this;
    }

    /**
     * Order by lot_category.consignment_commission
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignmentCommission(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignment_commission', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.hide_empty_fields
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideEmptyFields(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_empty_fields', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.hide_empty_fields from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideEmptyFields(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_empty_fields', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.hide_empty_fields
     * @return static
     */
    public function groupByHideEmptyFields(): static
    {
        $this->group($this->alias . '.hide_empty_fields');
        return $this;
    }

    /**
     * Order by lot_category.hide_empty_fields
     * @param bool $ascending
     * @return static
     */
    public function orderByHideEmptyFields(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_empty_fields', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.child_count
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterChildCount(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.child_count', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.child_count from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipChildCount(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.child_count', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.child_count
     * @return static
     */
    public function groupByChildCount(): static
    {
        $this->group($this->alias . '.child_count');
        return $this;
    }

    /**
     * Order by lot_category.child_count
     * @param bool $ascending
     * @return static
     */
    public function orderByChildCount(bool $ascending = true): static
    {
        $this->order($this->alias . '.child_count', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.child_count
     * @param int $filterValue
     * @return static
     */
    public function filterChildCountGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.child_count', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.child_count
     * @param int $filterValue
     * @return static
     */
    public function filterChildCountGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.child_count', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.child_count
     * @param int $filterValue
     * @return static
     */
    public function filterChildCountLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.child_count', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.child_count
     * @param int $filterValue
     * @return static
     */
    public function filterChildCountLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.child_count', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by lot_category.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
