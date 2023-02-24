<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceLineItem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceLineItemDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_LINE_ITEM;
    protected string $alias = Db::A_INVOICE_LINE_ITEM;

    /**
     * Filter by invoice_line_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.label
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLabel(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.label', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.label from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLabel(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.label', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.percentage
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPercentage(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.percentage', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.percentage from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPercentage(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.percentage', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.auction_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.auction_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_type', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.per_lot
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPerLot(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.per_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.per_lot from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPerLot(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.per_lot', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.leu_of_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLeuOfTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.leu_of_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.leu_of_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLeuOfTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.leu_of_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.break_down
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBreakDown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.break_down', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.break_down from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBreakDown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.break_down', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
