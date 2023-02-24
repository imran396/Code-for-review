<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItem;

use LotItem;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLotItemReadRepository
 * @method LotItem[] loadEntities()
 * @method LotItem|null loadEntity()
 */
abstract class AbstractLotItemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_LOT_ITEM;
    protected string $alias = Db::A_LOT_ITEM;

    /**
     * Filter by lot_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by lot_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by lot_item.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.consignor_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.consignor_id
     * @return static
     */
    public function groupByConsignorId(): static
    {
        $this->group($this->alias . '.consignor_id');
        return $this;
    }

    /**
     * Order by lot_item.consignor_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.invoice_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.invoice_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by lot_item.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.item_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterItemNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.item_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipItemNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.item_num
     * @return static
     */
    public function groupByItemNum(): static
    {
        $this->group($this->alias . '.item_num');
        return $this;
    }

    /**
     * Order by lot_item.item_num
     * @param bool $ascending
     * @return static
     */
    public function orderByItemNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.item_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.item_num
     * @param int $filterValue
     * @return static
     */
    public function filterItemNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.item_num
     * @param int $filterValue
     * @return static
     */
    public function filterItemNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.item_num
     * @param int $filterValue
     * @return static
     */
    public function filterItemNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.item_num
     * @param int $filterValue
     * @return static
     */
    public function filterItemNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.item_num_ext
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterItemNumExt(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num_ext', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.item_num_ext from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipItemNumExt(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num_ext', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.item_num_ext
     * @return static
     */
    public function groupByItemNumExt(): static
    {
        $this->group($this->alias . '.item_num_ext');
        return $this;
    }

    /**
     * Order by lot_item.item_num_ext
     * @param bool $ascending
     * @return static
     */
    public function orderByItemNumExt(bool $ascending = true): static
    {
        $this->order($this->alias . '.item_num_ext', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.item_num_ext by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeItemNumExt(string $filterValue): static
    {
        $this->like($this->alias . '.item_num_ext', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by lot_item.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.description
     * @return static
     */
    public function groupByDescription(): static
    {
        $this->group($this->alias . '.description');
        return $this;
    }

    /**
     * Order by lot_item.description
     * @param bool $ascending
     * @return static
     */
    public function orderByDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.description', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDescription(string $filterValue): static
    {
        $this->like($this->alias . '.description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.changes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterChanges(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.changes', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.changes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipChanges(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.changes', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.changes
     * @return static
     */
    public function groupByChanges(): static
    {
        $this->group($this->alias . '.changes');
        return $this;
    }

    /**
     * Order by lot_item.changes
     * @param bool $ascending
     * @return static
     */
    public function orderByChanges(bool $ascending = true): static
    {
        $this->order($this->alias . '.changes', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.changes by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeChanges(string $filterValue): static
    {
        $this->like($this->alias . '.changes', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.low_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLowEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.low_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.low_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLowEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.low_estimate', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.low_estimate
     * @return static
     */
    public function groupByLowEstimate(): static
    {
        $this->group($this->alias . '.low_estimate');
        return $this;
    }

    /**
     * Order by lot_item.low_estimate
     * @param bool $ascending
     * @return static
     */
    public function orderByLowEstimate(bool $ascending = true): static
    {
        $this->order($this->alias . '.low_estimate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterLowEstimateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.low_estimate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterLowEstimateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.low_estimate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterLowEstimateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.low_estimate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterLowEstimateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.low_estimate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.high_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHighEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.high_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.high_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHighEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.high_estimate', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.high_estimate
     * @return static
     */
    public function groupByHighEstimate(): static
    {
        $this->group($this->alias . '.high_estimate');
        return $this;
    }

    /**
     * Order by lot_item.high_estimate
     * @param bool $ascending
     * @return static
     */
    public function orderByHighEstimate(bool $ascending = true): static
    {
        $this->order($this->alias . '.high_estimate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterHighEstimateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.high_estimate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterHighEstimateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.high_estimate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterHighEstimateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.high_estimate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterHighEstimateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.high_estimate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.starting_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterStartingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.starting_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipStartingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.starting_bid
     * @return static
     */
    public function groupByStartingBid(): static
    {
        $this->group($this->alias . '.starting_bid');
        return $this;
    }

    /**
     * Order by lot_item.starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.cost
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCost(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cost', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.cost from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCost(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cost', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.cost
     * @return static
     */
    public function groupByCost(): static
    {
        $this->group($this->alias . '.cost');
        return $this;
    }

    /**
     * Order by lot_item.cost
     * @param bool $ascending
     * @return static
     */
    public function orderByCost(bool $ascending = true): static
    {
        $this->order($this->alias . '.cost', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.cost
     * @param float $filterValue
     * @return static
     */
    public function filterCostGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.cost
     * @param float $filterValue
     * @return static
     */
    public function filterCostGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.cost
     * @param float $filterValue
     * @return static
     */
    public function filterCostLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.cost
     * @param float $filterValue
     * @return static
     */
    public function filterCostLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.replacement_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterReplacementPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.replacement_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.replacement_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipReplacementPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.replacement_price', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.replacement_price
     * @return static
     */
    public function groupByReplacementPrice(): static
    {
        $this->group($this->alias . '.replacement_price');
        return $this;
    }

    /**
     * Order by lot_item.replacement_price
     * @param bool $ascending
     * @return static
     */
    public function orderByReplacementPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.replacement_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.replacement_price
     * @param float $filterValue
     * @return static
     */
    public function filterReplacementPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.replacement_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.replacement_price
     * @param float $filterValue
     * @return static
     */
    public function filterReplacementPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.replacement_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.replacement_price
     * @param float $filterValue
     * @return static
     */
    public function filterReplacementPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.replacement_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.replacement_price
     * @param float $filterValue
     * @return static
     */
    public function filterReplacementPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.replacement_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.reserve_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterReservePrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.reserve_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipReservePrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_price', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.reserve_price
     * @return static
     */
    public function groupByReservePrice(): static
    {
        $this->group($this->alias . '.reserve_price');
        return $this;
    }

    /**
     * Order by lot_item.reserve_price
     * @param bool $ascending
     * @return static
     */
    public function orderByReservePrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.reserve_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.reserve_price
     * @param float $filterValue
     * @return static
     */
    public function filterReservePriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.reserve_price
     * @param float $filterValue
     * @return static
     */
    public function filterReservePriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.reserve_price
     * @param float $filterValue
     * @return static
     */
    public function filterReservePriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.reserve_price
     * @param float $filterValue
     * @return static
     */
    public function filterReservePriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.hammer_price
     * @return static
     */
    public function groupByHammerPrice(): static
    {
        $this->group($this->alias . '.hammer_price');
        return $this;
    }

    /**
     * Order by lot_item.hammer_price
     * @param bool $ascending
     * @return static
     */
    public function orderByHammerPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.hammer_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.winning_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWinningBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.winning_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.winning_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWinningBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.winning_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.winning_bidder_id
     * @return static
     */
    public function groupByWinningBidderId(): static
    {
        $this->group($this->alias . '.winning_bidder_id');
        return $this;
    }

    /**
     * Order by lot_item.winning_bidder_id
     * @param bool $ascending
     * @return static
     */
    public function orderByWinningBidderId(bool $ascending = true): static
    {
        $this->order($this->alias . '.winning_bidder_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.internet_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInternetBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.internet_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.internet_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInternetBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.internet_bid', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.internet_bid
     * @return static
     */
    public function groupByInternetBid(): static
    {
        $this->group($this->alias . '.internet_bid');
        return $this;
    }

    /**
     * Order by lot_item.internet_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByInternetBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.internet_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.internet_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterInternetBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.internet_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.internet_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterInternetBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.internet_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.internet_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterInternetBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.internet_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.internet_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterInternetBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.internet_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by lot_item.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.date_sold
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterDateSold(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.date_sold', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.date_sold from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipDateSold(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.date_sold', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.date_sold
     * @return static
     */
    public function groupByDateSold(): static
    {
        $this->group($this->alias . '.date_sold');
        return $this;
    }

    /**
     * Order by lot_item.date_sold
     * @param bool $ascending
     * @return static
     */
    public function orderByDateSold(bool $ascending = true): static
    {
        $this->order($this->alias . '.date_sold', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.date_sold
     * @param string $filterValue
     * @return static
     */
    public function filterDateSoldGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_sold', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.date_sold
     * @param string $filterValue
     * @return static
     */
    public function filterDateSoldGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_sold', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.date_sold
     * @param string $filterValue
     * @return static
     */
    public function filterDateSoldLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_sold', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.date_sold
     * @param string $filterValue
     * @return static
     */
    public function filterDateSoldLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_sold', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by lot_item.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.tax_exempt
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTaxExempt(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_exempt', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.tax_exempt from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTaxExempt(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_exempt', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.tax_exempt
     * @return static
     */
    public function groupByTaxExempt(): static
    {
        $this->group($this->alias . '.tax_exempt');
        return $this;
    }

    /**
     * Order by lot_item.tax_exempt
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxExempt(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_exempt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.tax_exempt
     * @param bool $filterValue
     * @return static
     */
    public function filterTaxExemptGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exempt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.tax_exempt
     * @param bool $filterValue
     * @return static
     */
    public function filterTaxExemptGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exempt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.tax_exempt
     * @param bool $filterValue
     * @return static
     */
    public function filterTaxExemptLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exempt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.tax_exempt
     * @param bool $filterValue
     * @return static
     */
    public function filterTaxExemptLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exempt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.no_tax_oos
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTaxOos(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax_oos', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.no_tax_oos from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTaxOos(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax_oos', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.no_tax_oos
     * @return static
     */
    public function groupByNoTaxOos(): static
    {
        $this->group($this->alias . '.no_tax_oos');
        return $this;
    }

    /**
     * Order by lot_item.no_tax_oos
     * @param bool $ascending
     * @return static
     */
    public function orderByNoTaxOos(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_tax_oos', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxOosGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_oos', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxOosGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_oos', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxOosLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_oos', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterNoTaxOosLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_tax_oos', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.lot_item_tax_arr
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotItemTaxArr(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_tax_arr', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.lot_item_tax_arr from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotItemTaxArr(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_tax_arr', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.lot_item_tax_arr
     * @return static
     */
    public function groupByLotItemTaxArr(): static
    {
        $this->group($this->alias . '.lot_item_tax_arr');
        return $this;
    }

    /**
     * Order by lot_item.lot_item_tax_arr
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemTaxArr(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_tax_arr', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.lot_item_tax_arr
     * @param bool $filterValue
     * @return static
     */
    public function filterLotItemTaxArrGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_tax_arr', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.lot_item_tax_arr
     * @param bool $filterValue
     * @return static
     */
    public function filterLotItemTaxArrGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_tax_arr', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.lot_item_tax_arr
     * @param bool $filterValue
     * @return static
     */
    public function filterLotItemTaxArrLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_tax_arr', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.lot_item_tax_arr
     * @param bool $filterValue
     * @return static
     */
    public function filterLotItemTaxArrLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_tax_arr', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.returned
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReturned(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.returned', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.returned from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReturned(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.returned', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.returned
     * @return static
     */
    public function groupByReturned(): static
    {
        $this->group($this->alias . '.returned');
        return $this;
    }

    /**
     * Order by lot_item.returned
     * @param bool $ascending
     * @return static
     */
    public function orderByReturned(bool $ascending = true): static
    {
        $this->order($this->alias . '.returned', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.returned
     * @param bool $filterValue
     * @return static
     */
    public function filterReturnedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.returned', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.returned
     * @param bool $filterValue
     * @return static
     */
    public function filterReturnedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.returned', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.returned
     * @param bool $filterValue
     * @return static
     */
    public function filterReturnedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.returned', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.returned
     * @param bool $filterValue
     * @return static
     */
    public function filterReturnedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.returned', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by lot_item.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by lot_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by lot_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by lot_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by lot_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.warranty
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWarranty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.warranty', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.warranty from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWarranty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.warranty', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.warranty
     * @return static
     */
    public function groupByWarranty(): static
    {
        $this->group($this->alias . '.warranty');
        return $this;
    }

    /**
     * Order by lot_item.warranty
     * @param bool $ascending
     * @return static
     */
    public function orderByWarranty(bool $ascending = true): static
    {
        $this->order($this->alias . '.warranty', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.warranty by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeWarranty(string $filterValue): static
    {
        $this->like($this->alias . '.warranty', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notes', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notes', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.notes
     * @return static
     */
    public function groupByNotes(): static
    {
        $this->group($this->alias . '.notes');
        return $this;
    }

    /**
     * Order by lot_item.notes
     * @param bool $ascending
     * @return static
     */
    public function orderByNotes(bool $ascending = true): static
    {
        $this->order($this->alias . '.notes', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.notes by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNotes(string $filterValue): static
    {
        $this->like($this->alias . '.notes', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.changes_timestamp
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterChangesTimestamp(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.changes_timestamp', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.changes_timestamp from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipChangesTimestamp(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.changes_timestamp', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.changes_timestamp
     * @return static
     */
    public function groupByChangesTimestamp(): static
    {
        $this->group($this->alias . '.changes_timestamp');
        return $this;
    }

    /**
     * Order by lot_item.changes_timestamp
     * @param bool $ascending
     * @return static
     */
    public function orderByChangesTimestamp(bool $ascending = true): static
    {
        $this->order($this->alias . '.changes_timestamp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.changes_timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterChangesTimestampGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.changes_timestamp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.changes_timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterChangesTimestampGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.changes_timestamp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.changes_timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterChangesTimestampLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.changes_timestamp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.changes_timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterChangesTimestampLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.changes_timestamp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.only_tax_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyTaxBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_tax_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.only_tax_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyTaxBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_tax_bp', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.only_tax_bp
     * @return static
     */
    public function groupByOnlyTaxBp(): static
    {
        $this->group($this->alias . '.only_tax_bp');
        return $this;
    }

    /**
     * Order by lot_item.only_tax_bp
     * @param bool $ascending
     * @return static
     */
    public function orderByOnlyTaxBp(bool $ascending = true): static
    {
        $this->order($this->alias . '.only_tax_bp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.only_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyTaxBpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_tax_bp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.only_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyTaxBpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_tax_bp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.only_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyTaxBpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_tax_bp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.only_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyTaxBpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_tax_bp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.tax_default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.tax_default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_default_country', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.tax_default_country
     * @return static
     */
    public function groupByTaxDefaultCountry(): static
    {
        $this->group($this->alias . '.tax_default_country');
        return $this;
    }

    /**
     * Order by lot_item.tax_default_country
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxDefaultCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_default_country', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.tax_default_country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTaxDefaultCountry(string $filterValue): static
    {
        $this->like($this->alias . '.tax_default_country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.location_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.location_id
     * @return static
     */
    public function groupByLocationId(): static
    {
        $this->group($this->alias . '.location_id');
        return $this;
    }

    /**
     * Order by lot_item.location_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLocationId(bool $ascending = true): static
    {
        $this->order($this->alias . '.location_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.location_id
     * @param int $filterValue
     * @return static
     */
    public function filterLocationIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.auction_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.auction_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.auction_info
     * @return static
     */
    public function groupByAuctionInfo(): static
    {
        $this->group($this->alias . '.auction_info');
        return $this;
    }

    /**
     * Order by lot_item.auction_info
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_info', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.auction_info by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionInfo(string $filterValue): static
    {
        $this->like($this->alias . '.auction_info', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.additional_bp_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.additional_bp_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.additional_bp_internet
     * @return static
     */
    public function groupByAdditionalBpInternet(): static
    {
        $this->group($this->alias . '.additional_bp_internet');
        return $this;
    }

    /**
     * Order by lot_item.additional_bp_internet
     * @param bool $ascending
     * @return static
     */
    public function orderByAdditionalBpInternet(bool $ascending = true): static
    {
        $this->order($this->alias . '.additional_bp_internet', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.bp_range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.bp_range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.bp_range_calculation
     * @return static
     */
    public function groupByBpRangeCalculation(): static
    {
        $this->group($this->alias . '.bp_range_calculation');
        return $this;
    }

    /**
     * Order by lot_item.bp_range_calculation
     * @param bool $ascending
     * @return static
     */
    public function orderByBpRangeCalculation(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_range_calculation', $ascending);
        return $this;
    }

    /**
     * Filter by lot_item.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.buyers_premium_id
     * @return static
     */
    public function groupByBuyersPremiumId(): static
    {
        $this->group($this->alias . '.buyers_premium_id');
        return $this;
    }

    /**
     * Order by lot_item.buyers_premium_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremiumId(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_title', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_title', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.fb_og_title
     * @return static
     */
    public function groupByFbOgTitle(): static
    {
        $this->group($this->alias . '.fb_og_title');
        return $this;
    }

    /**
     * Order by lot_item.fb_og_title
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_title', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.fb_og_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgTitle(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_description', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.fb_og_description
     * @return static
     */
    public function groupByFbOgDescription(): static
    {
        $this->group($this->alias . '.fb_og_description');
        return $this;
    }

    /**
     * Order by lot_item.fb_og_description
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_description', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.fb_og_description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgDescription(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_image_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgImageUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_image_url', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_image_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgImageUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_image_url', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.fb_og_image_url
     * @return static
     */
    public function groupByFbOgImageUrl(): static
    {
        $this->group($this->alias . '.fb_og_image_url');
        return $this;
    }

    /**
     * Order by lot_item.fb_og_image_url
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgImageUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_image_url', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.fb_og_image_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgImageUrl(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_image_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_title', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_title', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.seo_meta_title
     * @return static
     */
    public function groupBySeoMetaTitle(): static
    {
        $this->group($this->alias . '.seo_meta_title');
        return $this;
    }

    /**
     * Order by lot_item.seo_meta_title
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_title', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.seo_meta_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaTitle(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_keywords
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaKeywords(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_keywords', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_keywords from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaKeywords(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_keywords', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.seo_meta_keywords
     * @return static
     */
    public function groupBySeoMetaKeywords(): static
    {
        $this->group($this->alias . '.seo_meta_keywords');
        return $this;
    }

    /**
     * Order by lot_item.seo_meta_keywords
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaKeywords(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_keywords', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.seo_meta_keywords by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaKeywords(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_keywords', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_description', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.seo_meta_description
     * @return static
     */
    public function groupBySeoMetaDescription(): static
    {
        $this->group($this->alias . '.seo_meta_description');
        return $this;
    }

    /**
     * Order by lot_item.seo_meta_description
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_description', $ascending);
        return $this;
    }

    /**
     * Filter lot_item.seo_meta_description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaDescription(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by lot_item.quantity
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterQuantity(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipQuantity(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.quantity
     * @return static
     */
    public function groupByQuantity(): static
    {
        $this->group($this->alias . '.quantity');
        return $this;
    }

    /**
     * Order by lot_item.quantity
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantity(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.quantity_digits
     * @return static
     */
    public function groupByQuantityDigits(): static
    {
        $this->group($this->alias . '.quantity_digits');
        return $this;
    }

    /**
     * Order by lot_item.quantity_digits
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityDigits(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_digits', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.quantity_x_money
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterQuantityXMoney(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_x_money', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity_x_money from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipQuantityXMoney(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_x_money', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.quantity_x_money
     * @return static
     */
    public function groupByQuantityXMoney(): static
    {
        $this->group($this->alias . '.quantity_x_money');
        return $this;
    }

    /**
     * Order by lot_item.quantity_x_money
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityXMoney(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_x_money', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.buy_now_select_quantity_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_select_quantity_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.buy_now_select_quantity_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowSelectQuantityEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_select_quantity_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.buy_now_select_quantity_enabled
     * @return static
     */
    public function groupByBuyNowSelectQuantityEnabled(): static
    {
        $this->group($this->alias . '.buy_now_select_quantity_enabled');
        return $this;
    }

    /**
     * Order by lot_item.buy_now_select_quantity_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowSelectQuantityEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_select_quantity_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by lot_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.consignor_commission_id
     * @return static
     */
    public function groupByConsignorCommissionId(): static
    {
        $this->group($this->alias . '.consignor_commission_id');
        return $this;
    }

    /**
     * Order by lot_item.consignor_commission_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorCommissionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_commission_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.consignor_sold_fee_id
     * @return static
     */
    public function groupByConsignorSoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_sold_fee_id');
        return $this;
    }

    /**
     * Order by lot_item.consignor_sold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorSoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_sold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.consignor_unsold_fee_id
     * @return static
     */
    public function groupByConsignorUnsoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_unsold_fee_id');
        return $this;
    }

    /**
     * Order by lot_item.consignor_unsold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorUnsoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_unsold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.hp_tax_schema_id
     * @return static
     */
    public function groupByHpTaxSchemaId(): static
    {
        $this->group($this->alias . '.hp_tax_schema_id');
        return $this;
    }

    /**
     * Order by lot_item.hp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by lot_item.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by lot_item.bp_tax_schema_id
     * @return static
     */
    public function groupByBpTaxSchemaId(): static
    {
        $this->group($this->alias . '.bp_tax_schema_id');
        return $this;
    }

    /**
     * Order by lot_item.bp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<=');
        return $this;
    }
}
