<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\FeedCustomReplacement;

use FeedCustomReplacement;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractFeedCustomReplacementReadRepository
 * @method FeedCustomReplacement[] loadEntities()
 * @method FeedCustomReplacement|null loadEntity()
 */
abstract class AbstractFeedCustomReplacementReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_FEED_CUSTOM_REPLACEMENT;
    protected string $alias = Db::A_FEED_CUSTOM_REPLACEMENT;

    /**
     * Filter by feed_custom_replacement.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.feed_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterFeedId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.feed_id', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.feed_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipFeedId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.feed_id', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.feed_id
     * @return static
     */
    public function groupByFeedId(): static
    {
        $this->group($this->alias . '.feed_id');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.feed_id
     * @param bool $ascending
     * @return static
     */
    public function orderByFeedId(bool $ascending = true): static
    {
        $this->order($this->alias . '.feed_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.feed_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeedIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.feed_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.feed_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeedIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.feed_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.feed_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeedIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.feed_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.feed_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeedIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.feed_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.original
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOriginal(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.original', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.original from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOriginal(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.original', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.original
     * @return static
     */
    public function groupByOriginal(): static
    {
        $this->group($this->alias . '.original');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.original
     * @param bool $ascending
     * @return static
     */
    public function orderByOriginal(bool $ascending = true): static
    {
        $this->order($this->alias . '.original', $ascending);
        return $this;
    }

    /**
     * Filter feed_custom_replacement.original by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOriginal(string $filterValue): static
    {
        $this->like($this->alias . '.original', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.replacement
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReplacement(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.replacement', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.replacement from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReplacement(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.replacement', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.replacement
     * @return static
     */
    public function groupByReplacement(): static
    {
        $this->group($this->alias . '.replacement');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.replacement
     * @param bool $ascending
     * @return static
     */
    public function orderByReplacement(bool $ascending = true): static
    {
        $this->order($this->alias . '.replacement', $ascending);
        return $this;
    }

    /**
     * Filter feed_custom_replacement.replacement by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReplacement(string $filterValue): static
    {
        $this->like($this->alias . '.replacement', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.regexp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegexp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.regexp', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.regexp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegexp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.regexp', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.regexp
     * @return static
     */
    public function groupByRegexp(): static
    {
        $this->group($this->alias . '.regexp');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.regexp
     * @param bool $ascending
     * @return static
     */
    public function orderByRegexp(bool $ascending = true): static
    {
        $this->order($this->alias . '.regexp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.regexp
     * @param bool $filterValue
     * @return static
     */
    public function filterRegexpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regexp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.regexp
     * @param bool $filterValue
     * @return static
     */
    public function filterRegexpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regexp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.regexp
     * @param bool $filterValue
     * @return static
     */
    public function filterRegexpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regexp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.regexp
     * @param bool $filterValue
     * @return static
     */
    public function filterRegexpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regexp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.order
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOrder(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.order from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOrder(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by feed_custom_replacement.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out feed_custom_replacement.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by feed_custom_replacement.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by feed_custom_replacement.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than feed_custom_replacement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than feed_custom_replacement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than feed_custom_replacement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than feed_custom_replacement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
