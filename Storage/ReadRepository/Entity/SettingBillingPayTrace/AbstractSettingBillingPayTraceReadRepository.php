<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingPayTrace;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingPayTrace;

/**
 * Abstract class AbstractSettingBillingPayTraceReadRepository
 * @method SettingBillingPayTrace[] loadEntities()
 * @method SettingBillingPayTrace|null loadEntity()
 */
abstract class AbstractSettingBillingPayTraceReadRepository extends ReadRepositoryBase
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
     * Group by setting_billing_pay_trace.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.pay_trace_username
     * @return static
     */
    public function groupByPayTraceUsername(): static
    {
        $this->group($this->alias . '.pay_trace_username');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.pay_trace_username
     * @param bool $ascending
     * @return static
     */
    public function orderByPayTraceUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.pay_trace_username', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_pay_trace.pay_trace_username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePayTraceUsername(string $filterValue): static
    {
        $this->like($this->alias . '.pay_trace_username', "%{$filterValue}%");
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
     * Group by setting_billing_pay_trace.pay_trace_password
     * @return static
     */
    public function groupByPayTracePassword(): static
    {
        $this->group($this->alias . '.pay_trace_password');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.pay_trace_password
     * @param bool $ascending
     * @return static
     */
    public function orderByPayTracePassword(bool $ascending = true): static
    {
        $this->order($this->alias . '.pay_trace_password', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_pay_trace.pay_trace_password by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePayTracePassword(string $filterValue): static
    {
        $this->like($this->alias . '.pay_trace_password', "%{$filterValue}%");
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
     * Group by setting_billing_pay_trace.pay_trace_mode
     * @return static
     */
    public function groupByPayTraceMode(): static
    {
        $this->group($this->alias . '.pay_trace_mode');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.pay_trace_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByPayTraceMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.pay_trace_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_pay_trace.pay_trace_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePayTraceMode(string $filterValue): static
    {
        $this->like($this->alias . '.pay_trace_mode', "%{$filterValue}%");
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
     * Group by setting_billing_pay_trace.cc_payment_pay_trace
     * @return static
     */
    public function groupByCcPaymentPayTrace(): static
    {
        $this->group($this->alias . '.cc_payment_pay_trace');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool $ascending
     * @return static
     */
    public function orderByCcPaymentPayTrace(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_payment_pay_trace', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentPayTraceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_pay_trace', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentPayTraceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_pay_trace', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentPayTraceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_pay_trace', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.cc_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentPayTraceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_pay_trace', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.ach_payment_pay_trace
     * @return static
     */
    public function groupByAchPaymentPayTrace(): static
    {
        $this->group($this->alias . '.ach_payment_pay_trace');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool $ascending
     * @return static
     */
    public function orderByAchPaymentPayTrace(bool $ascending = true): static
    {
        $this->order($this->alias . '.ach_payment_pay_trace', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentPayTraceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_pay_trace', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentPayTraceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_pay_trace', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentPayTraceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_pay_trace', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.ach_payment_pay_trace
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentPayTraceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_pay_trace', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.pay_trace_cim
     * @return static
     */
    public function groupByPayTraceCim(): static
    {
        $this->group($this->alias . '.pay_trace_cim');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.pay_trace_cim
     * @param bool $ascending
     * @return static
     */
    public function orderByPayTraceCim(bool $ascending = true): static
    {
        $this->order($this->alias . '.pay_trace_cim', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.pay_trace_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterPayTraceCimGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pay_trace_cim', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.pay_trace_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterPayTraceCimGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pay_trace_cim', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.pay_trace_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterPayTraceCimLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pay_trace_cim', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.pay_trace_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterPayTraceCimLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pay_trace_cim', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_billing_pay_trace.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_billing_pay_trace.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_pay_trace.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_pay_trace.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_pay_trace.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_pay_trace.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_pay_trace.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
