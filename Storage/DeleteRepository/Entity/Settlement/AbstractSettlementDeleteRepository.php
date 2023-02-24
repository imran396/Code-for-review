<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Settlement;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettlementDeleteRepository extends DeleteRepositoryBase
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
}
