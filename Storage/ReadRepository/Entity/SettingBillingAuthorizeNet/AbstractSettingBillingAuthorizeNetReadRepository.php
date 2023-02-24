<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingAuthorizeNet;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingAuthorizeNet;

/**
 * Abstract class AbstractSettingBillingAuthorizeNetReadRepository
 * @method SettingBillingAuthorizeNet[] loadEntities()
 * @method SettingBillingAuthorizeNet|null loadEntity()
 */
abstract class AbstractSettingBillingAuthorizeNetReadRepository extends ReadRepositoryBase
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
     * Group by setting_billing_authorize_net.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.auth_net_account_type
     * @return static
     */
    public function groupByAuthNetAccountType(): static
    {
        $this->group($this->alias . '.auth_net_account_type');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.auth_net_account_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetAccountType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_account_type', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_authorize_net.auth_net_account_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetAccountType(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_account_type', "%{$filterValue}%");
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
     * Group by setting_billing_authorize_net.auth_net_login
     * @return static
     */
    public function groupByAuthNetLogin(): static
    {
        $this->group($this->alias . '.auth_net_login');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.auth_net_login
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetLogin(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_login', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_authorize_net.auth_net_login by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetLogin(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_login', "%{$filterValue}%");
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
     * Group by setting_billing_authorize_net.auth_net_trankey
     * @return static
     */
    public function groupByAuthNetTrankey(): static
    {
        $this->group($this->alias . '.auth_net_trankey');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.auth_net_trankey
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetTrankey(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_trankey', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_authorize_net.auth_net_trankey by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetTrankey(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_trankey', "%{$filterValue}%");
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
     * Group by setting_billing_authorize_net.auth_net_mode
     * @return static
     */
    public function groupByAuthNetMode(): static
    {
        $this->group($this->alias . '.auth_net_mode');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.auth_net_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_authorize_net.auth_net_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthNetMode(string $filterValue): static
    {
        $this->like($this->alias . '.auth_net_mode', "%{$filterValue}%");
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
     * Group by setting_billing_authorize_net.auth_net_cim
     * @return static
     */
    public function groupByAuthNetCim(): static
    {
        $this->group($this->alias . '.auth_net_cim');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.auth_net_cim
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthNetCim(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_net_cim', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.auth_net_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterAuthNetCimGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_net_cim', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.auth_net_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterAuthNetCimGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_net_cim', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.auth_net_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterAuthNetCimLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_net_cim', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.auth_net_cim
     * @param bool $filterValue
     * @return static
     */
    public function filterAuthNetCimLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_net_cim', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.cc_payment
     * @return static
     */
    public function groupByCcPayment(): static
    {
        $this->group($this->alias . '.cc_payment');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.cc_payment
     * @param bool $ascending
     * @return static
     */
    public function orderByCcPayment(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_payment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.cc_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.cc_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.cc_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.cc_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.ach_payment
     * @return static
     */
    public function groupByAchPayment(): static
    {
        $this->group($this->alias . '.ach_payment');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.ach_payment
     * @param bool $ascending
     * @return static
     */
    public function orderByAchPayment(bool $ascending = true): static
    {
        $this->order($this->alias . '.ach_payment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.ach_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.ach_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.ach_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.ach_payment
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_billing_authorize_net.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_billing_authorize_net.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_authorize_net.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_authorize_net.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_authorize_net.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_authorize_net.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_authorize_net.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
