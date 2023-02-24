<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Coupon;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractCouponDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_COUPON;
    protected string $alias = Db::A_COUPON;

    /**
     * Filter by coupon.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.title', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.title', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.code', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.code', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.min_purchase_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMinPurchaseAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.min_purchase_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.min_purchase_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMinPurchaseAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.min_purchase_amt', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.per_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPerUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.per_user', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.per_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPerUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.per_user', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.coupon_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCouponType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.coupon_type', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.coupon_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCouponType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.coupon_type', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.start_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_date', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.start_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_date', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_date', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.waive_additional_charges
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterWaiveAdditionalCharges(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.waive_additional_charges', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.waive_additional_charges from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipWaiveAdditionalCharges(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.waive_additional_charges', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.coupon_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCouponStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.coupon_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.coupon_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCouponStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.coupon_status_id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.fixed_amount_off
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterFixedAmountOff(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fixed_amount_off', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.fixed_amount_off from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipFixedAmountOff(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fixed_amount_off', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.percentage_off
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterPercentageOff(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.percentage_off', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.percentage_off from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipPercentageOff(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.percentage_off', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
