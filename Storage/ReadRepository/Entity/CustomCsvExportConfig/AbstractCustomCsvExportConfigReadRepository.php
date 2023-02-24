<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig;

use CustomCsvExportConfig;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractCustomCsvExportConfigReadRepository
 * @method CustomCsvExportConfig[] loadEntities()
 * @method CustomCsvExportConfig|null loadEntity()
 */
abstract class AbstractCustomCsvExportConfigReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_CUSTOM_CSV_EXPORT_CONFIG;
    protected string $alias = Db::A_CUSTOM_CSV_EXPORT_CONFIG;

    /**
     * Filter by custom_csv_export_config.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter custom_csv_export_config.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.image_web_links
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageWebLinks(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_web_links', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.image_web_links from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageWebLinks(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_web_links', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.image_web_links
     * @return static
     */
    public function groupByImageWebLinks(): static
    {
        $this->group($this->alias . '.image_web_links');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.image_web_links
     * @param bool $ascending
     * @return static
     */
    public function orderByImageWebLinks(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_web_links', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.image_web_links
     * @param bool $filterValue
     * @return static
     */
    public function filterImageWebLinksGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_web_links', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.image_web_links
     * @param bool $filterValue
     * @return static
     */
    public function filterImageWebLinksGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_web_links', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.image_web_links
     * @param bool $filterValue
     * @return static
     */
    public function filterImageWebLinksLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_web_links', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.image_web_links
     * @param bool $filterValue
     * @return static
     */
    public function filterImageWebLinksLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_web_links', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.image_separate_columns
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterImageSeparateColumns(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_separate_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.image_separate_columns from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipImageSeparateColumns(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_separate_columns', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.image_separate_columns
     * @return static
     */
    public function groupByImageSeparateColumns(): static
    {
        $this->group($this->alias . '.image_separate_columns');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.image_separate_columns
     * @param bool $ascending
     * @return static
     */
    public function orderByImageSeparateColumns(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_separate_columns', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.image_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterImageSeparateColumnsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_separate_columns', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.image_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterImageSeparateColumnsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_separate_columns', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.image_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterImageSeparateColumnsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_separate_columns', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.image_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterImageSeparateColumnsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_separate_columns', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.categories_separate_columns
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumns(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.categories_separate_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.categories_separate_columns from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCategoriesSeparateColumns(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.categories_separate_columns', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.categories_separate_columns
     * @return static
     */
    public function groupByCategoriesSeparateColumns(): static
    {
        $this->group($this->alias . '.categories_separate_columns');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.categories_separate_columns
     * @param bool $ascending
     * @return static
     */
    public function orderByCategoriesSeparateColumns(bool $ascending = true): static
    {
        $this->order($this->alias . '.categories_separate_columns', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.categories_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumnsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.categories_separate_columns', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.categories_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumnsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.categories_separate_columns', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.categories_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumnsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.categories_separate_columns', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.categories_separate_columns
     * @param int $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumnsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.categories_separate_columns', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_config.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_config.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_config.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by custom_csv_export_config.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_config.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
