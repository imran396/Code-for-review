<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryTemplate;

use LotCategoryTemplate;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLotCategoryTemplateReadRepository
 * @method LotCategoryTemplate[] loadEntities()
 * @method LotCategoryTemplate|null loadEntity()
 */
abstract class AbstractLotCategoryTemplateReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_LOT_CATEGORY_TEMPLATE;
    protected string $alias = Db::A_LOT_CATEGORY_TEMPLATE;

    /**
     * Filter by lot_category_template.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by lot_category_template.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by lot_category_template.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.lot_category_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotCategoryId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.lot_category_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotCategoryId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_category_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.lot_category_id
     * @return static
     */
    public function groupByLotCategoryId(): static
    {
        $this->group($this->alias . '.lot_category_id');
        return $this;
    }

    /**
     * Order by lot_category_template.lot_category_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotCategoryId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_category_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.lot_category_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCategoryIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.lot_category_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCategoryIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.lot_category_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCategoryIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.lot_category_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCategoryIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.lot_item_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotItemDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.lot_item_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotItemDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_detail_template', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.lot_item_detail_template
     * @return static
     */
    public function groupByLotItemDetailTemplate(): static
    {
        $this->group($this->alias . '.lot_item_detail_template');
        return $this;
    }

    /**
     * Order by lot_category_template.lot_item_detail_template
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemDetailTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_detail_template', $ascending);
        return $this;
    }

    /**
     * Filter lot_category_template.lot_item_detail_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotItemDetailTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.lot_item_detail_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_category_template.rtb_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRtbDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.rtb_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRtbDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_detail_template', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.rtb_detail_template
     * @return static
     */
    public function groupByRtbDetailTemplate(): static
    {
        $this->group($this->alias . '.rtb_detail_template');
        return $this;
    }

    /**
     * Order by lot_category_template.rtb_detail_template
     * @param bool $ascending
     * @return static
     */
    public function orderByRtbDetailTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.rtb_detail_template', $ascending);
        return $this;
    }

    /**
     * Filter lot_category_template.rtb_detail_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRtbDetailTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.rtb_detail_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_category_template.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by lot_category_template.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by lot_category_template.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by lot_category_template.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by lot_category_template.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_category_template.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_category_template.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by lot_category_template.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_category_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_category_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_category_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_category_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
