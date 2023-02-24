<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\IndexQueue;

use IndexQueue;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractIndexQueueReadRepository
 * @method IndexQueue[] loadEntities()
 * @method IndexQueue|null loadEntity()
 */
abstract class AbstractIndexQueueReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INDEX_QUEUE;
    protected string $alias = Db::A_INDEX_QUEUE;

    /**
     * Filter by index_queue.entity_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_type', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.entity_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_type', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.entity_type
     * @return static
     */
    public function groupByEntityType(): static
    {
        $this->group($this->alias . '.entity_type');
        return $this;
    }

    /**
     * Order by index_queue.entity_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityType(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.entity_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_id', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.entity_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_id', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.entity_id
     * @return static
     */
    public function groupByEntityId(): static
    {
        $this->group($this->alias . '.entity_id');
        return $this;
    }

    /**
     * Order by index_queue.entity_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityId(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by index_queue.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by index_queue.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by index_queue.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by index_queue.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by index_queue.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out index_queue.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by index_queue.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by index_queue.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than index_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than index_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than index_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than index_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
