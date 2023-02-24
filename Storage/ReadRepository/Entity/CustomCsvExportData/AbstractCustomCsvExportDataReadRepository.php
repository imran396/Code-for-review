<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportData;

use CustomCsvExportData;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractCustomCsvExportDataReadRepository
 * @method CustomCsvExportData[] loadEntities()
 * @method CustomCsvExportData|null loadEntity()
 */
abstract class AbstractCustomCsvExportDataReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_CUSTOM_CSV_EXPORT_DATA;
    protected string $alias = Db::A_CUSTOM_CSV_EXPORT_DATA;

    /**
     * Filter by custom_csv_export_data.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.config_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterConfigId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.config_id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.config_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipConfigId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.config_id', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.config_id
     * @return static
     */
    public function groupByConfigId(): static
    {
        $this->group($this->alias . '.config_id');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.config_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConfigId(bool $ascending = true): static
    {
        $this->order($this->alias . '.config_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.config_id
     * @param int $filterValue
     * @return static
     */
    public function filterConfigIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.config_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.config_id
     * @param int $filterValue
     * @return static
     */
    public function filterConfigIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.config_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.config_id
     * @param int $filterValue
     * @return static
     */
    public function filterConfigIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.config_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.config_id
     * @param int $filterValue
     * @return static
     */
    public function filterConfigIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.config_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.field_index
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFieldIndex(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.field_index', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.field_index from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFieldIndex(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.field_index', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.field_index
     * @return static
     */
    public function groupByFieldIndex(): static
    {
        $this->group($this->alias . '.field_index');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.field_index
     * @param bool $ascending
     * @return static
     */
    public function orderByFieldIndex(bool $ascending = true): static
    {
        $this->order($this->alias . '.field_index', $ascending);
        return $this;
    }

    /**
     * Filter custom_csv_export_data.field_index by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFieldIndex(string $filterValue): static
    {
        $this->like($this->alias . '.field_index', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.field_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFieldName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.field_name', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.field_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFieldName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.field_name', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.field_name
     * @return static
     */
    public function groupByFieldName(): static
    {
        $this->group($this->alias . '.field_name');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.field_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFieldName(bool $ascending = true): static
    {
        $this->order($this->alias . '.field_name', $ascending);
        return $this;
    }

    /**
     * Filter custom_csv_export_data.field_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFieldName(string $filterValue): static
    {
        $this->like($this->alias . '.field_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.field_order
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterFieldOrder(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.field_order', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.field_order from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipFieldOrder(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.field_order', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.field_order
     * @return static
     */
    public function groupByFieldOrder(): static
    {
        $this->group($this->alias . '.field_order');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.field_order
     * @param bool $ascending
     * @return static
     */
    public function orderByFieldOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.field_order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.field_order
     * @param float $filterValue
     * @return static
     */
    public function filterFieldOrderGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.field_order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.field_order
     * @param float $filterValue
     * @return static
     */
    public function filterFieldOrderGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.field_order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.field_order
     * @param float $filterValue
     * @return static
     */
    public function filterFieldOrderLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.field_order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.field_order
     * @param float $filterValue
     * @return static
     */
    public function filterFieldOrderLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.field_order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by custom_csv_export_data.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_csv_export_data.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by custom_csv_export_data.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by custom_csv_export_data.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than custom_csv_export_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than custom_csv_export_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than custom_csv_export_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than custom_csv_export_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
