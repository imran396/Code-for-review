<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Feed;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractFeedDeleteRepository extends DeleteRepositoryBase
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
}
