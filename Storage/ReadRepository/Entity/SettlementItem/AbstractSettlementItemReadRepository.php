<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementItem;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettlementItem;

/**
 * Abstract class AbstractSettlementItemReadRepository
 * @method SettlementItem[] loadEntities()
 * @method SettlementItem|null loadEntity()
 */
abstract class AbstractSettlementItemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTLEMENT_ITEM;
    protected string $alias = Db::A_SETTLEMENT_ITEM;

    /**
     * Filter by settlement_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by settlement_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by settlement_item.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by settlement_item.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.hammer_price
     * @return static
     */
    public function groupByHammerPrice(): static
    {
        $this->group($this->alias . '.hammer_price');
        return $this;
    }

    /**
     * Order by settlement_item.hammer_price
     * @param bool $ascending
     * @return static
     */
    public function orderByHammerPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.hammer_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.consignment_commission
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignmentCommission(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignment_commission', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.consignment_commission from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignmentCommission(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignment_commission', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.consignment_commission
     * @return static
     */
    public function groupByConsignmentCommission(): static
    {
        $this->group($this->alias . '.consignment_commission');
        return $this;
    }

    /**
     * Order by settlement_item.consignment_commission
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignmentCommission(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignment_commission', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.subtotal
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSubtotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.subtotal', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.subtotal from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSubtotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.subtotal', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.subtotal
     * @return static
     */
    public function groupBySubtotal(): static
    {
        $this->group($this->alias . '.subtotal');
        return $this;
    }

    /**
     * Order by settlement_item.subtotal
     * @param bool $ascending
     * @return static
     */
    public function orderBySubtotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.subtotal', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.settlement_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSettlementId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.settlement_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSettlementId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.settlement_id
     * @return static
     */
    public function groupBySettlementId(): static
    {
        $this->group($this->alias . '.settlement_id');
        return $this;
    }

    /**
     * Order by settlement_item.settlement_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementId(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by settlement_item.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.lot_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_name', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.lot_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_name', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.lot_name
     * @return static
     */
    public function groupByLotName(): static
    {
        $this->group($this->alias . '.lot_name');
        return $this;
    }

    /**
     * Order by settlement_item.lot_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLotName(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_name', $ascending);
        return $this;
    }

    /**
     * Filter settlement_item.lot_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotName(string $filterValue): static
    {
        $this->like($this->alias . '.lot_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_item.commission
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCommission(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.commission', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.commission from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCommission(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.commission', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.commission
     * @return static
     */
    public function groupByCommission(): static
    {
        $this->group($this->alias . '.commission');
        return $this;
    }

    /**
     * Order by settlement_item.commission
     * @param bool $ascending
     * @return static
     */
    public function orderByCommission(bool $ascending = true): static
    {
        $this->order($this->alias . '.commission', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.commission
     * @param float $filterValue
     * @return static
     */
    public function filterCommissionGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.commission
     * @param float $filterValue
     * @return static
     */
    public function filterCommissionGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.commission
     * @param float $filterValue
     * @return static
     */
    public function filterCommissionLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.commission
     * @param float $filterValue
     * @return static
     */
    public function filterCommissionLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.fee
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterFee(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fee', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.fee from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipFee(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fee', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.fee
     * @return static
     */
    public function groupByFee(): static
    {
        $this->group($this->alias . '.fee');
        return $this;
    }

    /**
     * Order by settlement_item.fee
     * @param bool $ascending
     * @return static
     */
    public function orderByFee(bool $ascending = true): static
    {
        $this->order($this->alias . '.fee', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.fee
     * @param float $filterValue
     * @return static
     */
    public function filterFeeGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.fee
     * @param float $filterValue
     * @return static
     */
    public function filterFeeGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.fee
     * @param float $filterValue
     * @return static
     */
    public function filterFeeLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.fee
     * @param float $filterValue
     * @return static
     */
    public function filterFeeLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.commission_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.commission_id
     * @return static
     */
    public function groupByCommissionId(): static
    {
        $this->group($this->alias . '.commission_id');
        return $this;
    }

    /**
     * Order by settlement_item.commission_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCommissionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.commission_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterCommissionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterCommissionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterCommissionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterCommissionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.commission_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.fee_id
     * @return static
     */
    public function groupByFeeId(): static
    {
        $this->group($this->alias . '.fee_id');
        return $this;
    }

    /**
     * Order by settlement_item.fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by settlement_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by settlement_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by settlement_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by settlement_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by settlement_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
