<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingAuthorizeNet;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingAuthorizeNetDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_AUTHORIZE_NET;
    protected string $alias = Db::A_SETTING_BILLING_AUTHORIZE_NET;

    /**
     * Filter by setting_billing_authorize_net.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.auth_net_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.auth_net_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_account_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.auth_net_login
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetLogin(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_login', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.auth_net_login from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetLogin(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_login', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.auth_net_trankey
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetTrankey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_trankey', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.auth_net_trankey from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetTrankey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_trankey', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.auth_net_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.auth_net_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.auth_net_cim
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuthNetCim(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cim', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.auth_net_cim from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuthNetCim(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cim', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.cc_payment
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcPayment(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_payment', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.cc_payment from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcPayment(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_payment', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.ach_payment
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAchPayment(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ach_payment', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.ach_payment from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAchPayment(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ach_payment', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_authorize_net.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_authorize_net.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
