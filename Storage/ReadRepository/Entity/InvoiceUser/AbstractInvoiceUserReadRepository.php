<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceUser;

use InvoiceUser;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceUserReadRepository
 * @method InvoiceUser[] loadEntities()
 * @method InvoiceUser|null loadEntity()
 */
abstract class AbstractInvoiceUserReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_USER;
    protected string $alias = Db::A_INVOICE_USER;

    /**
     * Filter by invoice_user.invoice_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.invoice_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by invoice_user.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.username', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.username', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.username
     * @return static
     */
    public function groupByUsername(): static
    {
        $this->group($this->alias . '.username');
        return $this;
    }

    /**
     * Order by invoice_user.username
     * @param bool $ascending
     * @return static
     */
    public function orderByUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.username', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeUsername(string $filterValue): static
    {
        $this->like($this->alias . '.username', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.email
     * @return static
     */
    public function groupByEmail(): static
    {
        $this->group($this->alias . '.email');
        return $this;
    }

    /**
     * Order by invoice_user.email
     * @param bool $ascending
     * @return static
     */
    public function orderByEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.email', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmail(string $filterValue): static
    {
        $this->like($this->alias . '.email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.customer_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCustomerNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.customer_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.customer_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCustomerNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.customer_no', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.customer_no
     * @return static
     */
    public function groupByCustomerNo(): static
    {
        $this->group($this->alias . '.customer_no');
        return $this;
    }

    /**
     * Order by invoice_user.customer_no
     * @param bool $ascending
     * @return static
     */
    public function orderByCustomerNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.customer_no', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.first_name
     * @return static
     */
    public function groupByFirstName(): static
    {
        $this->group($this->alias . '.first_name');
        return $this;
    }

    /**
     * Order by invoice_user.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstName(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.first_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFirstName(string $filterValue): static
    {
        $this->like($this->alias . '.first_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.last_name
     * @return static
     */
    public function groupByLastName(): static
    {
        $this->group($this->alias . '.last_name');
        return $this;
    }

    /**
     * Order by invoice_user.last_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLastName(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.last_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLastName(string $filterValue): static
    {
        $this->like($this->alias . '.last_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.phone
     * @return static
     */
    public function groupByPhone(): static
    {
        $this->group($this->alias . '.phone');
        return $this;
    }

    /**
     * Order by invoice_user.phone
     * @param bool $ascending
     * @return static
     */
    public function orderByPhone(bool $ascending = true): static
    {
        $this->order($this->alias . '.phone', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.phone by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePhone(string $filterValue): static
    {
        $this->like($this->alias . '.phone', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by invoice_user.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by invoice_user.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.identification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIdentification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.identification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIdentification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.identification
     * @return static
     */
    public function groupByIdentification(): static
    {
        $this->group($this->alias . '.identification');
        return $this;
    }

    /**
     * Order by invoice_user.identification
     * @param bool $ascending
     * @return static
     */
    public function orderByIdentification(bool $ascending = true): static
    {
        $this->order($this->alias . '.identification', $ascending);
        return $this;
    }

    /**
     * Filter invoice_user.identification by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIdentification(string $filterValue): static
    {
        $this->like($this->alias . '.identification', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_user.identification_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterIdentificationType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification_type', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.identification_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipIdentificationType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification_type', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.identification_type
     * @return static
     */
    public function groupByIdentificationType(): static
    {
        $this->group($this->alias . '.identification_type');
        return $this;
    }

    /**
     * Order by invoice_user.identification_type
     * @param bool $ascending
     * @return static
     */
    public function orderByIdentificationType(bool $ascending = true): static
    {
        $this->order($this->alias . '.identification_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.identification_type
     * @param int $filterValue
     * @return static
     */
    public function filterIdentificationTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.identification_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_user.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_user.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_user.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_user.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_user.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_user.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_user.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
