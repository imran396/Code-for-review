<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomCsvExportData;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractCustomCsvExportDataDeleteRepository extends DeleteRepositoryBase
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
}
