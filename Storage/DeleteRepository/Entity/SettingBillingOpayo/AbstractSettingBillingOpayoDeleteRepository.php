<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingOpayo;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingOpayoDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_OPAYO;
    protected string $alias = Db::A_SETTING_BILLING_OPAYO;

    /**
     * Filter by setting_billing_opayo.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.cc_payment_opayo
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcPaymentOpayo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_payment_opayo', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.cc_payment_opayo from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcPaymentOpayo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_payment_opayo', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.ach_payment_opayo
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAchPaymentOpayo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ach_payment_opayo', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.ach_payment_opayo from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAchPaymentOpayo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ach_payment_opayo', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_vendor_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOpayoVendorName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_vendor_name', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_vendor_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOpayoVendorName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_vendor_name', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_avscv2
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOpayoAvscv2(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_avscv2', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_avscv2 from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOpayoAvscv2(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_avscv2', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_3dsecure
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOpayo3dsecure(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_3dsecure', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_3dsecure from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOpayo3dsecure(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_3dsecure', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOpayoMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOpayoMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_send_email
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOpayoSendEmail(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_send_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_send_email from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOpayoSendEmail(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_send_email', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_token
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOpayoToken(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_token', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_token from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOpayoToken(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_token', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_currency
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOpayoCurrency(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_currency', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_currency from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOpayoCurrency(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_currency', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.opayo_auth_transaction_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterOpayoAuthTransactionType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_auth_transaction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.opayo_auth_transaction_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipOpayoAuthTransactionType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_auth_transaction_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_opayo.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_opayo.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
