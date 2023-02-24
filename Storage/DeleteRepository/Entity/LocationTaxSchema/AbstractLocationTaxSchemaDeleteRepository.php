<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LocationTaxSchema;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLocationTaxSchemaDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOCATION_TAX_SCHEMA;
    protected string $alias = Db::A_LOCATION_TAX_SCHEMA;

    /**
     * Filter by location_tax_schema.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.location_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLocationId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.location_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLocationId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.location_id', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.tax_schema_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxSchemaId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.tax_schema_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxSchemaId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by location_tax_schema.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out location_tax_schema.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
