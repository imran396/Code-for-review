<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxSchemaTaxDefinition;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractTaxSchemaTaxDefinitionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_TAX_SCHEMA_TAX_DEFINITION;
    protected string $alias = Db::A_TAX_SCHEMA_TAX_DEFINITION;

    /**
     * Filter by tax_schema_tax_definition.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.tax_schema_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxSchemaId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.tax_schema_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxSchemaId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.tax_definition_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxDefinitionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_definition_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.tax_definition_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxDefinitionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_definition_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema_tax_definition.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema_tax_definition.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
