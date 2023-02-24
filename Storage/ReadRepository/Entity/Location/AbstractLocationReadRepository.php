<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Location;

use Location;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLocationReadRepository
 * @method Location[] loadEntities()
 * @method Location|null loadEntity()
 */
abstract class AbstractLocationReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_LOCATION;
    protected string $alias = Db::A_LOCATION;

    /**
     * Filter by location.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out location.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by location.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by location.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.entity_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEntityId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_id', $filterValue);
        return $this;
    }

    /**
     * Filter out location.entity_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEntityId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_id', $skipValue);
        return $this;
    }

    /**
     * Group by location.entity_id
     * @return static
     */
    public function groupByEntityId(): static
    {
        $this->group($this->alias . '.entity_id');
        return $this;
    }

    /**
     * Order by location.entity_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityId(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterEntityIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.entity_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEntityType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_type', $filterValue);
        return $this;
    }

    /**
     * Filter out location.entity_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEntityType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_type', $skipValue);
        return $this;
    }

    /**
     * Group by location.entity_type
     * @return static
     */
    public function groupByEntityType(): static
    {
        $this->group($this->alias . '.entity_type');
        return $this;
    }

    /**
     * Order by location.entity_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEntityType(bool $ascending = true): static
    {
        $this->order($this->alias . '.entity_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.entity_type
     * @param int $filterValue
     * @return static
     */
    public function filterEntityTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.entity_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out location.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by location.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by location.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out location.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by location.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by location.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter location.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.logo
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLogo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.logo', $filterValue);
        return $this;
    }

    /**
     * Filter out location.logo from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLogo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.logo', $skipValue);
        return $this;
    }

    /**
     * Group by location.logo
     * @return static
     */
    public function groupByLogo(): static
    {
        $this->group($this->alias . '.logo');
        return $this;
    }

    /**
     * Order by location.logo
     * @param bool $ascending
     * @return static
     */
    public function orderByLogo(bool $ascending = true): static
    {
        $this->order($this->alias . '.logo', $ascending);
        return $this;
    }

    /**
     * Filter location.logo by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLogo(string $filterValue): static
    {
        $this->like($this->alias . '.logo', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out location.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by location.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by location.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter location.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out location.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Group by location.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by location.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter location.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out location.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Group by location.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by location.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter location.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.county
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCounty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county', $filterValue);
        return $this;
    }

    /**
     * Filter out location.county from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCounty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county', $skipValue);
        return $this;
    }

    /**
     * Group by location.county
     * @return static
     */
    public function groupByCounty(): static
    {
        $this->group($this->alias . '.county');
        return $this;
    }

    /**
     * Order by location.county
     * @param bool $ascending
     * @return static
     */
    public function orderByCounty(bool $ascending = true): static
    {
        $this->order($this->alias . '.county', $ascending);
        return $this;
    }

    /**
     * Filter location.county by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCounty(string $filterValue): static
    {
        $this->like($this->alias . '.county', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out location.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Group by location.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by location.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter location.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out location.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Group by location.zip
     * @return static
     */
    public function groupByZip(): static
    {
        $this->group($this->alias . '.zip');
        return $this;
    }

    /**
     * Order by location.zip
     * @param bool $ascending
     * @return static
     */
    public function orderByZip(bool $ascending = true): static
    {
        $this->order($this->alias . '.zip', $ascending);
        return $this;
    }

    /**
     * Filter location.zip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeZip(string $filterValue): static
    {
        $this->like($this->alias . '.zip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by location.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out location.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by location.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by location.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out location.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by location.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by location.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out location.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by location.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by location.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out location.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by location.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by location.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out location.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by location.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by location.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by location.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out location.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by location.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by location.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than location.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than location.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than location.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than location.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
