<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingEway;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingBillingEwayDeleteRepository extends DeleteRepositoryBase
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
}
