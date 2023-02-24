<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingNmi;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingNmiDeleteRepository extends DeleteRepositoryBase
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
}
