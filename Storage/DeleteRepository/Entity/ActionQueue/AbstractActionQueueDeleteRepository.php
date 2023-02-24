<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ActionQueue;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractActionQueueDeleteRepository extends DeleteRepositoryBase
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
}
