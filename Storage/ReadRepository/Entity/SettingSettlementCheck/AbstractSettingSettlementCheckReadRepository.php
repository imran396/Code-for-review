<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSettlementCheck;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSettlementCheck;

/**
 * Abstract class AbstractSettingSettlementCheckReadRepository
 * @method SettingSettlementCheck[] loadEntities()
 * @method SettingSettlementCheck|null loadEntity()
 */
abstract class AbstractSettingSettlementCheckReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SETTLEMENT_CHECK;
    protected string $alias = Db::A_SETTING_SETTLEMENT_CHECK;

    /**
     * Filter by setting_settlement_check.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_settlement_check.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_settlement_check.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStlmCheckFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_file', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStlmCheckFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_file', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_file
     * @return static
     */
    public function groupByStlmCheckFile(): static
    {
        $this->group($this->alias . '.stlm_check_file');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_file
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckFile(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_file', $ascending);
        return $this;
    }

    /**
     * Filter setting_settlement_check.stlm_check_file by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStlmCheckFile(string $filterValue): static
    {
        $this->like($this->alias . '.stlm_check_file', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_name_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_name_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_name_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckNameCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_name_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_name_coord_x
     * @return static
     */
    public function groupByStlmCheckNameCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_name_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_name_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckNameCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_name_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_name_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_name_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_name_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_name_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_name_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_name_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_name_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckNameCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_name_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_name_coord_y
     * @return static
     */
    public function groupByStlmCheckNameCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_name_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_name_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckNameCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_name_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_name_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_name_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_name_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_name_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_name_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_amount_coord_x
     * @return static
     */
    public function groupByStlmCheckAmountCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_amount_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_amount_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAmountCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_amount_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_amount_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_amount_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_amount_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_amount_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_amount_coord_y
     * @return static
     */
    public function groupByStlmCheckAmountCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_amount_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_amount_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAmountCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_amount_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_amount_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_amount_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_amount_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_amount_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_date_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_date_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_date_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckDateCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_date_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_date_coord_x
     * @return static
     */
    public function groupByStlmCheckDateCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_date_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_date_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckDateCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_date_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_date_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_date_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_date_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_date_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_date_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_date_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_date_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckDateCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_date_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_date_coord_y
     * @return static
     */
    public function groupByStlmCheckDateCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_date_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_date_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckDateCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_date_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_date_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_date_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_date_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_date_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_date_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_memo_coord_x
     * @return static
     */
    public function groupByStlmCheckMemoCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_memo_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_memo_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckMemoCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_memo_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_memo_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_memo_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_memo_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_memo_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_memo_coord_y
     * @return static
     */
    public function groupByStlmCheckMemoCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_memo_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_memo_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckMemoCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_memo_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_memo_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_memo_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_memo_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_memo_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_memo_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_address_coord_x
     * @return static
     */
    public function groupByStlmCheckAddressCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_address_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_address_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAddressCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_address_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_address_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_address_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_address_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_address_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_address_coord_y
     * @return static
     */
    public function groupByStlmCheckAddressCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_address_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_address_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAddressCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_address_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_address_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_address_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_address_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_address_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_address_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_spelling_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountSpellingCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_spelling_coord_x', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @return static
     */
    public function groupByStlmCheckAmountSpellingCoordX(): static
    {
        $this->group($this->alias . '.stlm_check_amount_spelling_coord_x');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAmountSpellingCoordX(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_amount_spelling_coord_x', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordXGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordXGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordXLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordXLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_spelling_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountSpellingCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_spelling_coord_y', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @return static
     */
    public function groupByStlmCheckAmountSpellingCoordY(): static
    {
        $this->group($this->alias . '.stlm_check_amount_spelling_coord_y');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAmountSpellingCoordY(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_amount_spelling_coord_y', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordYGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordYGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordYLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordYLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_height
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckHeight(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_height', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_height from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckHeight(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_height', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_height
     * @return static
     */
    public function groupByStlmCheckHeight(): static
    {
        $this->group($this->alias . '.stlm_check_height');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_height
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckHeight(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_height', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_height
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckHeightGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_height', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_height
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckHeightGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_height', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_height
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckHeightLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_height', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_height
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckHeightLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_height', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_per_page
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStlmCheckPerPage(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_per_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_per_page from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStlmCheckPerPage(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_per_page', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_per_page
     * @return static
     */
    public function groupByStlmCheckPerPage(): static
    {
        $this->group($this->alias . '.stlm_check_per_page');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_per_page
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckPerPage(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_per_page', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckPerPageGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_per_page', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckPerPageGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_per_page', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckPerPageLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_per_page', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckPerPageLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_per_page', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_repeat_count
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCount(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_repeat_count', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_repeat_count from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStlmCheckRepeatCount(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_repeat_count', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_repeat_count
     * @return static
     */
    public function groupByStlmCheckRepeatCount(): static
    {
        $this->group($this->alias . '.stlm_check_repeat_count');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_repeat_count
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckRepeatCount(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_repeat_count', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_repeat_count
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCountGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_repeat_count', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_repeat_count
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCountGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_repeat_count', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_repeat_count
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCountLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_repeat_count', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_repeat_count
     * @param int $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCountLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_repeat_count', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_address_template
     * @return static
     */
    public function groupByStlmCheckAddressTemplate(): static
    {
        $this->group($this->alias . '.stlm_check_address_template');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_address_template
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckAddressTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_address_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_settlement_check.stlm_check_address_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStlmCheckAddressTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.stlm_check_address_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_payee_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckPayeeTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_payee_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_payee_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckPayeeTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_payee_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_payee_template
     * @return static
     */
    public function groupByStlmCheckPayeeTemplate(): static
    {
        $this->group($this->alias . '.stlm_check_payee_template');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_payee_template
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckPayeeTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_payee_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_settlement_check.stlm_check_payee_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStlmCheckPayeeTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.stlm_check_payee_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_memo_template
     * @return static
     */
    public function groupByStlmCheckMemoTemplate(): static
    {
        $this->group($this->alias . '.stlm_check_memo_template');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_memo_template
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckMemoTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_memo_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_settlement_check.stlm_check_memo_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStlmCheckMemoTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.stlm_check_memo_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterStlmCheckEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipStlmCheckEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.stlm_check_enabled
     * @return static
     */
    public function groupByStlmCheckEnabled(): static
    {
        $this->group($this->alias . '.stlm_check_enabled');
        return $this;
    }

    /**
     * Order by setting_settlement_check.stlm_check_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByStlmCheckEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.stlm_check_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.stlm_check_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterStlmCheckEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.stlm_check_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterStlmCheckEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.stlm_check_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterStlmCheckEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.stlm_check_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterStlmCheckEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stlm_check_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_settlement_check.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_settlement_check.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_settlement_check.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_settlement_check.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_settlement_check.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_settlement_check.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_settlement_check.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
