<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxDefinitionRange;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use TaxDefinitionRange;

/**
 * Abstract class AbstractTaxDefinitionRangeReadRepository
 * @method TaxDefinitionRange[] loadEntities()
 * @method TaxDefinitionRange|null loadEntity()
 */
abstract class AbstractTaxDefinitionRangeReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_TAX_DEFINITION_RANGE;
    protected string $alias = Db::A_TAX_DEFINITION_RANGE;

    /**
     * Filter by tax_definition_range.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by tax_definition_range.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.tax_definition_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxDefinitionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_definition_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.tax_definition_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxDefinitionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_definition_id', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.tax_definition_id
     * @return static
     */
    public function groupByTaxDefinitionId(): static
    {
        $this->group($this->alias . '.tax_definition_id');
        return $this;
    }

    /**
     * Order by tax_definition_range.tax_definition_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxDefinitionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_definition_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDefinitionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_definition_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDefinitionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_definition_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDefinitionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_definition_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDefinitionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_definition_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by tax_definition_range.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.fixed
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterFixed(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fixed', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.fixed from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipFixed(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fixed', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.fixed
     * @return static
     */
    public function groupByFixed(): static
    {
        $this->group($this->alias . '.fixed');
        return $this;
    }

    /**
     * Order by tax_definition_range.fixed
     * @param bool $ascending
     * @return static
     */
    public function orderByFixed(bool $ascending = true): static
    {
        $this->order($this->alias . '.fixed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.percent
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterPercent(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.percent', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.percent from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipPercent(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.percent', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.percent
     * @return static
     */
    public function groupByPercent(): static
    {
        $this->group($this->alias . '.percent');
        return $this;
    }

    /**
     * Order by tax_definition_range.percent
     * @param bool $ascending
     * @return static
     */
    public function orderByPercent(bool $ascending = true): static
    {
        $this->order($this->alias . '.percent', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mode', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mode', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.mode
     * @return static
     */
    public function groupByMode(): static
    {
        $this->group($this->alias . '.mode');
        return $this;
    }

    /**
     * Order by tax_definition_range.mode
     * @param bool $ascending
     * @return static
     */
    public function orderByMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.mode', $ascending);
        return $this;
    }

    /**
     * Filter by tax_definition_range.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by tax_definition_range.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by tax_definition_range.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by tax_definition_range.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by tax_definition_range.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by tax_definition_range.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by tax_definition_range.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by tax_definition_range.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by tax_definition_range.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
