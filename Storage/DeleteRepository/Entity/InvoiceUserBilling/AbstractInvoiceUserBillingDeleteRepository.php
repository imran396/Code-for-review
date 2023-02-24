<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceUserBilling;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceUserBillingDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_USER_BILLING;
    protected string $alias = Db::A_INVOICE_USER_BILLING;

    /**
     * Filter by invoice_user_billing.invoice_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.invoice_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.fax
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFax(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.fax from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFax(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fax', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.address2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address2', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.address2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address2', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.address3
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress3(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address3', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.address3 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress3(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address3', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_user_billing.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_user_billing.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
