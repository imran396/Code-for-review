<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyersPremium;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractBuyersPremiumDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_BUYERS_PREMIUM;
    protected string $alias = Db::A_BUYERS_PREMIUM;

    /**
     * Filter by buyers_premium.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.short_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShortName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.short_name', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.short_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShortName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.short_name', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.range_calculation', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.additional_bp_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.additional_bp_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by buyers_premium.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
