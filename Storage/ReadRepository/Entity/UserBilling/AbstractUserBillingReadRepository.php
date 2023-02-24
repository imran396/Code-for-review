<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserBilling;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserBilling;

/**
 * Abstract class AbstractUserBillingReadRepository
 * @method UserBilling[] loadEntities()
 * @method UserBilling|null loadEntity()
 */
abstract class AbstractUserBillingReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_BILLING;
    protected string $alias = Db::A_USER_BILLING;

    /**
     * Filter by user_billing.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_billing.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_billing.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.company_name
     * @return static
     */
    public function groupByCompanyName(): static
    {
        $this->group($this->alias . '.company_name');
        return $this;
    }

    /**
     * Order by user_billing.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByCompanyName(bool $ascending = true): static
    {
        $this->order($this->alias . '.company_name', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.company_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCompanyName(string $filterValue): static
    {
        $this->like($this->alias . '.company_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.first_name
     * @return static
     */
    public function groupByFirstName(): static
    {
        $this->group($this->alias . '.first_name');
        return $this;
    }

    /**
     * Order by user_billing.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstName(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_name', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.first_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFirstName(string $filterValue): static
    {
        $this->like($this->alias . '.first_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.last_name
     * @return static
     */
    public function groupByLastName(): static
    {
        $this->group($this->alias . '.last_name');
        return $this;
    }

    /**
     * Order by user_billing.last_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLastName(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_name', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.last_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLastName(string $filterValue): static
    {
        $this->like($this->alias . '.last_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by user_billing.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.fax
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFax(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.fax from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFax(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fax', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.fax
     * @return static
     */
    public function groupByFax(): static
    {
        $this->group($this->alias . '.fax');
        return $this;
    }

    /**
     * Order by user_billing.fax
     * @param bool $ascending
     * @return static
     */
    public function orderByFax(bool $ascending = true): static
    {
        $this->order($this->alias . '.fax', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.fax by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFax(string $filterValue): static
    {
        $this->like($this->alias . '.fax', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.email
     * @return static
     */
    public function groupByEmail(): static
    {
        $this->group($this->alias . '.email');
        return $this;
    }

    /**
     * Order by user_billing.email
     * @param bool $ascending
     * @return static
     */
    public function orderByEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.email', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmail(string $filterValue): static
    {
        $this->like($this->alias . '.email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by user_billing.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by user_billing.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.address2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address2', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address2', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.address2
     * @return static
     */
    public function groupByAddress2(): static
    {
        $this->group($this->alias . '.address2');
        return $this;
    }

    /**
     * Order by user_billing.address2
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress2(bool $ascending = true): static
    {
        $this->order($this->alias . '.address2', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.address2 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress2(string $filterValue): static
    {
        $this->like($this->alias . '.address2', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by user_billing.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by user_billing.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.zip
     * @return static
     */
    public function groupByZip(): static
    {
        $this->group($this->alias . '.zip');
        return $this;
    }

    /**
     * Order by user_billing.zip
     * @param bool $ascending
     * @return static
     */
    public function orderByZip(bool $ascending = true): static
    {
        $this->order($this->alias . '.zip', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.zip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeZip(string $filterValue): static
    {
        $this->like($this->alias . '.zip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.cc_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCcType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCcType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.cc_type
     * @return static
     */
    public function groupByCcType(): static
    {
        $this->group($this->alias . '.cc_type');
        return $this;
    }

    /**
     * Order by user_billing.cc_type
     * @param bool $ascending
     * @return static
     */
    public function orderByCcType(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.cc_type
     * @param int $filterValue
     * @return static
     */
    public function filterCcTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.cc_type
     * @param int $filterValue
     * @return static
     */
    public function filterCcTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.cc_type
     * @param int $filterValue
     * @return static
     */
    public function filterCcTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.cc_type
     * @param int $filterValue
     * @return static
     */
    public function filterCcTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.cc_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.cc_number
     * @return static
     */
    public function groupByCcNumber(): static
    {
        $this->group($this->alias . '.cc_number');
        return $this;
    }

    /**
     * Order by user_billing.cc_number
     * @param bool $ascending
     * @return static
     */
    public function orderByCcNumber(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_number', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.cc_number by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCcNumber(string $filterValue): static
    {
        $this->like($this->alias . '.cc_number', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.cc_number_eway
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumberEway(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number_eway', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number_eway from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumberEway(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number_eway', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.cc_number_eway
     * @return static
     */
    public function groupByCcNumberEway(): static
    {
        $this->group($this->alias . '.cc_number_eway');
        return $this;
    }

    /**
     * Order by user_billing.cc_number_eway
     * @param bool $ascending
     * @return static
     */
    public function orderByCcNumberEway(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_number_eway', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.cc_number_eway by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCcNumberEway(string $filterValue): static
    {
        $this->like($this->alias . '.cc_number_eway', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.cc_number_hash
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumberHash(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number_hash', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number_hash from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumberHash(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number_hash', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.cc_number_hash
     * @return static
     */
    public function groupByCcNumberHash(): static
    {
        $this->group($this->alias . '.cc_number_hash');
        return $this;
    }

    /**
     * Order by user_billing.cc_number_hash
     * @param bool $ascending
     * @return static
     */
    public function orderByCcNumberHash(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_number_hash', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.cc_number_hash by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCcNumberHash(string $filterValue): static
    {
        $this->like($this->alias . '.cc_number_hash', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.cc_exp_date
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcExpDate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_exp_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_exp_date from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcExpDate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_exp_date', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.cc_exp_date
     * @return static
     */
    public function groupByCcExpDate(): static
    {
        $this->group($this->alias . '.cc_exp_date');
        return $this;
    }

    /**
     * Order by user_billing.cc_exp_date
     * @param bool $ascending
     * @return static
     */
    public function orderByCcExpDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_exp_date', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.cc_exp_date by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCcExpDate(string $filterValue): static
    {
        $this->like($this->alias . '.cc_exp_date', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.use_card
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUseCard(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.use_card', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.use_card from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUseCard(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.use_card', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.use_card
     * @return static
     */
    public function groupByUseCard(): static
    {
        $this->group($this->alias . '.use_card');
        return $this;
    }

    /**
     * Order by user_billing.use_card
     * @param bool $ascending
     * @return static
     */
    public function orderByUseCard(bool $ascending = true): static
    {
        $this->order($this->alias . '.use_card', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.use_card
     * @param bool $filterValue
     * @return static
     */
    public function filterUseCardGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_card', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.use_card
     * @param bool $filterValue
     * @return static
     */
    public function filterUseCardGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_card', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.use_card
     * @param bool $filterValue
     * @return static
     */
    public function filterUseCardLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_card', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.use_card
     * @param bool $filterValue
     * @return static
     */
    public function filterUseCardLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_card', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.bank_routing_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankRoutingNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_routing_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_routing_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankRoutingNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_routing_number', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_routing_number
     * @return static
     */
    public function groupByBankRoutingNumber(): static
    {
        $this->group($this->alias . '.bank_routing_number');
        return $this;
    }

    /**
     * Order by user_billing.bank_routing_number
     * @param bool $ascending
     * @return static
     */
    public function orderByBankRoutingNumber(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_routing_number', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_routing_number by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankRoutingNumber(string $filterValue): static
    {
        $this->like($this->alias . '.bank_routing_number', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_number', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_account_number
     * @return static
     */
    public function groupByBankAccountNumber(): static
    {
        $this->group($this->alias . '.bank_account_number');
        return $this;
    }

    /**
     * Order by user_billing.bank_account_number
     * @param bool $ascending
     * @return static
     */
    public function orderByBankAccountNumber(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_account_number', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_account_number by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankAccountNumber(string $filterValue): static
    {
        $this->like($this->alias . '.bank_account_number', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_account_type
     * @return static
     */
    public function groupByBankAccountType(): static
    {
        $this->group($this->alias . '.bank_account_type');
        return $this;
    }

    /**
     * Order by user_billing.bank_account_type
     * @param bool $ascending
     * @return static
     */
    public function orderByBankAccountType(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_account_type', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_account_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankAccountType(string $filterValue): static
    {
        $this->like($this->alias . '.bank_account_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.bank_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_name
     * @return static
     */
    public function groupByBankName(): static
    {
        $this->group($this->alias . '.bank_name');
        return $this;
    }

    /**
     * Order by user_billing.bank_name
     * @param bool $ascending
     * @return static
     */
    public function orderByBankName(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_name', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankName(string $filterValue): static
    {
        $this->like($this->alias . '.bank_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_account_name
     * @return static
     */
    public function groupByBankAccountName(): static
    {
        $this->group($this->alias . '.bank_account_name');
        return $this;
    }

    /**
     * Order by user_billing.bank_account_name
     * @param bool $ascending
     * @return static
     */
    public function orderByBankAccountName(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_account_name', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_account_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankAccountName(string $filterValue): static
    {
        $this->like($this->alias . '.bank_account_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.address3
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress3(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address3', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address3 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress3(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address3', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.address3
     * @return static
     */
    public function groupByAddress3(): static
    {
        $this->group($this->alias . '.address3');
        return $this;
    }

    /**
     * Order by user_billing.address3
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress3(bool $ascending = true): static
    {
        $this->order($this->alias . '.address3', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.address3 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress3(string $filterValue): static
    {
        $this->like($this->alias . '.address3', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.contact_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterContactType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.contact_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.contact_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipContactType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.contact_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.contact_type
     * @return static
     */
    public function groupByContactType(): static
    {
        $this->group($this->alias . '.contact_type');
        return $this;
    }

    /**
     * Order by user_billing.contact_type
     * @param bool $ascending
     * @return static
     */
    public function orderByContactType(bool $ascending = true): static
    {
        $this->order($this->alias . '.contact_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_holder_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountHolderType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_holder_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_holder_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountHolderType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_holder_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.bank_account_holder_type
     * @return static
     */
    public function groupByBankAccountHolderType(): static
    {
        $this->group($this->alias . '.bank_account_holder_type');
        return $this;
    }

    /**
     * Order by user_billing.bank_account_holder_type
     * @param bool $ascending
     * @return static
     */
    public function orderByBankAccountHolderType(bool $ascending = true): static
    {
        $this->order($this->alias . '.bank_account_holder_type', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.bank_account_holder_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBankAccountHolderType(string $filterValue): static
    {
        $this->like($this->alias . '.bank_account_holder_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cpi
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCpi(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cpi', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cpi from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCpi(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cpi', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.auth_net_cpi
     * @return static
     */
    public function groupByAuthNetCpi(): static
    {
        $this->group($this->alias . '.auth_net_cpi');
        return $this;
    }

    /**
     * Order by user_billing.auth_net_cpi
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetCpi(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_cpi', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.auth_net_cpi by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetCpi(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_cpi', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cppi
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCppi(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cppi', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cppi from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCppi(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cppi', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.auth_net_cppi
     * @return static
     */
    public function groupByAuthNetCppi(): static
    {
        $this->group($this->alias . '.auth_net_cppi');
        return $this;
    }

    /**
     * Order by user_billing.auth_net_cppi
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetCppi(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_cppi', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.auth_net_cppi by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetCppi(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_cppi', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cai
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCai(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cai', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cai from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCai(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cai', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.auth_net_cai
     * @return static
     */
    public function groupByAuthNetCai(): static
    {
        $this->group($this->alias . '.auth_net_cai');
        return $this;
    }

    /**
     * Order by user_billing.auth_net_cai
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetCai(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_cai', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.auth_net_cai by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetCai(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_cai', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.pay_trace_cust_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayTraceCustId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_cust_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.pay_trace_cust_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayTraceCustId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_cust_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.pay_trace_cust_id
     * @return static
     */
    public function groupByPayTraceCustId(): static
    {
        $this->group($this->alias . '.pay_trace_cust_id');
        return $this;
    }

    /**
     * Order by user_billing.pay_trace_cust_id
     * @param bool $ascending
     * @return static
     */
    public function orderByPayTraceCustId(bool $ascending = true): static
    {
        $this->order($this->alias . '.pay_trace_cust_id', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.pay_trace_cust_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePayTraceCustId(string $filterValue): static
    {
        $this->like($this->alias . '.pay_trace_cust_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.nmi_vault_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNmiVaultId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_vault_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.nmi_vault_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNmiVaultId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_vault_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.nmi_vault_id
     * @return static
     */
    public function groupByNmiVaultId(): static
    {
        $this->group($this->alias . '.nmi_vault_id');
        return $this;
    }

    /**
     * Order by user_billing.nmi_vault_id
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiVaultId(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_vault_id', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.nmi_vault_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNmiVaultId(string $filterValue): static
    {
        $this->like($this->alias . '.nmi_vault_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.opayo_token_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOpayoTokenId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_token_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.opayo_token_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOpayoTokenId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_token_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.opayo_token_id
     * @return static
     */
    public function groupByOpayoTokenId(): static
    {
        $this->group($this->alias . '.opayo_token_id');
        return $this;
    }

    /**
     * Order by user_billing.opayo_token_id
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoTokenId(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_token_id', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.opayo_token_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOpayoTokenId(string $filterValue): static
    {
        $this->like($this->alias . '.opayo_token_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.eway_token_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayTokenId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_token_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.eway_token_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayTokenId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_token_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.eway_token_id
     * @return static
     */
    public function groupByEwayTokenId(): static
    {
        $this->group($this->alias . '.eway_token_id');
        return $this;
    }

    /**
     * Order by user_billing.eway_token_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEwayTokenId(bool $ascending = true): static
    {
        $this->order($this->alias . '.eway_token_id', $ascending);
        return $this;
    }

    /**
     * Filter user_billing.eway_token_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEwayTokenId(string $filterValue): static
    {
        $this->like($this->alias . '.eway_token_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_billing.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_billing.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_billing.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_billing.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_billing.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_billing.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_billing.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_billing.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_billing.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_billing.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_billing.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_billing.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
