<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\EntitySync;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractEntitySyncDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_ENTITY_SYNC;
    protected string $alias = Db::A_ENTITY_SYNC;

    /**
     * Filter by entity_sync.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.entity_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_id', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.entity_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_id', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.entity_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_type', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.entity_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_type', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.sync_namespace_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterSyncNamespaceId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sync_namespace_id', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.sync_namespace_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipSyncNamespaceId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sync_namespace_id', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.key', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.key', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.last_sync_in
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastSyncIn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_sync_in', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.last_sync_in from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastSyncIn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_sync_in', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.last_sync_out
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastSyncOut(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_sync_out', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.last_sync_out from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastSyncOut(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_sync_out', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.created_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.created_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by entity_sync.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out entity_sync.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
