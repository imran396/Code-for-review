<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingOpayo;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingOpayo;

/**
 * Abstract class AbstractSettingBillingOpayoReadRepository
 * @method SettingBillingOpayo[] loadEntities()
 * @method SettingBillingOpayo|null loadEntity()
 */
abstract class AbstractSettingBillingOpayoReadRepository extends ReadRepositoryBase
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
     * Group by setting_billing_opayo.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_billing_opayo.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_billing_opayo.cc_payment_opayo
     * @return static
     */
    public function groupByCcPaymentOpayo(): static
    {
        $this->group($this->alias . '.cc_payment_opayo');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.cc_payment_opayo
     * @param bool $ascending
     * @return static
     */
    public function orderByCcPaymentOpayo(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_payment_opayo', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.cc_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentOpayoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_opayo', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.cc_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentOpayoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_opayo', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.cc_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentOpayoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_opayo', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.cc_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentOpayoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_opayo', $filterValue, '<=');
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
     * Group by setting_billing_opayo.ach_payment_opayo
     * @return static
     */
    public function groupByAchPaymentOpayo(): static
    {
        $this->group($this->alias . '.ach_payment_opayo');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.ach_payment_opayo
     * @param bool $ascending
     * @return static
     */
    public function orderByAchPaymentOpayo(bool $ascending = true): static
    {
        $this->order($this->alias . '.ach_payment_opayo', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.ach_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentOpayoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_opayo', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.ach_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentOpayoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_opayo', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.ach_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentOpayoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_opayo', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.ach_payment_opayo
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentOpayoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_opayo', $filterValue, '<=');
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
     * Group by setting_billing_opayo.opayo_vendor_name
     * @return static
     */
    public function groupByOpayoVendorName(): static
    {
        $this->group($this->alias . '.opayo_vendor_name');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_vendor_name
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoVendorName(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_vendor_name', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_opayo.opayo_vendor_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOpayoVendorName(string $filterValue): static
    {
        $this->like($this->alias . '.opayo_vendor_name', "%{$filterValue}%");
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
     * Group by setting_billing_opayo.opayo_avscv2
     * @return static
     */
    public function groupByOpayoAvscv2(): static
    {
        $this->group($this->alias . '.opayo_avscv2');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_avscv2
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoAvscv2(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_avscv2', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.opayo_avscv2
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAvscv2Greater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_avscv2', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.opayo_avscv2
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAvscv2GreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_avscv2', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.opayo_avscv2
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAvscv2Less(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_avscv2', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.opayo_avscv2
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAvscv2LessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_avscv2', $filterValue, '<=');
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
     * Group by setting_billing_opayo.opayo_3dsecure
     * @return static
     */
    public function groupByOpayo3dsecure(): static
    {
        $this->group($this->alias . '.opayo_3dsecure');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_3dsecure
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayo3dsecure(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_3dsecure', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.opayo_3dsecure
     * @param int $filterValue
     * @return static
     */
    public function filterOpayo3dsecureGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_3dsecure', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.opayo_3dsecure
     * @param int $filterValue
     * @return static
     */
    public function filterOpayo3dsecureGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_3dsecure', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.opayo_3dsecure
     * @param int $filterValue
     * @return static
     */
    public function filterOpayo3dsecureLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_3dsecure', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.opayo_3dsecure
     * @param int $filterValue
     * @return static
     */
    public function filterOpayo3dsecureLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_3dsecure', $filterValue, '<=');
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
     * Group by setting_billing_opayo.opayo_mode
     * @return static
     */
    public function groupByOpayoMode(): static
    {
        $this->group($this->alias . '.opayo_mode');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_opayo.opayo_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOpayoMode(string $filterValue): static
    {
        $this->like($this->alias . '.opayo_mode', "%{$filterValue}%");
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
     * Group by setting_billing_opayo.opayo_send_email
     * @return static
     */
    public function groupByOpayoSendEmail(): static
    {
        $this->group($this->alias . '.opayo_send_email');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_send_email
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoSendEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_send_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.opayo_send_email
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoSendEmailGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_send_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.opayo_send_email
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoSendEmailGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_send_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.opayo_send_email
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoSendEmailLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_send_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.opayo_send_email
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoSendEmailLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_send_email', $filterValue, '<=');
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
     * Group by setting_billing_opayo.opayo_token
     * @return static
     */
    public function groupByOpayoToken(): static
    {
        $this->group($this->alias . '.opayo_token');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_token
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoToken(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_token', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.opayo_token
     * @param bool $filterValue
     * @return static
     */
    public function filterOpayoTokenGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_token', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.opayo_token
     * @param bool $filterValue
     * @return static
     */
    public function filterOpayoTokenGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_token', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.opayo_token
     * @param bool $filterValue
     * @return static
     */
    public function filterOpayoTokenLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_token', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.opayo_token
     * @param bool $filterValue
     * @return static
     */
    public function filterOpayoTokenLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_token', $filterValue, '<=');
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
     * Group by setting_billing_opayo.opayo_currency
     * @return static
     */
    public function groupByOpayoCurrency(): static
    {
        $this->group($this->alias . '.opayo_currency');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_currency
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoCurrency(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_currency', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_opayo.opayo_currency by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOpayoCurrency(string $filterValue): static
    {
        $this->like($this->alias . '.opayo_currency', "%{$filterValue}%");
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
     * Group by setting_billing_opayo.opayo_auth_transaction_type
     * @return static
     */
    public function groupByOpayoAuthTransactionType(): static
    {
        $this->group($this->alias . '.opayo_auth_transaction_type');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.opayo_auth_transaction_type
     * @param bool $ascending
     * @return static
     */
    public function orderByOpayoAuthTransactionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.opayo_auth_transaction_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.opayo_auth_transaction_type
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAuthTransactionTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_auth_transaction_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.opayo_auth_transaction_type
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAuthTransactionTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_auth_transaction_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.opayo_auth_transaction_type
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAuthTransactionTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_auth_transaction_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.opayo_auth_transaction_type
     * @param int $filterValue
     * @return static
     */
    public function filterOpayoAuthTransactionTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.opayo_auth_transaction_type', $filterValue, '<=');
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
     * Group by setting_billing_opayo.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_billing_opayo.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_billing_opayo.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_billing_opayo.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_billing_opayo.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_opayo.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_opayo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_opayo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_opayo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_opayo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
