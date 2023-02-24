<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingSmartPay;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingSmartPay;

/**
 * Abstract class AbstractSettingBillingSmartPayReadRepository
 * @method SettingBillingSmartPay[] loadEntities()
 * @method SettingBillingSmartPay|null loadEntity()
 */
abstract class AbstractSettingBillingSmartPayReadRepository extends ReadRepositoryBase
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
     * Group by setting_billing_smart_pay.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.smart_pay_account_type
     * @return static
     */
    public function groupBySmartPayAccountType(): static
    {
        $this->group($this->alias . '.smart_pay_account_type');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_account_type
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPayAccountType(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_account_type', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_smart_pay.smart_pay_account_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmartPayAccountType(string $filterValue): static
    {
        $this->like($this->alias . '.smart_pay_account_type', "%{$filterValue}%");
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
     * Group by setting_billing_smart_pay.smart_pay_skin_code
     * @return static
     */
    public function groupBySmartPaySkinCode(): static
    {
        $this->group($this->alias . '.smart_pay_skin_code');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_skin_code
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPaySkinCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_skin_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_smart_pay.smart_pay_skin_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmartPaySkinCode(string $filterValue): static
    {
        $this->like($this->alias . '.smart_pay_skin_code', "%{$filterValue}%");
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
     * Group by setting_billing_smart_pay.smart_pay_mode
     * @return static
     */
    public function groupBySmartPayMode(): static
    {
        $this->group($this->alias . '.smart_pay_mode');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_mode
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPayMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_smart_pay.smart_pay_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmartPayMode(string $filterValue): static
    {
        $this->like($this->alias . '.smart_pay_mode', "%{$filterValue}%");
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
     * Group by setting_billing_smart_pay.smart_pay_merchant_account
     * @return static
     */
    public function groupBySmartPayMerchantAccount(): static
    {
        $this->group($this->alias . '.smart_pay_merchant_account');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_merchant_account
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPayMerchantAccount(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_merchant_account', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_smart_pay.smart_pay_merchant_account by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmartPayMerchantAccount(string $filterValue): static
    {
        $this->like($this->alias . '.smart_pay_merchant_account', "%{$filterValue}%");
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
     * Group by setting_billing_smart_pay.smart_pay_shared_secret
     * @return static
     */
    public function groupBySmartPaySharedSecret(): static
    {
        $this->group($this->alias . '.smart_pay_shared_secret');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_shared_secret
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPaySharedSecret(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_shared_secret', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_smart_pay.smart_pay_shared_secret by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmartPaySharedSecret(string $filterValue): static
    {
        $this->like($this->alias . '.smart_pay_shared_secret', "%{$filterValue}%");
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
     * Group by setting_billing_smart_pay.smart_pay_merchant_mode
     * @return static
     */
    public function groupBySmartPayMerchantMode(): static
    {
        $this->group($this->alias . '.smart_pay_merchant_mode');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.smart_pay_merchant_mode
     * @param bool $ascending
     * @return static
     */
    public function orderBySmartPayMerchantMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.smart_pay_merchant_mode', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.smart_pay_merchant_mode
     * @param int $filterValue
     * @return static
     */
    public function filterSmartPayMerchantModeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smart_pay_merchant_mode', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.smart_pay_merchant_mode
     * @param int $filterValue
     * @return static
     */
    public function filterSmartPayMerchantModeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smart_pay_merchant_mode', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.smart_pay_merchant_mode
     * @param int $filterValue
     * @return static
     */
    public function filterSmartPayMerchantModeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smart_pay_merchant_mode', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.smart_pay_merchant_mode
     * @param int $filterValue
     * @return static
     */
    public function filterSmartPayMerchantModeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smart_pay_merchant_mode', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.enable_smart_payments
     * @return static
     */
    public function groupByEnableSmartPayments(): static
    {
        $this->group($this->alias . '.enable_smart_payments');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.enable_smart_payments
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableSmartPayments(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_smart_payments', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.enable_smart_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSmartPaymentsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_smart_payments', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.enable_smart_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSmartPaymentsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_smart_payments', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.enable_smart_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSmartPaymentsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_smart_payments', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.enable_smart_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSmartPaymentsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_smart_payments', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_billing_smart_pay.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_billing_smart_pay.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_smart_pay.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_smart_pay.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_smart_pay.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_smart_pay.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_smart_pay.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
