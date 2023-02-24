<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Account;

use Account;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAccountReadRepository
 * @method Account[] loadEntities()
 * @method Account|null loadEntity()
 */
abstract class AbstractAccountReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_ACCOUNT;
    protected string $alias = Db::A_ACCOUNT;

    /**
     * Filter by account.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out account.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by account.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by account.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by account.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by account.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter account.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Group by account.company_name
     * @return static
     */
    public function groupByCompanyName(): static
    {
        $this->group($this->alias . '.company_name');
        return $this;
    }

    /**
     * Order by account.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByCompanyName(bool $ascending = true): static
    {
        $this->order($this->alias . '.company_name', $ascending);
        return $this;
    }

    /**
     * Filter account.company_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCompanyName(string $filterValue): static
    {
        $this->like($this->alias . '.company_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out account.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by account.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by account.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter account.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.address_2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address_2', $filterValue);
        return $this;
    }

    /**
     * Filter out account.address_2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address_2', $skipValue);
        return $this;
    }

    /**
     * Group by account.address_2
     * @return static
     */
    public function groupByAddress2(): static
    {
        $this->group($this->alias . '.address_2');
        return $this;
    }

    /**
     * Order by account.address_2
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress2(bool $ascending = true): static
    {
        $this->order($this->alias . '.address_2', $ascending);
        return $this;
    }

    /**
     * Filter account.address_2 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress2(string $filterValue): static
    {
        $this->like($this->alias . '.address_2', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out account.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Group by account.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by account.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter account.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.state_province
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStateProvince(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state_province', $filterValue);
        return $this;
    }

    /**
     * Filter out account.state_province from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStateProvince(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state_province', $skipValue);
        return $this;
    }

    /**
     * Group by account.state_province
     * @return static
     */
    public function groupByStateProvince(): static
    {
        $this->group($this->alias . '.state_province');
        return $this;
    }

    /**
     * Order by account.state_province
     * @param bool $ascending
     * @return static
     */
    public function orderByStateProvince(bool $ascending = true): static
    {
        $this->order($this->alias . '.state_province', $ascending);
        return $this;
    }

    /**
     * Filter account.state_province by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStateProvince(string $filterValue): static
    {
        $this->like($this->alias . '.state_province', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.county
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCounty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county', $filterValue);
        return $this;
    }

    /**
     * Filter out account.county from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCounty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county', $skipValue);
        return $this;
    }

    /**
     * Group by account.county
     * @return static
     */
    public function groupByCounty(): static
    {
        $this->group($this->alias . '.county');
        return $this;
    }

    /**
     * Order by account.county
     * @param bool $ascending
     * @return static
     */
    public function orderByCounty(bool $ascending = true): static
    {
        $this->order($this->alias . '.county', $ascending);
        return $this;
    }

    /**
     * Filter account.county by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCounty(string $filterValue): static
    {
        $this->like($this->alias . '.county', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out account.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Group by account.zip
     * @return static
     */
    public function groupByZip(): static
    {
        $this->group($this->alias . '.zip');
        return $this;
    }

    /**
     * Order by account.zip
     * @param bool $ascending
     * @return static
     */
    public function orderByZip(bool $ascending = true): static
    {
        $this->order($this->alias . '.zip', $ascending);
        return $this;
    }

    /**
     * Filter account.zip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeZip(string $filterValue): static
    {
        $this->like($this->alias . '.zip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out account.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Group by account.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by account.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter account.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out account.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by account.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by account.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter account.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out account.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Group by account.email
     * @return static
     */
    public function groupByEmail(): static
    {
        $this->group($this->alias . '.email');
        return $this;
    }

    /**
     * Order by account.email
     * @param bool $ascending
     * @return static
     */
    public function orderByEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.email', $ascending);
        return $this;
    }

    /**
     * Filter account.email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmail(string $filterValue): static
    {
        $this->like($this->alias . '.email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.site_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSiteUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.site_url', $filterValue);
        return $this;
    }

    /**
     * Filter out account.site_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSiteUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.site_url', $skipValue);
        return $this;
    }

    /**
     * Group by account.site_url
     * @return static
     */
    public function groupBySiteUrl(): static
    {
        $this->group($this->alias . '.site_url');
        return $this;
    }

    /**
     * Order by account.site_url
     * @param bool $ascending
     * @return static
     */
    public function orderBySiteUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.site_url', $ascending);
        return $this;
    }

    /**
     * Filter account.site_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSiteUrl(string $filterValue): static
    {
        $this->like($this->alias . '.site_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notes', $filterValue);
        return $this;
    }

    /**
     * Filter out account.notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notes', $skipValue);
        return $this;
    }

    /**
     * Group by account.notes
     * @return static
     */
    public function groupByNotes(): static
    {
        $this->group($this->alias . '.notes');
        return $this;
    }

    /**
     * Order by account.notes
     * @param bool $ascending
     * @return static
     */
    public function orderByNotes(bool $ascending = true): static
    {
        $this->order($this->alias . '.notes', $ascending);
        return $this;
    }

    /**
     * Filter account.notes by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNotes(string $filterValue): static
    {
        $this->like($this->alias . '.notes', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out account.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by account.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by account.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out account.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by account.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by account.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out account.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by account.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by account.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out account.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by account.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by account.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.main
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMain(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.main', $filterValue);
        return $this;
    }

    /**
     * Filter out account.main from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMain(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.main', $skipValue);
        return $this;
    }

    /**
     * Group by account.main
     * @return static
     */
    public function groupByMain(): static
    {
        $this->group($this->alias . '.main');
        return $this;
    }

    /**
     * Order by account.main
     * @param bool $ascending
     * @return static
     */
    public function orderByMain(bool $ascending = true): static
    {
        $this->order($this->alias . '.main', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.main
     * @param bool $filterValue
     * @return static
     */
    public function filterMainGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.main', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.main
     * @param bool $filterValue
     * @return static
     */
    public function filterMainGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.main', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.main
     * @param bool $filterValue
     * @return static
     */
    public function filterMainLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.main', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.main
     * @param bool $filterValue
     * @return static
     */
    public function filterMainLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.main', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out account.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by account.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by account.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.auction_inc_allowed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuctionIncAllowed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_inc_allowed', $filterValue);
        return $this;
    }

    /**
     * Filter out account.auction_inc_allowed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuctionIncAllowed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_inc_allowed', $skipValue);
        return $this;
    }

    /**
     * Group by account.auction_inc_allowed
     * @return static
     */
    public function groupByAuctionIncAllowed(): static
    {
        $this->group($this->alias . '.auction_inc_allowed');
        return $this;
    }

    /**
     * Order by account.auction_inc_allowed
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionIncAllowed(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_inc_allowed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.auction_inc_allowed
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionIncAllowedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_inc_allowed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.auction_inc_allowed
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionIncAllowedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_inc_allowed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.auction_inc_allowed
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionIncAllowedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_inc_allowed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.auction_inc_allowed
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionIncAllowedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_inc_allowed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.public_support_contact_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPublicSupportContactName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.public_support_contact_name', $filterValue);
        return $this;
    }

    /**
     * Filter out account.public_support_contact_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPublicSupportContactName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.public_support_contact_name', $skipValue);
        return $this;
    }

    /**
     * Group by account.public_support_contact_name
     * @return static
     */
    public function groupByPublicSupportContactName(): static
    {
        $this->group($this->alias . '.public_support_contact_name');
        return $this;
    }

    /**
     * Order by account.public_support_contact_name
     * @param bool $ascending
     * @return static
     */
    public function orderByPublicSupportContactName(bool $ascending = true): static
    {
        $this->order($this->alias . '.public_support_contact_name', $ascending);
        return $this;
    }

    /**
     * Filter account.public_support_contact_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePublicSupportContactName(string $filterValue): static
    {
        $this->like($this->alias . '.public_support_contact_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.url_domain
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUrlDomain(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.url_domain', $filterValue);
        return $this;
    }

    /**
     * Filter out account.url_domain from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUrlDomain(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.url_domain', $skipValue);
        return $this;
    }

    /**
     * Group by account.url_domain
     * @return static
     */
    public function groupByUrlDomain(): static
    {
        $this->group($this->alias . '.url_domain');
        return $this;
    }

    /**
     * Order by account.url_domain
     * @param bool $ascending
     * @return static
     */
    public function orderByUrlDomain(bool $ascending = true): static
    {
        $this->order($this->alias . '.url_domain', $ascending);
        return $this;
    }

    /**
     * Filter account.url_domain by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeUrlDomain(string $filterValue): static
    {
        $this->like($this->alias . '.url_domain', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by account.show_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_all', $filterValue);
        return $this;
    }

    /**
     * Filter out account.show_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_all', $skipValue);
        return $this;
    }

    /**
     * Group by account.show_all
     * @return static
     */
    public function groupByShowAll(): static
    {
        $this->group($this->alias . '.show_all');
        return $this;
    }

    /**
     * Order by account.show_all
     * @param bool $ascending
     * @return static
     */
    public function orderByShowAll(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_all', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.show_all
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAllGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_all', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.show_all
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAllGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_all', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.show_all
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAllLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_all', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.show_all
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAllLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_all', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.hybrid_auction_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hybrid_auction_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out account.hybrid_auction_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHybridAuctionEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hybrid_auction_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by account.hybrid_auction_enabled
     * @return static
     */
    public function groupByHybridAuctionEnabled(): static
    {
        $this->group($this->alias . '.hybrid_auction_enabled');
        return $this;
    }

    /**
     * Order by account.hybrid_auction_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByHybridAuctionEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.hybrid_auction_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.hybrid_auction_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid_auction_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.hybrid_auction_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid_auction_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.hybrid_auction_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid_auction_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.hybrid_auction_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridAuctionEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid_auction_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.buy_now_select_quantity_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_select_quantity_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out account.buy_now_select_quantity_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowSelectQuantityEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_select_quantity_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by account.buy_now_select_quantity_enabled
     * @return static
     */
    public function groupByBuyNowSelectQuantityEnabled(): static
    {
        $this->group($this->alias . '.buy_now_select_quantity_enabled');
        return $this;
    }

    /**
     * Order by account.buy_now_select_quantity_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowSelectQuantityEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_select_quantity_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by account.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out account.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by account.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by account.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than account.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than account.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than account.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than account.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
