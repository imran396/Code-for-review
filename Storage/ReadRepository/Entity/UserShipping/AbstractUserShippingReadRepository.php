<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserShipping;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserShipping;

/**
 * Abstract class AbstractUserShippingReadRepository
 * @method UserShipping[] loadEntities()
 * @method UserShipping|null loadEntity()
 */
abstract class AbstractUserShippingReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_SHIPPING;
    protected string $alias = Db::A_USER_SHIPPING;

    /**
     * Filter by user_shipping.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_shipping.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_shipping.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.company_name
     * @return static
     */
    public function groupByCompanyName(): static
    {
        $this->group($this->alias . '.company_name');
        return $this;
    }

    /**
     * Order by user_shipping.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByCompanyName(bool $ascending = true): static
    {
        $this->order($this->alias . '.company_name', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.company_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCompanyName(string $filterValue): static
    {
        $this->like($this->alias . '.company_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.first_name
     * @return static
     */
    public function groupByFirstName(): static
    {
        $this->group($this->alias . '.first_name');
        return $this;
    }

    /**
     * Order by user_shipping.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstName(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_name', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.first_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFirstName(string $filterValue): static
    {
        $this->like($this->alias . '.first_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.last_name
     * @return static
     */
    public function groupByLastName(): static
    {
        $this->group($this->alias . '.last_name');
        return $this;
    }

    /**
     * Order by user_shipping.last_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLastName(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_name', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.last_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLastName(string $filterValue): static
    {
        $this->like($this->alias . '.last_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by user_shipping.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.fax
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFax(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.fax from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFax(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fax', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.fax
     * @return static
     */
    public function groupByFax(): static
    {
        $this->group($this->alias . '.fax');
        return $this;
    }

    /**
     * Order by user_shipping.fax
     * @param bool $ascending
     * @return static
     */
    public function orderByFax(bool $ascending = true): static
    {
        $this->order($this->alias . '.fax', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.fax by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFax(string $filterValue): static
    {
        $this->like($this->alias . '.fax', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by user_shipping.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by user_shipping.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.address2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address2', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.address2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address2', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.address2
     * @return static
     */
    public function groupByAddress2(): static
    {
        $this->group($this->alias . '.address2');
        return $this;
    }

    /**
     * Order by user_shipping.address2
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress2(bool $ascending = true): static
    {
        $this->order($this->alias . '.address2', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.address2 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress2(string $filterValue): static
    {
        $this->like($this->alias . '.address2', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by user_shipping.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by user_shipping.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.zip
     * @return static
     */
    public function groupByZip(): static
    {
        $this->group($this->alias . '.zip');
        return $this;
    }

    /**
     * Order by user_shipping.zip
     * @param bool $ascending
     * @return static
     */
    public function orderByZip(bool $ascending = true): static
    {
        $this->order($this->alias . '.zip', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.zip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeZip(string $filterValue): static
    {
        $this->like($this->alias . '.zip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.address3
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress3(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address3', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.address3 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress3(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address3', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.address3
     * @return static
     */
    public function groupByAddress3(): static
    {
        $this->group($this->alias . '.address3');
        return $this;
    }

    /**
     * Order by user_shipping.address3
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress3(bool $ascending = true): static
    {
        $this->order($this->alias . '.address3', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.address3 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress3(string $filterValue): static
    {
        $this->like($this->alias . '.address3', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.contact_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterContactType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.contact_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.contact_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipContactType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.contact_type', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.contact_type
     * @return static
     */
    public function groupByContactType(): static
    {
        $this->group($this->alias . '.contact_type');
        return $this;
    }

    /**
     * Order by user_shipping.contact_type
     * @param bool $ascending
     * @return static
     */
    public function orderByContactType(bool $ascending = true): static
    {
        $this->order($this->alias . '.contact_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.contact_type
     * @param int $filterValue
     * @return static
     */
    public function filterContactTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.contact_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.carrier_method
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCarrierMethod(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.carrier_method', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.carrier_method from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCarrierMethod(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.carrier_method', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.carrier_method
     * @return static
     */
    public function groupByCarrierMethod(): static
    {
        $this->group($this->alias . '.carrier_method');
        return $this;
    }

    /**
     * Order by user_shipping.carrier_method
     * @param bool $ascending
     * @return static
     */
    public function orderByCarrierMethod(bool $ascending = true): static
    {
        $this->order($this->alias . '.carrier_method', $ascending);
        return $this;
    }

    /**
     * Filter user_shipping.carrier_method by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCarrierMethod(string $filterValue): static
    {
        $this->like($this->alias . '.carrier_method', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_shipping.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_shipping.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_shipping.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_shipping.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_shipping.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_shipping.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_shipping.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_shipping.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_shipping.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
