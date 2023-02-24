<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Settlement;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use Settlement;

/**
 * Abstract class AbstractSettlementReadRepository
 * @method Settlement[] loadEntities()
 * @method Settlement|null loadEntity()
 */
abstract class AbstractSettlementReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTLEMENT;
    protected string $alias = Db::A_SETTLEMENT;

    /**
     * Filter by settlement.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by settlement.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.settlement_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSettlementNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_no', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.settlement_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSettlementNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_no', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.settlement_no
     * @return static
     */
    public function groupBySettlementNo(): static
    {
        $this->group($this->alias . '.settlement_no');
        return $this;
    }

    /**
     * Order by settlement.settlement_no
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_no', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.settlement_no
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementNoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_no', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.settlement_no
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementNoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_no', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.settlement_no
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementNoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_no', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.settlement_no
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementNoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_no', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by settlement.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.settlement_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSettlementStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.settlement_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSettlementStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_status_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.settlement_status_id
     * @return static
     */
    public function groupBySettlementStatusId(): static
    {
        $this->group($this->alias . '.settlement_status_id');
        return $this;
    }

    /**
     * Order by settlement.settlement_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.settlement_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.settlement_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.settlement_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.settlement_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_status_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_id
     * @return static
     */
    public function groupByConsignorId(): static
    {
        $this->group($this->alias . '.consignor_id');
        return $this;
    }

    /**
     * Order by settlement.consignor_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by settlement.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignment_commission
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignmentCommission(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignment_commission', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignment_commission from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignmentCommission(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignment_commission', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignment_commission
     * @return static
     */
    public function groupByConsignmentCommission(): static
    {
        $this->group($this->alias . '.consignment_commission');
        return $this;
    }

    /**
     * Order by settlement.consignment_commission
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignmentCommission(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignment_commission', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignment_commission
     * @param float $filterValue
     * @return static
     */
    public function filterConsignmentCommissionLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignment_commission', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.hp_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHpTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.hp_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHpTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.hp_total
     * @return static
     */
    public function groupByHpTotal(): static
    {
        $this->group($this->alias . '.hp_total');
        return $this;
    }

    /**
     * Order by settlement.hp_total
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.hp_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.hp_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.hp_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.hp_total
     * @param float $filterValue
     * @return static
     */
    public function filterHpTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.comm_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCommTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.comm_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.comm_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCommTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.comm_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.comm_total
     * @return static
     */
    public function groupByCommTotal(): static
    {
        $this->group($this->alias . '.comm_total');
        return $this;
    }

    /**
     * Order by settlement.comm_total
     * @param bool $ascending
     * @return static
     */
    public function orderByCommTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.comm_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.comm_total
     * @param float $filterValue
     * @return static
     */
    public function filterCommTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.comm_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.comm_total
     * @param float $filterValue
     * @return static
     */
    public function filterCommTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.comm_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.comm_total
     * @param float $filterValue
     * @return static
     */
    public function filterCommTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.comm_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.comm_total
     * @param float $filterValue
     * @return static
     */
    public function filterCommTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.comm_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.extra_charges
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExtraCharges(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extra_charges', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.extra_charges from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExtraCharges(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extra_charges', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.extra_charges
     * @return static
     */
    public function groupByExtraCharges(): static
    {
        $this->group($this->alias . '.extra_charges');
        return $this;
    }

    /**
     * Order by settlement.extra_charges
     * @param bool $ascending
     * @return static
     */
    public function orderByExtraCharges(bool $ascending = true): static
    {
        $this->order($this->alias . '.extra_charges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.extra_charges
     * @param float $filterValue
     * @return static
     */
    public function filterExtraChargesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.extra_charges', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.cost_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCostTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cost_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.cost_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCostTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cost_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.cost_total
     * @return static
     */
    public function groupByCostTotal(): static
    {
        $this->group($this->alias . '.cost_total');
        return $this;
    }

    /**
     * Order by settlement.cost_total
     * @param bool $ascending
     * @return static
     */
    public function orderByCostTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.cost_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.cost_total
     * @param float $filterValue
     * @return static
     */
    public function filterCostTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.cost_total
     * @param float $filterValue
     * @return static
     */
    public function filterCostTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.cost_total
     * @param float $filterValue
     * @return static
     */
    public function filterCostTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.cost_total
     * @param float $filterValue
     * @return static
     */
    public function filterCostTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cost_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.taxable_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxableTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.taxable_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.taxable_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxableTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.taxable_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.taxable_total
     * @return static
     */
    public function groupByTaxableTotal(): static
    {
        $this->group($this->alias . '.taxable_total');
        return $this;
    }

    /**
     * Order by settlement.taxable_total
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxableTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.taxable_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterTaxableTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.taxable_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterTaxableTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.taxable_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterTaxableTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.taxable_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterTaxableTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.taxable_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.non_taxable_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterNonTaxableTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.non_taxable_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.non_taxable_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipNonTaxableTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.non_taxable_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.non_taxable_total
     * @return static
     */
    public function groupByNonTaxableTotal(): static
    {
        $this->group($this->alias . '.non_taxable_total');
        return $this;
    }

    /**
     * Order by settlement.non_taxable_total
     * @param bool $ascending
     * @return static
     */
    public function orderByNonTaxableTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.non_taxable_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.non_taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterNonTaxableTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.non_taxable_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.non_taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterNonTaxableTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.non_taxable_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.non_taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterNonTaxableTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.non_taxable_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.non_taxable_total
     * @param float $filterValue
     * @return static
     */
    public function filterNonTaxableTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.non_taxable_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.export_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExportTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.export_total', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.export_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExportTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.export_total', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.export_total
     * @return static
     */
    public function groupByExportTotal(): static
    {
        $this->group($this->alias . '.export_total');
        return $this;
    }

    /**
     * Order by settlement.export_total
     * @param bool $ascending
     * @return static
     */
    public function orderByExportTotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.export_total', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.export_total
     * @param float $filterValue
     * @return static
     */
    public function filterExportTotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.export_total', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.export_total
     * @param float $filterValue
     * @return static
     */
    public function filterExportTotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.export_total', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.export_total
     * @param float $filterValue
     * @return static
     */
    public function filterExportTotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.export_total', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.export_total
     * @param float $filterValue
     * @return static
     */
    public function filterExportTotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.export_total', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.tax_inclusive
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxInclusive(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_inclusive', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.tax_inclusive from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxInclusive(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_inclusive', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.tax_inclusive
     * @return static
     */
    public function groupByTaxInclusive(): static
    {
        $this->group($this->alias . '.tax_inclusive');
        return $this;
    }

    /**
     * Order by settlement.tax_inclusive
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxInclusive(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_inclusive', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.tax_inclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxInclusiveGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_inclusive', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.tax_inclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxInclusiveGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_inclusive', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.tax_inclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxInclusiveLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_inclusive', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.tax_inclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxInclusiveLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_inclusive', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.tax_exclusive
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxExclusive(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_exclusive', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.tax_exclusive from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxExclusive(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_exclusive', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.tax_exclusive
     * @return static
     */
    public function groupByTaxExclusive(): static
    {
        $this->group($this->alias . '.tax_exclusive');
        return $this;
    }

    /**
     * Order by settlement.tax_exclusive
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxExclusive(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_exclusive', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.tax_exclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxExclusiveGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exclusive', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.tax_exclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxExclusiveGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exclusive', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.tax_exclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxExclusiveLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exclusive', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.tax_exclusive
     * @param float $filterValue
     * @return static
     */
    public function filterTaxExclusiveLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_exclusive', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.tax_services
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxServices(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_services', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.tax_services from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxServices(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_services', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.tax_services
     * @return static
     */
    public function groupByTaxServices(): static
    {
        $this->group($this->alias . '.tax_services');
        return $this;
    }

    /**
     * Order by settlement.tax_services
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxServices(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_services', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.tax_services
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServicesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_services', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.tax_services
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServicesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_services', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.tax_services
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServicesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_services', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.tax_services
     * @param float $filterValue
     * @return static
     */
    public function filterTaxServicesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_services', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignorTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignorTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_tax
     * @return static
     */
    public function groupByConsignorTax(): static
    {
        $this->group($this->alias . '.consignor_tax');
        return $this;
    }

    /**
     * Order by settlement.consignor_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_tax_hp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxHp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_hp', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_tax_hp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxHp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_hp', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_tax_hp
     * @return static
     */
    public function groupByConsignorTaxHp(): static
    {
        $this->group($this->alias . '.consignor_tax_hp');
        return $this;
    }

    /**
     * Order by settlement.consignor_tax_hp
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxHp(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_hp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_tax_hp_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorTaxHpType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_hp_type', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_tax_hp_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorTaxHpType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_hp_type', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_tax_hp_type
     * @return static
     */
    public function groupByConsignorTaxHpType(): static
    {
        $this->group($this->alias . '.consignor_tax_hp_type');
        return $this;
    }

    /**
     * Order by settlement.consignor_tax_hp_type
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxHpType(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_hp_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_tax_comm
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxComm(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_comm', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_tax_comm from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxComm(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_comm', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_tax_comm
     * @return static
     */
    public function groupByConsignorTaxComm(): static
    {
        $this->group($this->alias . '.consignor_tax_comm');
        return $this;
    }

    /**
     * Order by settlement.consignor_tax_comm
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxComm(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_comm', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.consignor_tax_services
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxServices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_services', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.consignor_tax_services from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxServices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_services', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.consignor_tax_services
     * @return static
     */
    public function groupByConsignorTaxServices(): static
    {
        $this->group($this->alias . '.consignor_tax_services');
        return $this;
    }

    /**
     * Order by settlement.consignor_tax_services
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxServices(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_services', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by settlement.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter settlement.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement.settlement_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterSettlementDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_date', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.settlement_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipSettlementDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_date', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.settlement_date
     * @return static
     */
    public function groupBySettlementDate(): static
    {
        $this->group($this->alias . '.settlement_date');
        return $this;
    }

    /**
     * Order by settlement.settlement_date
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.settlement_date
     * @param string $filterValue
     * @return static
     */
    public function filterSettlementDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.settlement_date
     * @param string $filterValue
     * @return static
     */
    public function filterSettlementDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.settlement_date
     * @param string $filterValue
     * @return static
     */
    public function filterSettlementDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.settlement_date
     * @param string $filterValue
     * @return static
     */
    public function filterSettlementDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by settlement.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by settlement.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by settlement.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by settlement.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by settlement.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by settlement.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
