<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSettlement;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSettlementDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SETTLEMENT;
    protected string $alias = Db::A_SETTING_SETTLEMENT;

    /**
     * Filter by setting_settlement.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.settlement_logo
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSettlementLogo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_logo', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.settlement_logo from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSettlementLogo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_logo', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.charge_consignor_commission
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterChargeConsignorCommission(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.charge_consignor_commission', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.charge_consignor_commission from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipChargeConsignorCommission(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.charge_consignor_commission', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.settlement_unpaid_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSettlementUnpaidLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_unpaid_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.settlement_unpaid_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSettlementUnpaidLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_unpaid_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.quantity_in_settlement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterQuantityInSettlement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_in_settlement', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.quantity_in_settlement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipQuantityInSettlement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_in_settlement', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.multiple_sale_settlement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMultipleSaleSettlement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.multiple_sale_settlement', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.multiple_sale_settlement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMultipleSaleSettlement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.multiple_sale_settlement', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForSettlement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRenderLotCustomFieldsInSeparateRowForSettlement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
