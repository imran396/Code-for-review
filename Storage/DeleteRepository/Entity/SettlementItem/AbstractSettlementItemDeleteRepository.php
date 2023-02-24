<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettlementItem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettlementItemDeleteRepository extends DeleteRepositoryBase
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
}
