<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CachedQueue;

use CachedQueue;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractCachedQueueReadRepository
 * @method CachedQueue[] loadEntities()
 * @method CachedQueue|null loadEntity()
 */
abstract class AbstractCachedQueueReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_CACHED_QUEUE;
    protected string $alias = Db::A_CACHED_QUEUE;

    /**
     * Filter by cached_queue.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by cached_queue.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by cached_queue.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.type
     * @return static
     */
    public function groupByType(): static
    {
        $this->group($this->alias . '.type');
        return $this;
    }

    /**
     * Order by cached_queue.type
     * @param bool $ascending
     * @return static
     */
    public function orderByType(bool $ascending = true): static
    {
        $this->order($this->alias . '.type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.file_path
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFilePath(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.file_path', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.file_path from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFilePath(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.file_path', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.file_path
     * @return static
     */
    public function groupByFilePath(): static
    {
        $this->group($this->alias . '.file_path');
        return $this;
    }

    /**
     * Order by cached_queue.file_path
     * @param bool $ascending
     * @return static
     */
    public function orderByFilePath(bool $ascending = true): static
    {
        $this->order($this->alias . '.file_path', $ascending);
        return $this;
    }

    /**
     * Filter cached_queue.file_path by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFilePath(string $filterValue): static
    {
        $this->like($this->alias . '.file_path', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by cached_queue.cached
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCached(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cached', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.cached from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCached(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cached', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.cached
     * @return static
     */
    public function groupByCached(): static
    {
        $this->group($this->alias . '.cached');
        return $this;
    }

    /**
     * Order by cached_queue.cached
     * @param bool $ascending
     * @return static
     */
    public function orderByCached(bool $ascending = true): static
    {
        $this->order($this->alias . '.cached', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.cached
     * @param int $filterValue
     * @return static
     */
    public function filterCachedGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cached', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.cached
     * @param int $filterValue
     * @return static
     */
    public function filterCachedGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cached', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.cached
     * @param int $filterValue
     * @return static
     */
    public function filterCachedLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cached', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.cached
     * @param int $filterValue
     * @return static
     */
    public function filterCachedLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cached', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by cached_queue.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by cached_queue.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by cached_queue.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by cached_queue.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by cached_queue.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out cached_queue.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by cached_queue.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by cached_queue.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than cached_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than cached_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than cached_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than cached_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
