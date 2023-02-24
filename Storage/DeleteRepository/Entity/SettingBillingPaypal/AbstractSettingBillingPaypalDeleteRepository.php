<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingPaypal;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingPaypalDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_PAYPAL;
    protected string $alias = Db::A_SETTING_BILLING_PAYPAL;

    /**
     * Filter by setting_billing_paypal.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.enable_paypal_payments
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnablePaypalPayments(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_paypal_payments', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.enable_paypal_payments from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnablePaypalPayments(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_paypal_payments', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.paypal_email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaypalEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.paypal_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.paypal_email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaypalEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.paypal_email', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.paypal_identity_token
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaypalIdentityToken(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.paypal_identity_token', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.paypal_identity_token from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaypalIdentityToken(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.paypal_identity_token', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.paypal_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaypalAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.paypal_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.paypal_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaypalAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.paypal_account_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.paypal_bn_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaypalBnCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.paypal_bn_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.paypal_bn_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaypalBnCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.paypal_bn_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_paypal.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_paypal.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
