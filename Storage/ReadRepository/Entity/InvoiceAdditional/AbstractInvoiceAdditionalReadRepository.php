<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceAdditional;

use InvoiceAdditional;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceAdditionalReadRepository
 * @method InvoiceAdditional[] loadEntities()
 * @method InvoiceAdditional|null loadEntity()
 */
abstract class AbstractInvoiceAdditionalReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_ADDITIONAL;
    protected string $alias = Db::A_INVOICE_ADDITIONAL;

    /**
     * Filter by invoice_additional.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice_additional.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.invoice_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.invoice_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by invoice_additional.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by invoice_additional.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_additional.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_additional.amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by invoice_additional.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.tax_amount
     * @return static
     */
    public function groupByTaxAmount(): static
    {
        $this->group($this->alias . '.tax_amount');
        return $this;
    }

    /**
     * Order by invoice_additional.tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.country_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterCountryTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.country_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipCountryTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.country_tax_amount
     * @return static
     */
    public function groupByCountryTaxAmount(): static
    {
        $this->group($this->alias . '.country_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_additional.country_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByCountryTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.country_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountryTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.country_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountryTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.country_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountryTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.country_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountryTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.country_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.state_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterStateTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.state_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipStateTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.state_tax_amount
     * @return static
     */
    public function groupByStateTaxAmount(): static
    {
        $this->group($this->alias . '.state_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_additional.state_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByStateTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.state_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterStateTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.state_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterStateTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.state_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterStateTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.state_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterStateTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.state_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.county_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterCountyTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.county_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipCountyTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.county_tax_amount
     * @return static
     */
    public function groupByCountyTaxAmount(): static
    {
        $this->group($this->alias . '.county_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_additional.county_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByCountyTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.county_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountyTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.county_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountyTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.county_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountyTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.county_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCountyTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.county_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.city_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterCityTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.city_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipCityTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.city_tax_amount
     * @return static
     */
    public function groupByCityTaxAmount(): static
    {
        $this->group($this->alias . '.city_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_additional.city_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByCityTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.city_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCityTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.city_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCityTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.city_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCityTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.city_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterCityTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.city_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.coupon_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCouponId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.coupon_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.coupon_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCouponId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.coupon_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.coupon_id
     * @return static
     */
    public function groupByCouponId(): static
    {
        $this->group($this->alias . '.coupon_id');
        return $this;
    }

    /**
     * Order by invoice_additional.coupon_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCouponId(bool $ascending = true): static
    {
        $this->order($this->alias . '.coupon_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.coupon_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.coupon_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.coupon_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.coupon_id
     * @param int $filterValue
     * @return static
     */
    public function filterCouponIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.coupon_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.coupon_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCouponCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.coupon_code', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.coupon_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCouponCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.coupon_code', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.coupon_code
     * @return static
     */
    public function groupByCouponCode(): static
    {
        $this->group($this->alias . '.coupon_code');
        return $this;
    }

    /**
     * Order by invoice_additional.coupon_code
     * @param bool $ascending
     * @return static
     */
    public function orderByCouponCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.coupon_code', $ascending);
        return $this;
    }

    /**
     * Filter invoice_additional.coupon_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCouponCode(string $filterValue): static
    {
        $this->like($this->alias . '.coupon_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_additional.payment_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPaymentId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.payment_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPaymentId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.payment_id
     * @return static
     */
    public function groupByPaymentId(): static
    {
        $this->group($this->alias . '.payment_id');
        return $this;
    }

    /**
     * Order by invoice_additional.payment_id
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentId(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.type
     * @return static
     */
    public function groupByType(): static
    {
        $this->group($this->alias . '.type');
        return $this;
    }

    /**
     * Order by invoice_additional.type
     * @param bool $ascending
     * @return static
     */
    public function orderByType(bool $ascending = true): static
    {
        $this->order($this->alias . '.type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by invoice_additional.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter invoice_additional.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_additional.tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.tax_schema_id
     * @return static
     */
    public function groupByTaxSchemaId(): static
    {
        $this->group($this->alias . '.tax_schema_id');
        return $this;
    }

    /**
     * Order by invoice_additional.tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by invoice_additional.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_additional.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_additional.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_additional.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_additional.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_additional.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_additional.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_additional.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_additional.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_additional.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_additional.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_additional.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_additional.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
