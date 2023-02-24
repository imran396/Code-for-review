<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxDefinition;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use TaxDefinition;

/**
 * Abstract class AbstractTaxDefinitionReadRepository
 * @method TaxDefinition[] loadEntities()
 * @method TaxDefinition|null loadEntity()
 */
abstract class AbstractTaxDefinitionReadRepository extends ReadRepositoryBase
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
     * Group by tax_definition.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by tax_definition.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by tax_definition.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by tax_definition.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by tax_definition.source_tax_definition_id
     * @return static
     */
    public function groupBySourceTaxDefinitionId(): static
    {
        $this->group($this->alias . '.source_tax_definition_id');
        return $this;
    }

    /**
     * Order by tax_definition.source_tax_definition_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySourceTaxDefinitionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.source_tax_definition_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.source_tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxDefinitionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_definition_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.source_tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxDefinitionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_definition_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.source_tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxDefinitionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_definition_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.source_tax_definition_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxDefinitionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_definition_id', $filterValue, '<=');
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
     * Group by tax_definition.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by tax_definition.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
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
     * Group by tax_definition.tax_type
     * @return static
     */
    public function groupByTaxType(): static
    {
        $this->group($this->alias . '.tax_type');
        return $this;
    }

    /**
     * Order by tax_definition.tax_type
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxType(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.tax_type
     * @param int $filterValue
     * @return static
     */
    public function filterTaxTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.tax_type
     * @param int $filterValue
     * @return static
     */
    public function filterTaxTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.tax_type
     * @param int $filterValue
     * @return static
     */
    public function filterTaxTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.tax_type
     * @param int $filterValue
     * @return static
     */
    public function filterTaxTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_type', $filterValue, '<=');
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
     * Group by tax_definition.geo_type
     * @return static
     */
    public function groupByGeoType(): static
    {
        $this->group($this->alias . '.geo_type');
        return $this;
    }

    /**
     * Order by tax_definition.geo_type
     * @param bool $ascending
     * @return static
     */
    public function orderByGeoType(bool $ascending = true): static
    {
        $this->order($this->alias . '.geo_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '<=');
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
     * Group by tax_definition.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by tax_definition.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
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
     * Group by tax_definition.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by tax_definition.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
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
     * Group by tax_definition.county
     * @return static
     */
    public function groupByCounty(): static
    {
        $this->group($this->alias . '.county');
        return $this;
    }

    /**
     * Order by tax_definition.county
     * @param bool $ascending
     * @return static
     */
    public function orderByCounty(bool $ascending = true): static
    {
        $this->order($this->alias . '.county', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.county by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCounty(string $filterValue): static
    {
        $this->like($this->alias . '.county', "%{$filterValue}%");
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
     * Group by tax_definition.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by tax_definition.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
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
     * Group by tax_definition.description
     * @return static
     */
    public function groupByDescription(): static
    {
        $this->group($this->alias . '.description');
        return $this;
    }

    /**
     * Order by tax_definition.description
     * @param bool $ascending
     * @return static
     */
    public function orderByDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.description', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDescription(string $filterValue): static
    {
        $this->like($this->alias . '.description', "%{$filterValue}%");
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
     * Group by tax_definition.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by tax_definition.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter tax_definition.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
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
     * Group by tax_definition.range_calculation
     * @return static
     */
    public function groupByRangeCalculation(): static
    {
        $this->group($this->alias . '.range_calculation');
        return $this;
    }

    /**
     * Order by tax_definition.range_calculation
     * @param bool $ascending
     * @return static
     */
    public function orderByRangeCalculation(bool $ascending = true): static
    {
        $this->order($this->alias . '.range_calculation', $ascending);
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
     * Group by tax_definition.collected_amount
     * @return static
     */
    public function groupByCollectedAmount(): static
    {
        $this->group($this->alias . '.collected_amount');
        return $this;
    }

    /**
     * Order by tax_definition.collected_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByCollectedAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.collected_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.collected_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.collected_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.collected_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.collected_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected_amount', $filterValue, '<=');
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
     * Group by tax_definition.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by tax_definition.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by tax_definition.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by tax_definition.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by tax_definition.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by tax_definition.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by tax_definition.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by tax_definition.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by tax_definition.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by tax_definition.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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

    /**
     * Group by tax_definition.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by tax_definition.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_definition.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_definition.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_definition.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_definition.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
