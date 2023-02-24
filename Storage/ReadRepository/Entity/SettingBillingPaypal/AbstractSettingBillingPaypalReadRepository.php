<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingPaypal;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingPaypal;

/**
 * Abstract class AbstractSettingBillingPaypalReadRepository
 * @method SettingBillingPaypal[] loadEntities()
 * @method SettingBillingPaypal|null loadEntity()
 */
abstract class AbstractSettingBillingPaypalReadRepository extends ReadRepositoryBase
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
     * Group by setting_billing_paypal.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_billing_paypal.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_billing_paypal.enable_paypal_payments
     * @return static
     */
    public function groupByEnablePaypalPayments(): static
    {
        $this->group($this->alias . '.enable_paypal_payments');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.enable_paypal_payments
     * @param bool $ascending
     * @return static
     */
    public function orderByEnablePaypalPayments(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_paypal_payments', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.enable_paypal_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnablePaypalPaymentsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_paypal_payments', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.enable_paypal_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnablePaypalPaymentsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_paypal_payments', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.enable_paypal_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnablePaypalPaymentsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_paypal_payments', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.enable_paypal_payments
     * @param bool $filterValue
     * @return static
     */
    public function filterEnablePaypalPaymentsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_paypal_payments', $filterValue, '<=');
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
     * Group by setting_billing_paypal.paypal_email
     * @return static
     */
    public function groupByPaypalEmail(): static
    {
        $this->group($this->alias . '.paypal_email');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.paypal_email
     * @param bool $ascending
     * @return static
     */
    public function orderByPaypalEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.paypal_email', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_paypal.paypal_email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaypalEmail(string $filterValue): static
    {
        $this->like($this->alias . '.paypal_email', "%{$filterValue}%");
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
     * Group by setting_billing_paypal.paypal_identity_token
     * @return static
     */
    public function groupByPaypalIdentityToken(): static
    {
        $this->group($this->alias . '.paypal_identity_token');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.paypal_identity_token
     * @param bool $ascending
     * @return static
     */
    public function orderByPaypalIdentityToken(bool $ascending = true): static
    {
        $this->order($this->alias . '.paypal_identity_token', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_paypal.paypal_identity_token by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaypalIdentityToken(string $filterValue): static
    {
        $this->like($this->alias . '.paypal_identity_token', "%{$filterValue}%");
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
     * Group by setting_billing_paypal.paypal_account_type
     * @return static
     */
    public function groupByPaypalAccountType(): static
    {
        $this->group($this->alias . '.paypal_account_type');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.paypal_account_type
     * @param bool $ascending
     * @return static
     */
    public function orderByPaypalAccountType(bool $ascending = true): static
    {
        $this->order($this->alias . '.paypal_account_type', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_paypal.paypal_account_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaypalAccountType(string $filterValue): static
    {
        $this->like($this->alias . '.paypal_account_type', "%{$filterValue}%");
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
     * Group by setting_billing_paypal.paypal_bn_code
     * @return static
     */
    public function groupByPaypalBnCode(): static
    {
        $this->group($this->alias . '.paypal_bn_code');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.paypal_bn_code
     * @param bool $ascending
     * @return static
     */
    public function orderByPaypalBnCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.paypal_bn_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_paypal.paypal_bn_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaypalBnCode(string $filterValue): static
    {
        $this->like($this->alias . '.paypal_bn_code', "%{$filterValue}%");
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
     * Group by setting_billing_paypal.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_billing_paypal.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_billing_paypal.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_billing_paypal.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_billing_paypal.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_paypal.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_paypal.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_paypal.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_paypal.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_paypal.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
