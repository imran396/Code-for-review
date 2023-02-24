<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceAdditional;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceAdditionalDeleteRepository extends DeleteRepositoryBase
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
}
