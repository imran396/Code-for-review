<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\EntitySync;

use EntitySync;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractEntitySyncReadRepository
 * @method EntitySync[] loadEntities()
 * @method EntitySync|null loadEntity()
 */
abstract class AbstractEntitySyncReadRepository extends ReadRepositoryBase
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
     * Group by entity_sync.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by entity_sync.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by entity_sync.entity_id
     * @return static
     */
    public function groupByEntityId(): static
    {
        $this->group($this->alias . '.entity_id');
        return $this;
    }

    /**
     * Order by entity_sync.entity_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityId(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<=');
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
     * Group by entity_sync.entity_type
     * @return static
     */
    public function groupByEntityType(): static
    {
        $this->group($this->alias . '.entity_type');
        return $this;
    }

    /**
     * Order by entity_sync.entity_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityType(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<=');
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
     * Group by entity_sync.sync_namespace_id
     * @return static
     */
    public function groupBySyncNamespaceId(): static
    {
        $this->group($this->alias . '.sync_namespace_id');
        return $this;
    }

    /**
     * Order by entity_sync.sync_namespace_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySyncNamespaceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.sync_namespace_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.sync_namespace_id
     * @param int $filterValue
     * @return static
     */
    public function filterSyncNamespaceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sync_namespace_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.sync_namespace_id
     * @param int $filterValue
     * @return static
     */
    public function filterSyncNamespaceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sync_namespace_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.sync_namespace_id
     * @param int $filterValue
     * @return static
     */
    public function filterSyncNamespaceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sync_namespace_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.sync_namespace_id
     * @param int $filterValue
     * @return static
     */
    public function filterSyncNamespaceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sync_namespace_id', $filterValue, '<=');
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
     * Group by entity_sync.key
     * @return static
     */
    public function groupByKey(): static
    {
        $this->group($this->alias . '.key');
        return $this;
    }

    /**
     * Order by entity_sync.key
     * @param bool $ascending
     * @return static
     */
    public function orderByKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.key', $ascending);
        return $this;
    }

    /**
     * Filter entity_sync.key by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeKey(string $filterValue): static
    {
        $this->like($this->alias . '.key', "%{$filterValue}%");
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
     * Group by entity_sync.last_sync_in
     * @return static
     */
    public function groupByLastSyncIn(): static
    {
        $this->group($this->alias . '.last_sync_in');
        return $this;
    }

    /**
     * Order by entity_sync.last_sync_in
     * @param bool $ascending
     * @return static
     */
    public function orderByLastSyncIn(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_sync_in', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.last_sync_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncInGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_in', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.last_sync_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncInGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_in', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.last_sync_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncInLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_in', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.last_sync_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncInLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_in', $filterValue, '<=');
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
     * Group by entity_sync.last_sync_out
     * @return static
     */
    public function groupByLastSyncOut(): static
    {
        $this->group($this->alias . '.last_sync_out');
        return $this;
    }

    /**
     * Order by entity_sync.last_sync_out
     * @param bool $ascending
     * @return static
     */
    public function orderByLastSyncOut(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_sync_out', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.last_sync_out
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncOutGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_out', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.last_sync_out
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncOutGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_out', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.last_sync_out
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncOutLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_out', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.last_sync_out
     * @param string $filterValue
     * @return static
     */
    public function filterLastSyncOutLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_sync_out', $filterValue, '<=');
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
     * Group by entity_sync.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by entity_sync.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by entity_sync.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by entity_sync.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by entity_sync.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by entity_sync.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by entity_sync.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by entity_sync.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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

    /**
     * Group by entity_sync.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by entity_sync.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than entity_sync.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than entity_sync.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than entity_sync.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than entity_sync.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
