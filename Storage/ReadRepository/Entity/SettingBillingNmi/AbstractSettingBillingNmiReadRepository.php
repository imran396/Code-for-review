<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingNmi;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingBillingNmi;

/**
 * Abstract class AbstractSettingBillingNmiReadRepository
 * @method SettingBillingNmi[] loadEntities()
 * @method SettingBillingNmi|null loadEntity()
 */
abstract class AbstractSettingBillingNmiReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_BILLING_NMI;
    protected string $alias = Db::A_SETTING_BILLING_NMI;

    /**
     * Filter by setting_billing_nmi.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.nmi_username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNmiUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_username', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.nmi_username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNmiUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_username', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.nmi_username
     * @return static
     */
    public function groupByNmiUsername(): static
    {
        $this->group($this->alias . '.nmi_username');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.nmi_username
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_username', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_nmi.nmi_username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNmiUsername(string $filterValue): static
    {
        $this->like($this->alias . '.nmi_username', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.nmi_password
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNmiPassword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_password', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.nmi_password from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNmiPassword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_password', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.nmi_password
     * @return static
     */
    public function groupByNmiPassword(): static
    {
        $this->group($this->alias . '.nmi_password');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.nmi_password
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiPassword(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_password', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_nmi.nmi_password by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNmiPassword(string $filterValue): static
    {
        $this->like($this->alias . '.nmi_password', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.nmi_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNmiMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.nmi_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNmiMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_mode', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.nmi_mode
     * @return static
     */
    public function groupByNmiMode(): static
    {
        $this->group($this->alias . '.nmi_mode');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.nmi_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_billing_nmi.nmi_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNmiMode(string $filterValue): static
    {
        $this->like($this->alias . '.nmi_mode', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.cc_payment_nmi
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcPaymentNmi(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_payment_nmi', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.cc_payment_nmi from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcPaymentNmi(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_payment_nmi', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.cc_payment_nmi
     * @return static
     */
    public function groupByCcPaymentNmi(): static
    {
        $this->group($this->alias . '.cc_payment_nmi');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.cc_payment_nmi
     * @param bool $ascending
     * @return static
     */
    public function orderByCcPaymentNmi(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_payment_nmi', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.cc_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentNmiGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_nmi', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.cc_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentNmiGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_nmi', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.cc_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentNmiLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_nmi', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.cc_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterCcPaymentNmiLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_payment_nmi', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.ach_payment_nmi
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAchPaymentNmi(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ach_payment_nmi', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.ach_payment_nmi from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAchPaymentNmi(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ach_payment_nmi', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.ach_payment_nmi
     * @return static
     */
    public function groupByAchPaymentNmi(): static
    {
        $this->group($this->alias . '.ach_payment_nmi');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.ach_payment_nmi
     * @param bool $ascending
     * @return static
     */
    public function orderByAchPaymentNmi(bool $ascending = true): static
    {
        $this->order($this->alias . '.ach_payment_nmi', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.ach_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentNmiGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_nmi', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.ach_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentNmiGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_nmi', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.ach_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentNmiLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_nmi', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.ach_payment_nmi
     * @param bool $filterValue
     * @return static
     */
    public function filterAchPaymentNmiLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ach_payment_nmi', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.nmi_vault
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNmiVault(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_vault', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.nmi_vault from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNmiVault(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_vault', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.nmi_vault
     * @return static
     */
    public function groupByNmiVault(): static
    {
        $this->group($this->alias . '.nmi_vault');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.nmi_vault
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiVault(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_vault', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.nmi_vault
     * @param bool $filterValue
     * @return static
     */
    public function filterNmiVaultGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.nmi_vault
     * @param bool $filterValue
     * @return static
     */
    public function filterNmiVaultGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.nmi_vault
     * @param bool $filterValue
     * @return static
     */
    public function filterNmiVaultLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.nmi_vault
     * @param bool $filterValue
     * @return static
     */
    public function filterNmiVaultLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.nmi_vault_option
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNmiVaultOption(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_vault_option', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.nmi_vault_option from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNmiVaultOption(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_vault_option', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.nmi_vault_option
     * @return static
     */
    public function groupByNmiVaultOption(): static
    {
        $this->group($this->alias . '.nmi_vault_option');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.nmi_vault_option
     * @param bool $ascending
     * @return static
     */
    public function orderByNmiVaultOption(bool $ascending = true): static
    {
        $this->order($this->alias . '.nmi_vault_option', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.nmi_vault_option
     * @param int $filterValue
     * @return static
     */
    public function filterNmiVaultOptionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault_option', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.nmi_vault_option
     * @param int $filterValue
     * @return static
     */
    public function filterNmiVaultOptionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault_option', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.nmi_vault_option
     * @param int $filterValue
     * @return static
     */
    public function filterNmiVaultOptionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault_option', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.nmi_vault_option
     * @param int $filterValue
     * @return static
     */
    public function filterNmiVaultOptionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.nmi_vault_option', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_billing_nmi.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_billing_nmi.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_billing_nmi.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_billing_nmi.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_billing_nmi.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_billing_nmi.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_billing_nmi.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_billing_nmi.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
