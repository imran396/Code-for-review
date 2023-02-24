<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserConsignorCommissionFee;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserConsignorCommissionFeeDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_USER_CONSIGNOR_COMMISSION_FEE;
    protected string $alias = Db::A_USER_CONSIGNOR_COMMISSION_FEE;

    /**
     * Filter by user_consignor_commission_fee.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.commission_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_consignor_commission_fee.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_consignor_commission_fee.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
