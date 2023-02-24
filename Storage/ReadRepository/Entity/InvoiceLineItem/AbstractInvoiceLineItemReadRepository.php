<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceLineItem;

use InvoiceLineItem;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceLineItemReadRepository
 * @method InvoiceLineItem[] loadEntities()
 * @method InvoiceLineItem|null loadEntity()
 */
abstract class AbstractInvoiceLineItemReadRepository extends ReadRepositoryBase
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
     * Group by invoice_line_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice_line_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by invoice_line_item.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by invoice_line_item.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by invoice_line_item.label
     * @return static
     */
    public function groupByLabel(): static
    {
        $this->group($this->alias . '.label');
        return $this;
    }

    /**
     * Order by invoice_line_item.label
     * @param bool $ascending
     * @return static
     */
    public function orderByLabel(bool $ascending = true): static
    {
        $this->order($this->alias . '.label', $ascending);
        return $this;
    }

    /**
     * Filter invoice_line_item.label by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLabel(string $filterValue): static
    {
        $this->like($this->alias . '.label', "%{$filterValue}%");
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
     * Group by invoice_line_item.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by invoice_line_item.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
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
     * Group by invoice_line_item.percentage
     * @return static
     */
    public function groupByPercentage(): static
    {
        $this->group($this->alias . '.percentage');
        return $this;
    }

    /**
     * Order by invoice_line_item.percentage
     * @param bool $ascending
     * @return static
     */
    public function orderByPercentage(bool $ascending = true): static
    {
        $this->order($this->alias . '.percentage', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.percentage
     * @param bool $filterValue
     * @return static
     */
    public function filterPercentageGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.percentage
     * @param bool $filterValue
     * @return static
     */
    public function filterPercentageGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.percentage
     * @param bool $filterValue
     * @return static
     */
    public function filterPercentageLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.percentage
     * @param bool $filterValue
     * @return static
     */
    public function filterPercentageLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.percentage', $filterValue, '<=');
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
     * Group by invoice_line_item.auction_type
     * @return static
     */
    public function groupByAuctionType(): static
    {
        $this->group($this->alias . '.auction_type');
        return $this;
    }

    /**
     * Order by invoice_line_item.auction_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_type', $ascending);
        return $this;
    }

    /**
     * Filter invoice_line_item.auction_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionType(string $filterValue): static
    {
        $this->like($this->alias . '.auction_type', "%{$filterValue}%");
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
     * Group by invoice_line_item.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by invoice_line_item.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by invoice_line_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_line_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by invoice_line_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_line_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by invoice_line_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_line_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by invoice_line_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_line_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by invoice_line_item.per_lot
     * @return static
     */
    public function groupByPerLot(): static
    {
        $this->group($this->alias . '.per_lot');
        return $this;
    }

    /**
     * Order by invoice_line_item.per_lot
     * @param bool $ascending
     * @return static
     */
    public function orderByPerLot(bool $ascending = true): static
    {
        $this->order($this->alias . '.per_lot', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.per_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterPerLotGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_lot', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.per_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterPerLotGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_lot', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.per_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterPerLotLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_lot', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.per_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterPerLotLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.per_lot', $filterValue, '<=');
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
     * Group by invoice_line_item.leu_of_tax
     * @return static
     */
    public function groupByLeuOfTax(): static
    {
        $this->group($this->alias . '.leu_of_tax');
        return $this;
    }

    /**
     * Order by invoice_line_item.leu_of_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByLeuOfTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.leu_of_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.leu_of_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterLeuOfTaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.leu_of_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.leu_of_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterLeuOfTaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.leu_of_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.leu_of_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterLeuOfTaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.leu_of_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.leu_of_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterLeuOfTaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.leu_of_tax', $filterValue, '<=');
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
     * Group by invoice_line_item.break_down
     * @return static
     */
    public function groupByBreakDown(): static
    {
        $this->group($this->alias . '.break_down');
        return $this;
    }

    /**
     * Order by invoice_line_item.break_down
     * @param bool $ascending
     * @return static
     */
    public function orderByBreakDown(bool $ascending = true): static
    {
        $this->order($this->alias . '.break_down', $ascending);
        return $this;
    }

    /**
     * Filter invoice_line_item.break_down by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBreakDown(string $filterValue): static
    {
        $this->like($this->alias . '.break_down', "%{$filterValue}%");
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

    /**
     * Group by invoice_line_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_line_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
