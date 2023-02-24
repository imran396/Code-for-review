<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingPayTrace;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingPayTraceDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_PAY_TRACE;
    protected string $alias = Db::A_SETTING_BILLING_PAY_TRACE;

    /**
     * Filter by setting_billing_pay_trace.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.pay_trace_username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayTraceUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_username', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.pay_trace_username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayTraceUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_username', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.pay_trace_password
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayTracePassword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_password', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.pay_trace_password from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayTracePassword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_password', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.pay_trace_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayTraceMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.pay_trace_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayTraceMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcPaymentPayTrace(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_payment_pay_trace', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.cc_payment_pay_trace from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcPaymentPayTrace(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_payment_pay_trace', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAchPaymentPayTrace(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ach_payment_pay_trace', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.ach_payment_pay_trace from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAchPaymentPayTrace(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ach_payment_pay_trace', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.pay_trace_cim
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPayTraceCim(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_cim', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.pay_trace_cim from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPayTraceCim(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_cim', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_billing_pay_trace.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_pay_trace.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
