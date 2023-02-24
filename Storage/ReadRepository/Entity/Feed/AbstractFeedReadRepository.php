<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Feed;

use Feed;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractFeedReadRepository
 * @method Feed[] loadEntities()
 * @method Feed|null loadEntity()
 */
abstract class AbstractFeedReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_FEED;
    protected string $alias = Db::A_FEED;

    /**
     * Filter by feed.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by feed.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by feed.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by feed.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by feed.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by feed.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by feed.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter feed.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.slug
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSlug(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.slug', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.slug from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSlug(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.slug', $skipValue);
        return $this;
    }

    /**
     * Group by feed.slug
     * @return static
     */
    public function groupBySlug(): static
    {
        $this->group($this->alias . '.slug');
        return $this;
    }

    /**
     * Order by feed.slug
     * @param bool $ascending
     * @return static
     */
    public function orderBySlug(bool $ascending = true): static
    {
        $this->order($this->alias . '.slug', $ascending);
        return $this;
    }

    /**
     * Filter feed.slug by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSlug(string $filterValue): static
    {
        $this->like($this->alias . '.slug', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.feed_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFeedType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.feed_type', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.feed_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFeedType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.feed_type', $skipValue);
        return $this;
    }

    /**
     * Group by feed.feed_type
     * @return static
     */
    public function groupByFeedType(): static
    {
        $this->group($this->alias . '.feed_type');
        return $this;
    }

    /**
     * Order by feed.feed_type
     * @param bool $ascending
     * @return static
     */
    public function orderByFeedType(bool $ascending = true): static
    {
        $this->order($this->alias . '.feed_type', $ascending);
        return $this;
    }

    /**
     * Filter feed.feed_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFeedType(string $filterValue): static
    {
        $this->like($this->alias . '.feed_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.cache_timeout
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCacheTimeout(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cache_timeout', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.cache_timeout from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCacheTimeout(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cache_timeout', $skipValue);
        return $this;
    }

    /**
     * Group by feed.cache_timeout
     * @return static
     */
    public function groupByCacheTimeout(): static
    {
        $this->group($this->alias . '.cache_timeout');
        return $this;
    }

    /**
     * Order by feed.cache_timeout
     * @param bool $ascending
     * @return static
     */
    public function orderByCacheTimeout(bool $ascending = true): static
    {
        $this->order($this->alias . '.cache_timeout', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.cache_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterCacheTimeoutGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cache_timeout', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.cache_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterCacheTimeoutGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cache_timeout', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.cache_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterCacheTimeoutLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cache_timeout', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.cache_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterCacheTimeoutLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cache_timeout', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.items_per_page
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterItemsPerPage(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.items_per_page', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.items_per_page from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipItemsPerPage(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.items_per_page', $skipValue);
        return $this;
    }

    /**
     * Group by feed.items_per_page
     * @return static
     */
    public function groupByItemsPerPage(): static
    {
        $this->group($this->alias . '.items_per_page');
        return $this;
    }

    /**
     * Order by feed.items_per_page
     * @param bool $ascending
     * @return static
     */
    public function orderByItemsPerPage(bool $ascending = true): static
    {
        $this->order($this->alias . '.items_per_page', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.escaping
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEscaping(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.escaping', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.escaping from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEscaping(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.escaping', $skipValue);
        return $this;
    }

    /**
     * Group by feed.escaping
     * @return static
     */
    public function groupByEscaping(): static
    {
        $this->group($this->alias . '.escaping');
        return $this;
    }

    /**
     * Order by feed.escaping
     * @param bool $ascending
     * @return static
     */
    public function orderByEscaping(bool $ascending = true): static
    {
        $this->order($this->alias . '.escaping', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.escaping
     * @param int $filterValue
     * @return static
     */
    public function filterEscapingGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.escaping', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.escaping
     * @param int $filterValue
     * @return static
     */
    public function filterEscapingGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.escaping', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.escaping
     * @param int $filterValue
     * @return static
     */
    public function filterEscapingLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.escaping', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.escaping
     * @param int $filterValue
     * @return static
     */
    public function filterEscapingLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.escaping', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.encoding
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEncoding(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.encoding', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.encoding from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEncoding(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.encoding', $skipValue);
        return $this;
    }

    /**
     * Group by feed.encoding
     * @return static
     */
    public function groupByEncoding(): static
    {
        $this->group($this->alias . '.encoding');
        return $this;
    }

    /**
     * Order by feed.encoding
     * @param bool $ascending
     * @return static
     */
    public function orderByEncoding(bool $ascending = true): static
    {
        $this->order($this->alias . '.encoding', $ascending);
        return $this;
    }

    /**
     * Filter feed.encoding by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEncoding(string $filterValue): static
    {
        $this->like($this->alias . '.encoding', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.header
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterHeader(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.header', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.header from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipHeader(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.header', $skipValue);
        return $this;
    }

    /**
     * Group by feed.header
     * @return static
     */
    public function groupByHeader(): static
    {
        $this->group($this->alias . '.header');
        return $this;
    }

    /**
     * Order by feed.header
     * @param bool $ascending
     * @return static
     */
    public function orderByHeader(bool $ascending = true): static
    {
        $this->order($this->alias . '.header', $ascending);
        return $this;
    }

    /**
     * Filter feed.header by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeHeader(string $filterValue): static
    {
        $this->like($this->alias . '.header', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.repetition
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRepetition(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.repetition', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.repetition from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRepetition(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.repetition', $skipValue);
        return $this;
    }

    /**
     * Group by feed.repetition
     * @return static
     */
    public function groupByRepetition(): static
    {
        $this->group($this->alias . '.repetition');
        return $this;
    }

    /**
     * Order by feed.repetition
     * @param bool $ascending
     * @return static
     */
    public function orderByRepetition(bool $ascending = true): static
    {
        $this->order($this->alias . '.repetition', $ascending);
        return $this;
    }

    /**
     * Filter feed.repetition by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRepetition(string $filterValue): static
    {
        $this->like($this->alias . '.repetition', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.glue
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGlue(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.glue', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.glue from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGlue(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.glue', $skipValue);
        return $this;
    }

    /**
     * Group by feed.glue
     * @return static
     */
    public function groupByGlue(): static
    {
        $this->group($this->alias . '.glue');
        return $this;
    }

    /**
     * Order by feed.glue
     * @param bool $ascending
     * @return static
     */
    public function orderByGlue(bool $ascending = true): static
    {
        $this->order($this->alias . '.glue', $ascending);
        return $this;
    }

    /**
     * Filter feed.glue by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeGlue(string $filterValue): static
    {
        $this->like($this->alias . '.glue', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.footer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFooter(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.footer', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.footer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFooter(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.footer', $skipValue);
        return $this;
    }

    /**
     * Group by feed.footer
     * @return static
     */
    public function groupByFooter(): static
    {
        $this->group($this->alias . '.footer');
        return $this;
    }

    /**
     * Order by feed.footer
     * @param bool $ascending
     * @return static
     */
    public function orderByFooter(bool $ascending = true): static
    {
        $this->order($this->alias . '.footer', $ascending);
        return $this;
    }

    /**
     * Filter feed.footer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFooter(string $filterValue): static
    {
        $this->like($this->alias . '.footer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by feed.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by feed.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by feed.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by feed.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by feed.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by feed.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by feed.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by feed.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by feed.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by feed.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.currency_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCurrencyId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.currency_id', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.currency_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCurrencyId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.currency_id', $skipValue);
        return $this;
    }

    /**
     * Group by feed.currency_id
     * @return static
     */
    public function groupByCurrencyId(): static
    {
        $this->group($this->alias . '.currency_id');
        return $this;
    }

    /**
     * Order by feed.currency_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrencyId(bool $ascending = true): static
    {
        $this->order($this->alias . '.currency_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.include_in_reports
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeInReports(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_in_reports', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.include_in_reports from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeInReports(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_in_reports', $skipValue);
        return $this;
    }

    /**
     * Group by feed.include_in_reports
     * @return static
     */
    public function groupByIncludeInReports(): static
    {
        $this->group($this->alias . '.include_in_reports');
        return $this;
    }

    /**
     * Order by feed.include_in_reports
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeInReports(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_in_reports', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.include_in_reports
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeInReportsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_in_reports', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.include_in_reports
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeInReportsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_in_reports', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.include_in_reports
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeInReportsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_in_reports', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.include_in_reports
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeInReportsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_in_reports', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.content_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterContentType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.content_type', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.content_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipContentType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.content_type', $skipValue);
        return $this;
    }

    /**
     * Group by feed.content_type
     * @return static
     */
    public function groupByContentType(): static
    {
        $this->group($this->alias . '.content_type');
        return $this;
    }

    /**
     * Order by feed.content_type
     * @param bool $ascending
     * @return static
     */
    public function orderByContentType(bool $ascending = true): static
    {
        $this->order($this->alias . '.content_type', $ascending);
        return $this;
    }

    /**
     * Filter feed.content_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeContentType(string $filterValue): static
    {
        $this->like($this->alias . '.content_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.filename
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFilename(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.filename', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.filename from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFilename(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.filename', $skipValue);
        return $this;
    }

    /**
     * Group by feed.filename
     * @return static
     */
    public function groupByFilename(): static
    {
        $this->group($this->alias . '.filename');
        return $this;
    }

    /**
     * Order by feed.filename
     * @param bool $ascending
     * @return static
     */
    public function orderByFilename(bool $ascending = true): static
    {
        $this->order($this->alias . '.filename', $ascending);
        return $this;
    }

    /**
     * Filter feed.filename by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFilename(string $filterValue): static
    {
        $this->like($this->alias . '.filename', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.hide_empty_fields
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideEmptyFields(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_empty_fields', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.hide_empty_fields from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideEmptyFields(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_empty_fields', $skipValue);
        return $this;
    }

    /**
     * Group by feed.hide_empty_fields
     * @return static
     */
    public function groupByHideEmptyFields(): static
    {
        $this->group($this->alias . '.hide_empty_fields');
        return $this;
    }

    /**
     * Order by feed.hide_empty_fields
     * @param bool $ascending
     * @return static
     */
    public function orderByHideEmptyFields(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_empty_fields', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.hide_empty_fields
     * @param bool $filterValue
     * @return static
     */
    public function filterHideEmptyFieldsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_empty_fields', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed.locale
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLocale(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.locale', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.locale from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLocale(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.locale', $skipValue);
        return $this;
    }

    /**
     * Group by feed.locale
     * @return static
     */
    public function groupByLocale(): static
    {
        $this->group($this->alias . '.locale');
        return $this;
    }

    /**
     * Order by feed.locale
     * @param bool $ascending
     * @return static
     */
    public function orderByLocale(bool $ascending = true): static
    {
        $this->order($this->alias . '.locale', $ascending);
        return $this;
    }

    /**
     * Filter feed.locale by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLocale(string $filterValue): static
    {
        $this->like($this->alias . '.locale', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out feed.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by feed.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by feed.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
