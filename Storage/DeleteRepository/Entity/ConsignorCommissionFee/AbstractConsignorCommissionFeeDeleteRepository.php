<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ConsignorCommissionFee;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractConsignorCommissionFeeDeleteRepository extends DeleteRepositoryBase
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
}
