<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ActionQueue;

use ActionQueue;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractActionQueueReadRepository
 * @method ActionQueue[] loadEntities()
 * @method ActionQueue|null loadEntity()
 */
abstract class AbstractActionQueueReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_ACTION_QUEUE;
    protected string $alias = Db::A_ACTION_QUEUE;

    /**
     * Filter by action_queue.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by action_queue.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.priority
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPriority(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.priority', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.priority from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPriority(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.priority', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.priority
     * @return static
     */
    public function groupByPriority(): static
    {
        $this->group($this->alias . '.priority');
        return $this;
    }

    /**
     * Order by action_queue.priority
     * @param bool $ascending
     * @return static
     */
    public function orderByPriority(bool $ascending = true): static
    {
        $this->order($this->alias . '.priority', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.priority
     * @param int $filterValue
     * @return static
     */
    public function filterPriorityGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.priority', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.priority
     * @param int $filterValue
     * @return static
     */
    public function filterPriorityGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.priority', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.priority
     * @param int $filterValue
     * @return static
     */
    public function filterPriorityLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.priority', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.priority
     * @param int $filterValue
     * @return static
     */
    public function filterPriorityLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.priority', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.max_attempts
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterMaxAttempts(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.max_attempts', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.max_attempts from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipMaxAttempts(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.max_attempts', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.max_attempts
     * @return static
     */
    public function groupByMaxAttempts(): static
    {
        $this->group($this->alias . '.max_attempts');
        return $this;
    }

    /**
     * Order by action_queue.max_attempts
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxAttempts(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_attempts', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.max_attempts
     * @param int $filterValue
     * @return static
     */
    public function filterMaxAttemptsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_attempts', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.max_attempts
     * @param int $filterValue
     * @return static
     */
    public function filterMaxAttemptsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_attempts', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.max_attempts
     * @param int $filterValue
     * @return static
     */
    public function filterMaxAttemptsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_attempts', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.max_attempts
     * @param int $filterValue
     * @return static
     */
    public function filterMaxAttemptsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_attempts', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.attempts
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAttempts(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.attempts', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.attempts from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAttempts(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.attempts', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.attempts
     * @return static
     */
    public function groupByAttempts(): static
    {
        $this->group($this->alias . '.attempts');
        return $this;
    }

    /**
     * Order by action_queue.attempts
     * @param bool $ascending
     * @return static
     */
    public function orderByAttempts(bool $ascending = true): static
    {
        $this->order($this->alias . '.attempts', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.attempts
     * @param int $filterValue
     * @return static
     */
    public function filterAttemptsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.attempts', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.attempts
     * @param int $filterValue
     * @return static
     */
    public function filterAttemptsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.attempts', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.attempts
     * @param int $filterValue
     * @return static
     */
    public function filterAttemptsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.attempts', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.attempts
     * @param int $filterValue
     * @return static
     */
    public function filterAttemptsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.attempts', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.action_handler
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterActionHandler(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.action_handler', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.action_handler from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipActionHandler(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.action_handler', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.action_handler
     * @return static
     */
    public function groupByActionHandler(): static
    {
        $this->group($this->alias . '.action_handler');
        return $this;
    }

    /**
     * Order by action_queue.action_handler
     * @param bool $ascending
     * @return static
     */
    public function orderByActionHandler(bool $ascending = true): static
    {
        $this->order($this->alias . '.action_handler', $ascending);
        return $this;
    }

    /**
     * Filter action_queue.action_handler by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeActionHandler(string $filterValue): static
    {
        $this->like($this->alias . '.action_handler', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by action_queue.group
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroup(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.group from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroup(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.group
     * @return static
     */
    public function groupByGroup(): static
    {
        $this->group($this->alias . '.group');
        return $this;
    }

    /**
     * Order by action_queue.group
     * @param bool $ascending
     * @return static
     */
    public function orderByGroup(bool $ascending = true): static
    {
        $this->order($this->alias . '.group', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.group
     * @param int $filterValue
     * @return static
     */
    public function filterGroupGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.group
     * @param int $filterValue
     * @return static
     */
    public function filterGroupGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.group
     * @param int $filterValue
     * @return static
     */
    public function filterGroupLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.group
     * @param int $filterValue
     * @return static
     */
    public function filterGroupLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.identifier
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIdentifier(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identifier', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.identifier from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIdentifier(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identifier', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.identifier
     * @return static
     */
    public function groupByIdentifier(): static
    {
        $this->group($this->alias . '.identifier');
        return $this;
    }

    /**
     * Order by action_queue.identifier
     * @param bool $ascending
     * @return static
     */
    public function orderByIdentifier(bool $ascending = true): static
    {
        $this->order($this->alias . '.identifier', $ascending);
        return $this;
    }

    /**
     * Filter action_queue.identifier by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIdentifier(string $filterValue): static
    {
        $this->like($this->alias . '.identifier', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by action_queue.data
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterData(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.data', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.data from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipData(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.data', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.data
     * @return static
     */
    public function groupByData(): static
    {
        $this->group($this->alias . '.data');
        return $this;
    }

    /**
     * Order by action_queue.data
     * @param bool $ascending
     * @return static
     */
    public function orderByData(bool $ascending = true): static
    {
        $this->order($this->alias . '.data', $ascending);
        return $this;
    }

    /**
     * Filter action_queue.data by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeData(string $filterValue): static
    {
        $this->like($this->alias . '.data', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by action_queue.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by action_queue.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.created_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.created_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by action_queue.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by action_queue.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by action_queue.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by action_queue.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out action_queue.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by action_queue.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by action_queue.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than action_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than action_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than action_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than action_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
