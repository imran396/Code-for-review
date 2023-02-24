<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Invoice;

use Invoice;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceReadRepository
 * @method Invoice[] loadEntities()
 * @method Invoice|null loadEntity()
 */
abstract class AbstractInvoiceReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE;
    protected string $alias = Db::A_INVOICE;

    /**
     * Filter by invoice.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.invoice_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_no', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.invoice_no
     * @return static
     */
    public function groupByInvoiceNo(): static
    {
        $this->group($this->alias . '.invoice_no');
        return $this;
    }

    /**
     * Order by invoice.invoice_no
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_no', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.invoice_no
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceNoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_no', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.invoice_no
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceNoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_no', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.invoice_no
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceNoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_no', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.invoice_no
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceNoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_no', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by invoice.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.invoice_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_status_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.invoice_status_id
     * @return static
     */
    public function groupByInvoiceStatusId(): static
    {
        $this->group($this->alias . '.invoice_status_id');
        return $this;
    }

    /**
     * Order by invoice.invoice_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.invoice_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.invoice_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.invoice_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.invoice_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_status_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bidder_id
     * @return static
     */
    public function groupByBidderId(): static
    {
        $this->group($this->alias . '.bidder_id');
        return $this;
    }

    /**
     * Order by invoice.bidder_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBidderId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidder_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterBidderIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidder_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterBidderIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidder_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterBidderIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidder_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterBidderIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidder_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.consignor_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.consignor_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.consignor_id
     * @return static
     */
    public function groupByConsignorId(): static
    {
        $this->group($this->alias . '.consignor_id');
        return $this;
    }

    /**
     * Order by invoice.consignor_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by invoice.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.shipping
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterShipping(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipShipping(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.shipping
     * @return static
     */
    public function groupByShipping(): static
    {
        $this->group($this->alias . '.shipping');
        return $this;
    }

    /**
     * Order by invoice.shipping
     * @param bool $ascending
     * @return static
     */
    public function orderByShipping(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.shipping
     * @param float $filterValue
     * @return static
     */
    public function filterShippingGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.shipping
     * @param float $filterValue
     * @return static
     */
    public function filterShippingGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.shipping
     * @param float $filterValue
     * @return static
     */
    public function filterShippingLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.shipping
     * @param float $filterValue
     * @return static
     */
    public function filterShippingLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.shipping_note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShippingNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping_note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShippingNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_note', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.shipping_note
     * @return static
     */
    public function groupByShippingNote(): static
    {
        $this->group($this->alias . '.shipping_note');
        return $this;
    }

    /**
     * Order by invoice.shipping_note
     * @param bool $ascending
     * @return static
     */
    public function orderByShippingNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping_note', $ascending);
        return $this;
    }

    /**
     * Filter invoice.shipping_note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeShippingNote(string $filterValue): static
    {
        $this->like($this->alias . '.shipping_note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by invoice.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter invoice.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.exclude_in_threshold
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExcludeInThreshold(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.exclude_in_threshold', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.exclude_in_threshold from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExcludeInThreshold(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.exclude_in_threshold', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.exclude_in_threshold
     * @return static
     */
    public function groupByExcludeInThreshold(): static
    {
        $this->group($this->alias . '.exclude_in_threshold');
        return $this;
    }

    /**
     * Order by invoice.exclude_in_threshold
     * @param bool $ascending
     * @return static
     */
    public function orderByExcludeInThreshold(bool $ascending = true): static
    {
        $this->order($this->alias . '.exclude_in_threshold', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.exclude_in_threshold
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeInThresholdGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_in_threshold', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.exclude_in_threshold
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeInThresholdGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_in_threshold', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.exclude_in_threshold
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeInThresholdLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_in_threshold', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.exclude_in_threshold
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeInThresholdLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_in_threshold', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.invoice_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterInvoiceDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_date', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipInvoiceDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_date', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.invoice_date
     * @return static
     */
    public function groupByInvoiceDate(): static
    {
        $this->group($this->alias . '.invoice_date');
        return $this;
    }

    /**
     * Order by invoice.invoice_date
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.invoice_date
     * @param string $filterValue
     * @return static
     */
    public function filterInvoiceDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.invoice_date
     * @param string $filterValue
     * @return static
     */
    public function filterInvoiceDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.invoice_date
     * @param string $filterValue
     * @return static
     */
    public function filterInvoiceDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.invoice_date
     * @param string $filterValue
     * @return static
     */
    public function filterInvoiceDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.cash_discount
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCashDiscount(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cash_discount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.cash_discount from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCashDiscount(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cash_discount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.cash_discount
     * @return static
     */
    public function groupByCashDiscount(): static
    {
        $this->group($this->alias . '.cash_discount');
        return $this;
    }

    /**
     * Order by invoice.cash_discount
     * @param bool $ascending
     * @return static
     */
    public function orderByCashDiscount(bool $ascending = true): static
    {
        $this->order($this->alias . '.cash_discount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.cash_discount
     * @param bool $filterValue
     * @return static
     */
    public function filterCashDiscountGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.cash_discount
     * @param bool $filterValue
     * @return static
     */
    public function filterCashDiscountGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.cash_discount
     * @param bool $filterValue
     * @return static
     */
    public function filterCashDiscountLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.cash_discount
     * @param bool $filterValue
     * @return static
     */
    public function filterCashDiscountLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.first_sent_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterFirstSentOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.first_sent_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.first_sent_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipFirstSentOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.first_sent_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.first_sent_on
     * @return static
     */
    public function groupByFirstSentOn(): static
    {
        $this->group($this->alias . '.first_sent_on');
        return $this;
    }

    /**
     * Order by invoice.first_sent_on
     * @param bool $ascending
     * @return static
     */
    public function orderByFirstSentOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.first_sent_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.first_sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterFirstSentOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.first_sent_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.first_sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterFirstSentOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.first_sent_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.first_sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterFirstSentOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.first_sent_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.first_sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterFirstSentOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.first_sent_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.sent_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterSentOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sent_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.sent_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipSentOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sent_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.sent_on
     * @return static
     */
    public function groupBySentOn(): static
    {
        $this->group($this->alias . '.sent_on');
        return $this;
    }

    /**
     * Order by invoice.sent_on
     * @param bool $ascending
     * @return static
     */
    public function orderBySentOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.sent_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterSentOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sent_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterSentOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sent_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterSentOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sent_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.sent_on
     * @param string $filterValue
     * @return static
     */
    public function filterSentOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sent_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bidder_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidderNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_number', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bidder_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidderNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_number', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bidder_number
     * @return static
     */
    public function groupByBidderNumber(): static
    {
        $this->group($this->alias . '.bidder_number');
        return $this;
    }

    /**
     * Order by invoice.bidder_number
     * @param bool $ascending
     * @return static
     */
    public function orderByBidderNumber(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidder_number', $ascending);
        return $this;
    }

    /**
     * Filter invoice.bidder_number by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidderNumber(string $filterValue): static
    {
        $this->like($this->alias . '.bidder_number', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.bid_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBidTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bid_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBidTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bid_total
     * @return static
     */
    public function groupByBidTotal(): static
    {
        $this->group($this->alias . '.bid_total');
        return $this;
    }

    /**
     * Order by invoice.bid_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBidTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bid_total
     * @param float $filterValue
     * @return static
     */
    public function filterBidTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bid_total
     * @param float $filterValue
     * @return static
     */
    public function filterBidTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bid_total
     * @param float $filterValue
     * @return static
     */
    public function filterBidTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bid_total
     * @param float $filterValue
     * @return static
     */
    public function filterBidTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.buyers_premium
     * @return static
     */
    public function groupByBuyersPremium(): static
    {
        $this->group($this->alias . '.buyers_premium');
        return $this;
    }

    /**
     * Order by invoice.buyers_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax
     * @return static
     */
    public function groupByTax(): static
    {
        $this->group($this->alias . '.tax');
        return $this;
    }

    /**
     * Order by invoice.tax
     * @param bool $ascending
     * @return static
     */
    public function orderByTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.tax
     * @param float $filterValue
     * @return static
     */
    public function filterTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.tax
     * @param float $filterValue
     * @return static
     */
    public function filterTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.tax
     * @param float $filterValue
     * @return static
     */
    public function filterTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.tax
     * @param float $filterValue
     * @return static
     */
    public function filterTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax_service
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxService(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_service', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_service from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxService(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_service', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax_service
     * @return static
     */
    public function groupByTaxService(): static
    {
        $this->group($this->alias . '.tax_service');
        return $this;
    }

    /**
     * Order by invoice.tax_service
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxService(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_service', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.tax_service
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServiceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_service', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.tax_service
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServiceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_service', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.tax_service
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServiceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_service', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.tax_service
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServiceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_service', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.extra_charges
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExtraCharges(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extra_charges', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.extra_charges from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExtraCharges(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extra_charges', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.extra_charges
     * @return static
     */
    public function groupByExtraCharges(): static
    {
        $this->group($this->alias . '.extra_charges');
        return $this;
    }

    /**
     * Order by invoice.extra_charges
     * @param bool $ascending
     * @return static
     */
    public function orderByExtraCharges(bool $ascending = true): static
    {
        $this->order($this->alias . '.extra_charges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.shipping_fees
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterShippingFees(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_fees', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping_fees from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipShippingFees(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_fees', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.shipping_fees
     * @return static
     */
    public function groupByShippingFees(): static
    {
        $this->group($this->alias . '.shipping_fees');
        return $this;
    }

    /**
     * Order by invoice.shipping_fees
     * @param bool $ascending
     * @return static
     */
    public function orderByShippingFees(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping_fees', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.shipping_fees
     * @param float $filterValue
     * @return static
     */
    public function filterShippingFeesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_fees', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.shipping_fees
     * @param float $filterValue
     * @return static
     */
    public function filterShippingFeesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_fees', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.shipping_fees
     * @param float $filterValue
     * @return static
     */
    public function filterShippingFeesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_fees', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.shipping_fees
     * @param float $filterValue
     * @return static
     */
    public function filterShippingFeesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_fees', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.total_payment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPayment(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_payment', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.total_payment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPayment(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_payment', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.total_payment
     * @return static
     */
    public function groupByTotalPayment(): static
    {
        $this->group($this->alias . '.total_payment');
        return $this;
    }

    /**
     * Order by invoice.total_payment
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalPayment(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_payment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.total_payment
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaymentGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_payment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.total_payment
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaymentGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_payment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.total_payment
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaymentLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_payment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.total_payment
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaymentLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_payment', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.currency_sign
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCurrencySign(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.currency_sign', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.currency_sign from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCurrencySign(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.currency_sign', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.currency_sign
     * @return static
     */
    public function groupByCurrencySign(): static
    {
        $this->group($this->alias . '.currency_sign');
        return $this;
    }

    /**
     * Order by invoice.currency_sign
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrencySign(bool $ascending = true): static
    {
        $this->order($this->alias . '.currency_sign', $ascending);
        return $this;
    }

    /**
     * Filter invoice.currency_sign by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCurrencySign(string $filterValue): static
    {
        $this->like($this->alias . '.currency_sign', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.ex_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ex_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.ex_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ex_rate', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.ex_rate
     * @return static
     */
    public function groupByExRate(): static
    {
        $this->group($this->alias . '.ex_rate');
        return $this;
    }

    /**
     * Order by invoice.ex_rate
     * @param bool $ascending
     * @return static
     */
    public function orderByExRate(bool $ascending = true): static
    {
        $this->order($this->alias . '.ex_rate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.ex_rate
     * @param float $filterValue
     * @return static
     */
    public function filterExRateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ex_rate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.ex_rate
     * @param float $filterValue
     * @return static
     */
    public function filterExRateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ex_rate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.ex_rate
     * @param float $filterValue
     * @return static
     */
    public function filterExRateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ex_rate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.ex_rate
     * @param float $filterValue
     * @return static
     */
    public function filterExRateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.ex_rate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.internal_note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterInternalNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.internal_note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.internal_note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipInternalNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.internal_note', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.internal_note
     * @return static
     */
    public function groupByInternalNote(): static
    {
        $this->group($this->alias . '.internal_note');
        return $this;
    }

    /**
     * Order by invoice.internal_note
     * @param bool $ascending
     * @return static
     */
    public function orderByInternalNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.internal_note', $ascending);
        return $this;
    }

    /**
     * Filter invoice.internal_note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeInternalNote(string $filterValue): static
    {
        $this->like($this->alias . '.internal_note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.internal_note_modified
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterInternalNoteModified(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.internal_note_modified', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.internal_note_modified from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipInternalNoteModified(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.internal_note_modified', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.internal_note_modified
     * @return static
     */
    public function groupByInternalNoteModified(): static
    {
        $this->group($this->alias . '.internal_note_modified');
        return $this;
    }

    /**
     * Order by invoice.internal_note_modified
     * @param bool $ascending
     * @return static
     */
    public function orderByInternalNoteModified(bool $ascending = true): static
    {
        $this->order($this->alias . '.internal_note_modified', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.internal_note_modified
     * @param string $filterValue
     * @return static
     */
    public function filterInternalNoteModifiedGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.internal_note_modified', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.internal_note_modified
     * @param string $filterValue
     * @return static
     */
    public function filterInternalNoteModifiedGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.internal_note_modified', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.internal_note_modified
     * @param string $filterValue
     * @return static
     */
    public function filterInternalNoteModifiedLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.internal_note_modified', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.internal_note_modified
     * @param string $filterValue
     * @return static
     */
    public function filterInternalNoteModifiedLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.internal_note_modified', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax_charges_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxChargesRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_charges_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_charges_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxChargesRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_charges_rate', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax_charges_rate
     * @return static
     */
    public function groupByTaxChargesRate(): static
    {
        $this->group($this->alias . '.tax_charges_rate');
        return $this;
    }

    /**
     * Order by invoice.tax_charges_rate
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxChargesRate(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_charges_rate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.tax_charges_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxChargesRateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_charges_rate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.tax_charges_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxChargesRateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_charges_rate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.tax_charges_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxChargesRateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_charges_rate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.tax_charges_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxChargesRateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_charges_rate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax_fees_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxFeesRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_fees_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_fees_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxFeesRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_fees_rate', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax_fees_rate
     * @return static
     */
    public function groupByTaxFeesRate(): static
    {
        $this->group($this->alias . '.tax_fees_rate');
        return $this;
    }

    /**
     * Order by invoice.tax_fees_rate
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxFeesRate(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_fees_rate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.tax_fees_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxFeesRateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_fees_rate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.tax_fees_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxFeesRateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_fees_rate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.tax_fees_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxFeesRateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_fees_rate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.tax_fees_rate
     * @param float $filterValue
     * @return static
     */
    public function filterTaxFeesRateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_fees_rate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax_designation
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxDesignation(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_designation', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_designation from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxDesignation(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_designation', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax_designation
     * @return static
     */
    public function groupByTaxDesignation(): static
    {
        $this->group($this->alias . '.tax_designation');
        return $this;
    }

    /**
     * Order by invoice.tax_designation
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxDesignation(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_designation', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.tax_designation
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDesignationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_designation', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.tax_designation
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDesignationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_designation', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.tax_designation
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDesignationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_designation', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.tax_designation
     * @param int $filterValue
     * @return static
     */
    public function filterTaxDesignationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_designation', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.tax_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_country', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_country', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.tax_country
     * @return static
     */
    public function groupByTaxCountry(): static
    {
        $this->group($this->alias . '.tax_country');
        return $this;
    }

    /**
     * Order by invoice.tax_country
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_country', $ascending);
        return $this;
    }

    /**
     * Filter invoice.tax_country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTaxCountry(string $filterValue): static
    {
        $this->like($this->alias . '.tax_country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice.hp_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHpTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHpTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.hp_tax_total
     * @return static
     */
    public function groupByHpTaxTotal(): static
    {
        $this->group($this->alias . '.hp_tax_total');
        return $this;
    }

    /**
     * Order by invoice.hp_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.hp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.hp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.hp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.hp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.hp_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.hp_country_tax_total
     * @return static
     */
    public function groupByHpCountryTaxTotal(): static
    {
        $this->group($this->alias . '.hp_country_tax_total');
        return $this;
    }

    /**
     * Order by invoice.hp_country_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCountryTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_country_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.hp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.hp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.hp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.hp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.hp_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.hp_state_tax_total
     * @return static
     */
    public function groupByHpStateTaxTotal(): static
    {
        $this->group($this->alias . '.hp_state_tax_total');
        return $this;
    }

    /**
     * Order by invoice.hp_state_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpStateTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_state_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.hp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.hp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.hp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.hp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.hp_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.hp_county_tax_total
     * @return static
     */
    public function groupByHpCountyTaxTotal(): static
    {
        $this->group($this->alias . '.hp_county_tax_total');
        return $this;
    }

    /**
     * Order by invoice.hp_county_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCountyTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_county_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.hp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.hp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.hp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.hp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.hp_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.hp_city_tax_total
     * @return static
     */
    public function groupByHpCityTaxTotal(): static
    {
        $this->group($this->alias . '.hp_city_tax_total');
        return $this;
    }

    /**
     * Order by invoice.hp_city_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCityTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_city_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.hp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.hp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.hp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.hp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bp_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBpTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBpTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bp_tax_total
     * @return static
     */
    public function groupByBpTaxTotal(): static
    {
        $this->group($this->alias . '.bp_tax_total');
        return $this;
    }

    /**
     * Order by invoice.bp_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bp_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bp_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bp_country_tax_total
     * @return static
     */
    public function groupByBpCountryTaxTotal(): static
    {
        $this->group($this->alias . '.bp_country_tax_total');
        return $this;
    }

    /**
     * Order by invoice.bp_country_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCountryTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_country_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bp_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bp_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bp_state_tax_total
     * @return static
     */
    public function groupByBpStateTaxTotal(): static
    {
        $this->group($this->alias . '.bp_state_tax_total');
        return $this;
    }

    /**
     * Order by invoice.bp_state_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBpStateTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_state_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bp_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bp_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bp_county_tax_total
     * @return static
     */
    public function groupByBpCountyTaxTotal(): static
    {
        $this->group($this->alias . '.bp_county_tax_total');
        return $this;
    }

    /**
     * Order by invoice.bp_county_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCountyTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_county_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bp_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.bp_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.bp_city_tax_total
     * @return static
     */
    public function groupByBpCityTaxTotal(): static
    {
        $this->group($this->alias . '.bp_city_tax_total');
        return $this;
    }

    /**
     * Order by invoice.bp_city_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCityTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_city_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.bp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.bp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.bp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.bp_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.services_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterServicesTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.services_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipServicesTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.services_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.services_tax_total
     * @return static
     */
    public function groupByServicesTaxTotal(): static
    {
        $this->group($this->alias . '.services_tax_total');
        return $this;
    }

    /**
     * Order by invoice.services_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.services_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.services_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.services_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.services_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.services_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.services_country_tax_total
     * @return static
     */
    public function groupByServicesCountryTaxTotal(): static
    {
        $this->group($this->alias . '.services_country_tax_total');
        return $this;
    }

    /**
     * Order by invoice.services_country_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesCountryTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_country_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.services_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_country_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.services_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_country_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.services_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_country_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.services_country_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_country_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.services_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.services_state_tax_total
     * @return static
     */
    public function groupByServicesStateTaxTotal(): static
    {
        $this->group($this->alias . '.services_state_tax_total');
        return $this;
    }

    /**
     * Order by invoice.services_state_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesStateTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_state_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.services_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_state_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.services_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_state_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.services_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_state_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.services_state_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_state_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.services_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.services_county_tax_total
     * @return static
     */
    public function groupByServicesCountyTaxTotal(): static
    {
        $this->group($this->alias . '.services_county_tax_total');
        return $this;
    }

    /**
     * Order by invoice.services_county_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesCountyTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_county_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.services_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_county_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.services_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_county_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.services_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_county_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.services_county_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_county_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.services_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.services_city_tax_total
     * @return static
     */
    public function groupByServicesCityTaxTotal(): static
    {
        $this->group($this->alias . '.services_city_tax_total');
        return $this;
    }

    /**
     * Order by invoice.services_city_tax_total
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesCityTaxTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_city_tax_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.services_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_city_tax_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.services_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_city_tax_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.services_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_city_tax_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.services_city_tax_total
     * @param float $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_city_tax_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
