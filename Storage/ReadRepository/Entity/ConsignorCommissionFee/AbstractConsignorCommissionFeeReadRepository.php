<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee;

use ConsignorCommissionFee;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractConsignorCommissionFeeReadRepository
 * @method ConsignorCommissionFee[] loadEntities()
 * @method ConsignorCommissionFee|null loadEntity()
 */
abstract class AbstractConsignorCommissionFeeReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_CONSIGNOR_COMMISSION_FEE;
    protected string $alias = Db::A_CONSIGNOR_COMMISSION_FEE;

    /**
     * Filter by consignor_commission_fee.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.name
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterName(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.name from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipName(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter consignor_commission_fee.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.calculation_method
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCalculationMethod(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.calculation_method', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.calculation_method from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCalculationMethod(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.calculation_method', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.calculation_method
     * @return static
     */
    public function groupByCalculationMethod(): static
    {
        $this->group($this->alias . '.calculation_method');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.calculation_method
     * @param bool $ascending
     * @return static
     */
    public function orderByCalculationMethod(bool $ascending = true): static
    {
        $this->order($this->alias . '.calculation_method', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.calculation_method
     * @param int $filterValue
     * @return static
     */
    public function filterCalculationMethodGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculation_method', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.calculation_method
     * @param int $filterValue
     * @return static
     */
    public function filterCalculationMethodGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculation_method', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.calculation_method
     * @param int $filterValue
     * @return static
     */
    public function filterCalculationMethodLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculation_method', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.calculation_method
     * @param int $filterValue
     * @return static
     */
    public function filterCalculationMethodLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculation_method', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.fee_reference
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFeeReference(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fee_reference', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.fee_reference from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFeeReference(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fee_reference', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.fee_reference
     * @return static
     */
    public function groupByFeeReference(): static
    {
        $this->group($this->alias . '.fee_reference');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.fee_reference
     * @param bool $ascending
     * @return static
     */
    public function orderByFeeReference(bool $ascending = true): static
    {
        $this->order($this->alias . '.fee_reference', $ascending);
        return $this;
    }

    /**
     * Filter consignor_commission_fee.fee_reference by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFeeReference(string $filterValue): static
    {
        $this->like($this->alias . '.fee_reference', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.level
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLevel(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.level', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.level from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLevel(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.level', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.level
     * @return static
     */
    public function groupByLevel(): static
    {
        $this->group($this->alias . '.level');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.level
     * @param bool $ascending
     * @return static
     */
    public function orderByLevel(bool $ascending = true): static
    {
        $this->order($this->alias . '.level', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.level
     * @param int $filterValue
     * @return static
     */
    public function filterLevelLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.level', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.related_entity_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterRelatedEntityId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.related_entity_id', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.related_entity_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipRelatedEntityId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.related_entity_id', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.related_entity_id
     * @return static
     */
    public function groupByRelatedEntityId(): static
    {
        $this->group($this->alias . '.related_entity_id');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.related_entity_id
     * @param bool $ascending
     * @return static
     */
    public function orderByRelatedEntityId(bool $ascending = true): static
    {
        $this->order($this->alias . '.related_entity_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.related_entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterRelatedEntityIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.related_entity_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.related_entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterRelatedEntityIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.related_entity_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.related_entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterRelatedEntityIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.related_entity_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.related_entity_id
     * @param int $filterValue
     * @return static
     */
    public function filterRelatedEntityIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.related_entity_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by consignor_commission_fee.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor_commission_fee.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by consignor_commission_fee.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by consignor_commission_fee.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor_commission_fee.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor_commission_fee.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor_commission_fee.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor_commission_fee.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
