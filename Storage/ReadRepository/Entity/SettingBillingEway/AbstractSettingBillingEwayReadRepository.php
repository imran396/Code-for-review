<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingEway;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingEway;

/**
 * Abstract class AbstractSettingBillingEwayReadRepository
 * @method SettingBillingEway[] loadEntities()
 * @method SettingBillingEway|null loadEntity()
 */
abstract class AbstractSettingBillingEwayReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_EWAY;
    protected string $alias = Db::A_SETTING_BILLING_EWAY;

    /**
     * Filter by setting_billing_eway.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_eway.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_eway.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.eway_api_key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayApiKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_api_key', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.eway_api_key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayApiKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_api_key', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.eway_api_key
     * @return static
     */
    public function groupByEwayApiKey(): static
    {
        $this->group($this->alias . '.eway_api_key');
        return $this;
    }

    /**
     * Order by setting_billing_eway.eway_api_key
     * @param bool $ascending
     * @return static
     */
    public function orderByEwayApiKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.eway_api_key', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_eway.eway_api_key by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEwayApiKey(string $filterValue): static
    {
        $this->like($this->alias . '.eway_api_key', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_eway.eway_password
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayPassword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_password', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.eway_password from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayPassword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_password', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.eway_password
     * @return static
     */
    public function groupByEwayPassword(): static
    {
        $this->group($this->alias . '.eway_password');
        return $this;
    }

    /**
     * Order by setting_billing_eway.eway_password
     * @param bool $ascending
     * @return static
     */
    public function orderByEwayPassword(bool $ascending = true): static
    {
        $this->order($this->alias . '.eway_password', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_eway.eway_password by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEwayPassword(string $filterValue): static
    {
        $this->like($this->alias . '.eway_password', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_eway.eway_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.eway_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_account_type', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.eway_account_type
     * @return static
     */
    public function groupByEwayAccountType(): static
    {
        $this->group($this->alias . '.eway_account_type');
        return $this;
    }

    /**
     * Order by setting_billing_eway.eway_account_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEwayAccountType(bool $ascending = true): static
    {
        $this->order($this->alias . '.eway_account_type', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_eway.eway_account_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEwayAccountType(string $filterValue): static
    {
        $this->like($this->alias . '.eway_account_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_eway.cc_payment_eway
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcPaymentEway(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_payment_eway', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.cc_payment_eway from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcPaymentEway(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_payment_eway', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.cc_payment_eway
     * @return static
     */
    public function groupByCcPaymentEway(): static
    {
        $this->group($this->alias . '.cc_payment_eway');
        return $this;
    }

    /**
     * Order by setting_billing_eway.cc_payment_eway
     * @param bool $ascending
     * @return static
     */
    public function orderByCcPaymentEway(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_payment_eway', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.cc_payment_eway
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentEwayGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_eway', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.cc_payment_eway
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentEwayGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_eway', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.cc_payment_eway
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentEwayLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_eway', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.cc_payment_eway
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentEwayLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_eway', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.eway_encryption_key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayEncryptionKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_encryption_key', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.eway_encryption_key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayEncryptionKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_encryption_key', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.eway_encryption_key
     * @return static
     */
    public function groupByEwayEncryptionKey(): static
    {
        $this->group($this->alias . '.eway_encryption_key');
        return $this;
    }

    /**
     * Order by setting_billing_eway.eway_encryption_key
     * @param bool $ascending
     * @return static
     */
    public function orderByEwayEncryptionKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.eway_encryption_key', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_eway.eway_encryption_key by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEwayEncryptionKey(string $filterValue): static
    {
        $this->like($this->alias . '.eway_encryption_key', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_eway.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_eway.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_eway.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_eway.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_eway.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_eway.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_eway.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_eway.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_eway.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_eway.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_eway.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_eway.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_eway.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
