<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxDefinition;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractTaxDefinitionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_TAX_DEFINITION;
    protected string $alias = Db::A_TAX_DEFINITION;

    /**
     * Filter by tax_definition.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.source_tax_definition_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSourceTaxDefinitionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.source_tax_definition_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.source_tax_definition_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSourceTaxDefinitionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.source_tax_definition_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.tax_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_type', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.tax_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_type', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.geo_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGeoType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.geo_type', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.geo_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGeoType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.geo_type', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.county
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCounty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.county from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCounty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.range_calculation', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.collected_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterCollectedAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.collected_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.collected_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipCollectedAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.collected_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
