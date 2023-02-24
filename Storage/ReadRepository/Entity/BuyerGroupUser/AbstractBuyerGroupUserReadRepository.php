<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyerGroupUser;

use BuyerGroupUser;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractBuyerGroupUserReadRepository
 * @method BuyerGroupUser[] loadEntities()
 * @method BuyerGroupUser|null loadEntity()
 */
abstract class AbstractBuyerGroupUserReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_BUYER_GROUP_USER;
    protected string $alias = Db::A_BUYER_GROUP_USER;

    /**
     * Filter by buyer_group_user.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by buyer_group_user.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.buyer_group_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBuyerGroupId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_group_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.buyer_group_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBuyerGroupId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_group_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.buyer_group_id
     * @return static
     */
    public function groupByBuyerGroupId(): static
    {
        $this->group($this->alias . '.buyer_group_id');
        return $this;
    }

    /**
     * Order by buyer_group_user.buyer_group_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerGroupId(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_group_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.buyer_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerGroupIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_group_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.buyer_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerGroupIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_group_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.buyer_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerGroupIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_group_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.buyer_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyerGroupIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_group_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by buyer_group_user.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by buyer_group_user.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.added_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAddedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.added_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.added_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAddedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.added_by', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.added_by
     * @return static
     */
    public function groupByAddedBy(): static
    {
        $this->group($this->alias . '.added_by');
        return $this;
    }

    /**
     * Order by buyer_group_user.added_by
     * @param bool $ascending
     * @return static
     */
    public function orderByAddedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.added_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.added_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAddedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.added_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.added_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAddedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.added_on', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.added_on
     * @return static
     */
    public function groupByAddedOn(): static
    {
        $this->group($this->alias . '.added_on');
        return $this;
    }

    /**
     * Order by buyer_group_user.added_on
     * @param bool $ascending
     * @return static
     */
    public function orderByAddedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.added_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.added_on
     * @param string $filterValue
     * @return static
     */
    public function filterAddedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.added_on
     * @param string $filterValue
     * @return static
     */
    public function filterAddedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.added_on
     * @param string $filterValue
     * @return static
     */
    public function filterAddedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.added_on
     * @param string $filterValue
     * @return static
     */
    public function filterAddedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by buyer_group_user.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by buyer_group_user.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by buyer_group_user.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by buyer_group_user.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyer_group_user.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by buyer_group_user.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by buyer_group_user.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyer_group_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyer_group_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyer_group_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyer_group_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
