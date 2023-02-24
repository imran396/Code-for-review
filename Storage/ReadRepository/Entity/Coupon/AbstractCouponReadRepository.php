<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Coupon;

use Coupon;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractCouponReadRepository
 * @method Coupon[] loadEntities()
 * @method Coupon|null loadEntity()
 */
abstract class AbstractCouponReadRepository extends ReadRepositoryBase
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
     * Group by coupon.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by coupon.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by coupon.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by coupon.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by coupon.title
     * @return static
     */
    public function groupByTitle(): static
    {
        $this->group($this->alias . '.title');
        return $this;
    }

    /**
     * Order by coupon.title
     * @param bool $ascending
     * @return static
     */
    public function orderByTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.title', $ascending);
        return $this;
    }

    /**
     * Filter coupon.title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTitle(string $filterValue): static
    {
        $this->like($this->alias . '.title', "%{$filterValue}%");
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
     * Group by coupon.code
     * @return static
     */
    public function groupByCode(): static
    {
        $this->group($this->alias . '.code');
        return $this;
    }

    /**
     * Order by coupon.code
     * @param bool $ascending
     * @return static
     */
    public function orderByCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.code', $ascending);
        return $this;
    }

    /**
     * Filter coupon.code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCode(string $filterValue): static
    {
        $this->like($this->alias . '.code', "%{$filterValue}%");
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
     * Group by coupon.min_purchase_amt
     * @return static
     */
    public function groupByMinPurchaseAmt(): static
    {
        $this->group($this->alias . '.min_purchase_amt');
        return $this;
    }

    /**
     * Order by coupon.min_purchase_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByMinPurchaseAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.min_purchase_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.min_purchase_amt
     * @param float $filterValue
     * @return static
     */
    public function filterMinPurchaseAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.min_purchase_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.min_purchase_amt
     * @param float $filterValue
     * @return static
     */
    public function filterMinPurchaseAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.min_purchase_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.min_purchase_amt
     * @param float $filterValue
     * @return static
     */
    public function filterMinPurchaseAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.min_purchase_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.min_purchase_amt
     * @param float $filterValue
     * @return static
     */
    public function filterMinPurchaseAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.min_purchase_amt', $filterValue, '<=');
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
     * Group by coupon.per_user
     * @return static
     */
    public function groupByPerUser(): static
    {
        $this->group($this->alias . '.per_user');
        return $this;
    }

    /**
     * Order by coupon.per_user
     * @param bool $ascending
     * @return static
     */
    public function orderByPerUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.per_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.per_user
     * @param int $filterValue
     * @return static
     */
    public function filterPerUserGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.per_user
     * @param int $filterValue
     * @return static
     */
    public function filterPerUserGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.per_user
     * @param int $filterValue
     * @return static
     */
    public function filterPerUserLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.per_user
     * @param int $filterValue
     * @return static
     */
    public function filterPerUserLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_user', $filterValue, '<=');
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
     * Group by coupon.coupon_type
     * @return static
     */
    public function groupByCouponType(): static
    {
        $this->group($this->alias . '.coupon_type');
        return $this;
    }

    /**
     * Order by coupon.coupon_type
     * @param bool $ascending
     * @return static
     */
    public function orderByCouponType(bool $ascending = true): static
    {
        $this->order($this->alias . '.coupon_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.coupon_type
     * @param int $filterValue
     * @return static
     */
    public function filterCouponTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.coupon_type
     * @param int $filterValue
     * @return static
     */
    public function filterCouponTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.coupon_type
     * @param int $filterValue
     * @return static
     */
    public function filterCouponTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.coupon_type
     * @param int $filterValue
     * @return static
     */
    public function filterCouponTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_type', $filterValue, '<=');
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
     * Group by coupon.start_date
     * @return static
     */
    public function groupByStartDate(): static
    {
        $this->group($this->alias . '.start_date');
        return $this;
    }

    /**
     * Order by coupon.start_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<=');
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
     * Group by coupon.timezone_id
     * @return static
     */
    public function groupByTimezoneId(): static
    {
        $this->group($this->alias . '.timezone_id');
        return $this;
    }

    /**
     * Order by coupon.timezone_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneId(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<=');
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
     * Group by coupon.end_date
     * @return static
     */
    public function groupByEndDate(): static
    {
        $this->group($this->alias . '.end_date');
        return $this;
    }

    /**
     * Order by coupon.end_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<=');
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
     * Group by coupon.waive_additional_charges
     * @return static
     */
    public function groupByWaiveAdditionalCharges(): static
    {
        $this->group($this->alias . '.waive_additional_charges');
        return $this;
    }

    /**
     * Order by coupon.waive_additional_charges
     * @param bool $ascending
     * @return static
     */
    public function orderByWaiveAdditionalCharges(bool $ascending = true): static
    {
        $this->order($this->alias . '.waive_additional_charges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.waive_additional_charges
     * @param bool $filterValue
     * @return static
     */
    public function filterWaiveAdditionalChargesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.waive_additional_charges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.waive_additional_charges
     * @param bool $filterValue
     * @return static
     */
    public function filterWaiveAdditionalChargesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.waive_additional_charges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.waive_additional_charges
     * @param bool $filterValue
     * @return static
     */
    public function filterWaiveAdditionalChargesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.waive_additional_charges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.waive_additional_charges
     * @param bool $filterValue
     * @return static
     */
    public function filterWaiveAdditionalChargesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.waive_additional_charges', $filterValue, '<=');
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
     * Group by coupon.coupon_status_id
     * @return static
     */
    public function groupByCouponStatusId(): static
    {
        $this->group($this->alias . '.coupon_status_id');
        return $this;
    }

    /**
     * Order by coupon.coupon_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCouponStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.coupon_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.coupon_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.coupon_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.coupon_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.coupon_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_status_id', $filterValue, '<=');
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
     * Group by coupon.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by coupon.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by coupon.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by coupon.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by coupon.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by coupon.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by coupon.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by coupon.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by coupon.fixed_amount_off
     * @return static
     */
    public function groupByFixedAmountOff(): static
    {
        $this->group($this->alias . '.fixed_amount_off');
        return $this;
    }

    /**
     * Order by coupon.fixed_amount_off
     * @param bool $ascending
     * @return static
     */
    public function orderByFixedAmountOff(bool $ascending = true): static
    {
        $this->order($this->alias . '.fixed_amount_off', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.fixed_amount_off
     * @param float $filterValue
     * @return static
     */
    public function filterFixedAmountOffGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed_amount_off', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.fixed_amount_off
     * @param float $filterValue
     * @return static
     */
    public function filterFixedAmountOffGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed_amount_off', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.fixed_amount_off
     * @param float $filterValue
     * @return static
     */
    public function filterFixedAmountOffLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed_amount_off', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.fixed_amount_off
     * @param float $filterValue
     * @return static
     */
    public function filterFixedAmountOffLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed_amount_off', $filterValue, '<=');
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
     * Group by coupon.percentage_off
     * @return static
     */
    public function groupByPercentageOff(): static
    {
        $this->group($this->alias . '.percentage_off');
        return $this;
    }

    /**
     * Order by coupon.percentage_off
     * @param bool $ascending
     * @return static
     */
    public function orderByPercentageOff(bool $ascending = true): static
    {
        $this->order($this->alias . '.percentage_off', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.percentage_off
     * @param float $filterValue
     * @return static
     */
    public function filterPercentageOffGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage_off', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.percentage_off
     * @param float $filterValue
     * @return static
     */
    public function filterPercentageOffGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage_off', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.percentage_off
     * @param float $filterValue
     * @return static
     */
    public function filterPercentageOffLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage_off', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.percentage_off
     * @param float $filterValue
     * @return static
     */
    public function filterPercentageOffLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage_off', $filterValue, '<=');
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

    /**
     * Group by coupon.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by coupon.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than coupon.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than coupon.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than coupon.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than coupon.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
