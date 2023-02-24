<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionFieldConfig;

use AuctionFieldConfig;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionFieldConfigReadRepository
 * @method AuctionFieldConfig[] loadEntities()
 * @method AuctionFieldConfig|null loadEntity()
 */
abstract class AbstractAuctionFieldConfigReadRepository extends ReadRepositoryBase
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
     * Group by auction_field_config.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction_field_config.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by auction_field_config.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by auction_field_config.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by auction_field_config.index
     * @return static
     */
    public function groupByIndex(): static
    {
        $this->group($this->alias . '.index');
        return $this;
    }

    /**
     * Order by auction_field_config.index
     * @param bool $ascending
     * @return static
     */
    public function orderByIndex(bool $ascending = true): static
    {
        $this->order($this->alias . '.index', $ascending);
        return $this;
    }

    /**
     * Filter auction_field_config.index by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIndex(string $filterValue): static
    {
        $this->like($this->alias . '.index', "%{$filterValue}%");
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
     * Group by auction_field_config.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by auction_field_config.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
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
     * Group by auction_field_config.visible
     * @return static
     */
    public function groupByVisible(): static
    {
        $this->group($this->alias . '.visible');
        return $this;
    }

    /**
     * Order by auction_field_config.visible
     * @param bool $ascending
     * @return static
     */
    public function orderByVisible(bool $ascending = true): static
    {
        $this->order($this->alias . '.visible', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.visible
     * @param bool $filterValue
     * @return static
     */
    public function filterVisibleGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.visible
     * @param bool $filterValue
     * @return static
     */
    public function filterVisibleGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.visible
     * @param bool $filterValue
     * @return static
     */
    public function filterVisibleLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.visible
     * @param bool $filterValue
     * @return static
     */
    public function filterVisibleLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible', $filterValue, '<=');
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
     * Group by auction_field_config.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by auction_field_config.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by auction_field_config.required
     * @return static
     */
    public function groupByRequired(): static
    {
        $this->group($this->alias . '.required');
        return $this;
    }

    /**
     * Order by auction_field_config.required
     * @param bool $ascending
     * @return static
     */
    public function orderByRequired(bool $ascending = true): static
    {
        $this->order($this->alias . '.required', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<=');
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
     * Group by auction_field_config.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_field_config.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by auction_field_config.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_field_config.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by auction_field_config.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_field_config.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by auction_field_config.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_field_config.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by auction_field_config.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_field_config.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_field_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_field_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_field_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_field_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
