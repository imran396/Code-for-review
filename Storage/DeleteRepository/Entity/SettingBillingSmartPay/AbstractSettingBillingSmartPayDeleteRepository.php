<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingSmartPay;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingSmartPayDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_SMART_PAY;
    protected string $alias = Db::A_SETTING_BILLING_SMART_PAY;

    /**
     * Filter by setting_billing_smart_pay.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmartPayAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmartPayAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_account_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_skin_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmartPaySkinCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_skin_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_skin_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmartPaySkinCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_skin_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmartPayMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmartPayMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_merchant_account
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmartPayMerchantAccount(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_merchant_account', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_merchant_account from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmartPayMerchantAccount(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_merchant_account', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_shared_secret
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmartPaySharedSecret(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_shared_secret', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_shared_secret from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmartPaySharedSecret(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_shared_secret', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.smart_pay_merchant_mode
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterSmartPayMerchantMode(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smart_pay_merchant_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.smart_pay_merchant_mode from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipSmartPayMerchantMode(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smart_pay_merchant_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.enable_smart_payments
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableSmartPayments(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_smart_payments', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.enable_smart_payments from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableSmartPayments(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_smart_payments', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_smart_pay.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_smart_pay.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
