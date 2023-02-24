<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserCustField;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserCustFieldDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_USER_CUST_FIELD;
    protected string $alias = Db::A_USER_CUST_FIELD;

    /**
     * Filter by user_cust_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.order
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOrder(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.order from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOrder(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.panel
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPanel(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.panel', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.panel from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPanel(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.panel', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.parameters
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterParameters(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parameters', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.parameters from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipParameters(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parameters', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.required
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequired(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.required', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.required from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequired(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.required', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.on_registration
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnRegistration(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_registration', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_registration from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnRegistration(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_registration', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.on_profile
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnProfile(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_profile', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_profile from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnProfile(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_profile', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.encrypted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEncrypted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.encrypted', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.encrypted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEncrypted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.encrypted', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.in_admin_search
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminSearch(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_search', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_admin_search from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminSearch(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_search', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.in_admin_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_admin_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_catalog', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.in_invoices
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInInvoices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_invoices', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_invoices from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInInvoices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_invoices', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.in_settlements
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInSettlements(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_settlements', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_settlements from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInSettlements(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_settlements', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.on_add_new_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnAddNewBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_add_new_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_add_new_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnAddNewBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_add_new_bidder', $skipValue);
        return $this;
    }

    /**
     * Filter by user_cust_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
