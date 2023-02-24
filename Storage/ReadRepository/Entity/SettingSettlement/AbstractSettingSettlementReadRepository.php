<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSettlement;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSettlement;

/**
 * Abstract class AbstractSettingSettlementReadRepository
 * @method SettingSettlement[] loadEntities()
 * @method SettingSettlement|null loadEntity()
 */
abstract class AbstractSettingSettlementReadRepository extends ReadRepositoryBase
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
     * Group by setting_settlement.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_settlement.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_settlement.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_settlement.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_settlement.settlement_logo
     * @return static
     */
    public function groupBySettlementLogo(): static
    {
        $this->group($this->alias . '.settlement_logo');
        return $this;
    }

    /**
     * Order by setting_settlement.settlement_logo
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementLogo(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_logo', $ascending);
        return $this;
    }

    /**
     * Filter setting_settlement.settlement_logo by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSettlementLogo(string $filterValue): static
    {
        $this->like($this->alias . '.settlement_logo', "%{$filterValue}%");
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
     * Group by setting_settlement.charge_consignor_commission
     * @return static
     */
    public function groupByChargeConsignorCommission(): static
    {
        $this->group($this->alias . '.charge_consignor_commission');
        return $this;
    }

    /**
     * Order by setting_settlement.charge_consignor_commission
     * @param bool $ascending
     * @return static
     */
    public function orderByChargeConsignorCommission(bool $ascending = true): static
    {
        $this->order($this->alias . '.charge_consignor_commission', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.charge_consignor_commission
     * @param bool $filterValue
     * @return static
     */
    public function filterChargeConsignorCommissionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.charge_consignor_commission', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.charge_consignor_commission
     * @param bool $filterValue
     * @return static
     */
    public function filterChargeConsignorCommissionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.charge_consignor_commission', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.charge_consignor_commission
     * @param bool $filterValue
     * @return static
     */
    public function filterChargeConsignorCommissionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.charge_consignor_commission', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.charge_consignor_commission
     * @param bool $filterValue
     * @return static
     */
    public function filterChargeConsignorCommissionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.charge_consignor_commission', $filterValue, '<=');
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
     * Group by setting_settlement.settlement_unpaid_lots
     * @return static
     */
    public function groupBySettlementUnpaidLots(): static
    {
        $this->group($this->alias . '.settlement_unpaid_lots');
        return $this;
    }

    /**
     * Order by setting_settlement.settlement_unpaid_lots
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementUnpaidLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_unpaid_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.settlement_unpaid_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterSettlementUnpaidLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_unpaid_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.settlement_unpaid_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterSettlementUnpaidLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_unpaid_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.settlement_unpaid_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterSettlementUnpaidLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_unpaid_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.settlement_unpaid_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterSettlementUnpaidLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_unpaid_lots', $filterValue, '<=');
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
     * Group by setting_settlement.quantity_in_settlement
     * @return static
     */
    public function groupByQuantityInSettlement(): static
    {
        $this->group($this->alias . '.quantity_in_settlement');
        return $this;
    }

    /**
     * Order by setting_settlement.quantity_in_settlement
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityInSettlement(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_in_settlement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.quantity_in_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInSettlementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_settlement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.quantity_in_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInSettlementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_settlement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.quantity_in_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInSettlementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_settlement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.quantity_in_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInSettlementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_settlement', $filterValue, '<=');
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
     * Group by setting_settlement.multiple_sale_settlement
     * @return static
     */
    public function groupByMultipleSaleSettlement(): static
    {
        $this->group($this->alias . '.multiple_sale_settlement');
        return $this;
    }

    /**
     * Order by setting_settlement.multiple_sale_settlement
     * @param bool $ascending
     * @return static
     */
    public function orderByMultipleSaleSettlement(bool $ascending = true): static
    {
        $this->order($this->alias . '.multiple_sale_settlement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.multiple_sale_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleSettlementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_settlement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.multiple_sale_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleSettlementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_settlement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.multiple_sale_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleSettlementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_settlement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.multiple_sale_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleSettlementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_settlement', $filterValue, '<=');
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
     * Group by setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @return static
     */
    public function groupByRenderLotCustomFieldsInSeparateRowForSettlement(): static
    {
        $this->group($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement');
        return $this;
    }

    /**
     * Order by setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool $ascending
     * @return static
     */
    public function orderByRenderLotCustomFieldsInSeparateRowForSettlement(bool $ascending = true): static
    {
        $this->order($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForSettlementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForSettlementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForSettlementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.render_lot_custom_fields_in_separate_row_for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForSettlementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_settlement', $filterValue, '<=');
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
     * Group by setting_settlement.consignor_commission_id
     * @return static
     */
    public function groupByConsignorCommissionId(): static
    {
        $this->group($this->alias . '.consignor_commission_id');
        return $this;
    }

    /**
     * Order by setting_settlement.consignor_commission_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorCommissionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_commission_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<=');
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
     * Group by setting_settlement.consignor_sold_fee_id
     * @return static
     */
    public function groupByConsignorSoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_sold_fee_id');
        return $this;
    }

    /**
     * Order by setting_settlement.consignor_sold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorSoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_sold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<=');
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
     * Group by setting_settlement.consignor_unsold_fee_id
     * @return static
     */
    public function groupByConsignorUnsoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_unsold_fee_id');
        return $this;
    }

    /**
     * Order by setting_settlement.consignor_unsold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorUnsoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_unsold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<=');
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
     * Group by setting_settlement.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_settlement.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_settlement.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_settlement.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_settlement.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_settlement.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_settlement.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_settlement.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_settlement.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_settlement.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_settlement.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
