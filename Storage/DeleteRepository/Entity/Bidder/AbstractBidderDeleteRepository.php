<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Bidder;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractBidderDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_BIDDER;
    protected string $alias = Db::A_BIDDER;

    /**
     * Filter by bidder.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.preferred
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPreferred(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.preferred', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.preferred from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPreferred(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.preferred', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.house
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHouse(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.house', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.house from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHouse(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.house', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.agent
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAgent(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.agent', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.agent from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAgent(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.agent', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.bp_range_calculation_live
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculationLive(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation_live', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.bp_range_calculation_live from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculationLive(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation_live', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.bp_range_calculation_timed
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculationTimed(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation_timed', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.bp_range_calculation_timed from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculationTimed(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation_timed', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.bp_range_calculation_hybrid
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculationHybrid(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.bp_range_calculation_hybrid from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculationHybrid(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.additional_bp_internet_live
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLive(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet_live', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.additional_bp_internet_live from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternetLive(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet_live', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.additional_bp_internet_hybrid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetHybrid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.additional_bp_internet_hybrid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternetHybrid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.agent_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAgentId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.agent_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.agent_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAgentId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.agent_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by bidder.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out bidder.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
