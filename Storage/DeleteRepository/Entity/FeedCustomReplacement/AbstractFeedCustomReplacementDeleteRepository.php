<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\FeedCustomReplacement;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractFeedCustomReplacementDeleteRepository extends DeleteRepositoryBase
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
}
