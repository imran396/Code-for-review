<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceUserShipping;

use InvoiceUserShipping;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceUserShippingReadRepository
 * @method InvoiceUserShipping[] loadEntities()
 * @method InvoiceUserShipping|null loadEntity()
 */
abstract class AbstractInvoiceUserShippingReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_USER_SHIPPING;
    protected string $alias = Db::A_INVOICE_USER_SHIPPING;

    /**
     * Filter by invoice_user_shipping.invoice_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.invoice_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.company_name
     * @return static
     */
    public function groupByCompanyName(): static
    {
        $this->group($this->alias . '.company_name');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByCompanyName(bool $ascending = true): static
    {
        $this->order($this->alias . '.company_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.company_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCompanyName(string $filterValue): static
    {
        $this->like($this->alias . '.company_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.first_name
     * @return static
     */
    public function groupByFirstName(): static
    {
        $this->group($this->alias . '.first_name');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstName(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.first_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFirstName(string $filterValue): static
    {
        $this->like($this->alias . '.first_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.last_name
     * @return static
     */
    public function groupByLastName(): static
    {
        $this->group($this->alias . '.last_name');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.last_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLastName(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.last_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLastName(string $filterValue): static
    {
        $this->like($this->alias . '.last_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.fax
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFax(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.fax from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFax(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fax', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.fax
     * @return static
     */
    public function groupByFax(): static
    {
        $this->group($this->alias . '.fax');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.fax
     * @param bool $ascending
     * @return static
     */
    public function orderByFax(bool $ascending = true): static
    {
        $this->order($this->alias . '.fax', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.fax by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFax(string $filterValue): static
    {
        $this->like($this->alias . '.fax', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.address2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address2', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.address2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address2', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.address2
     * @return static
     */
    public function groupByAddress2(): static
    {
        $this->group($this->alias . '.address2');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.address2
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress2(bool $ascending = true): static
    {
        $this->order($this->alias . '.address2', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.address2 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress2(string $filterValue): static
    {
        $this->like($this->alias . '.address2', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.address3
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress3(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address3', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.address3 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress3(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address3', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.address3
     * @return static
     */
    public function groupByAddress3(): static
    {
        $this->group($this->alias . '.address3');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.address3
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress3(bool $ascending = true): static
    {
        $this->order($this->alias . '.address3', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.address3 by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress3(string $filterValue): static
    {
        $this->like($this->alias . '.address3', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.zip
     * @return static
     */
    public function groupByZip(): static
    {
        $this->group($this->alias . '.zip');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.zip
     * @param bool $ascending
     * @return static
     */
    public function orderByZip(bool $ascending = true): static
    {
        $this->order($this->alias . '.zip', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user_shipping.zip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeZip(string $filterValue): static
    {
        $this->like($this->alias . '.zip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user_shipping.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_shipping.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user_shipping.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_user_shipping.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user_shipping.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
