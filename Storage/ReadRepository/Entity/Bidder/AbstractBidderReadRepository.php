<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Bidder;

use Bidder;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractBidderReadRepository
 * @method Bidder[] loadEntities()
 * @method Bidder|null loadEntity()
 */
abstract class AbstractBidderReadRepository extends ReadRepositoryBase
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
     * Group by bidder.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by bidder.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by bidder.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by bidder.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by bidder.preferred
     * @return static
     */
    public function groupByPreferred(): static
    {
        $this->group($this->alias . '.preferred');
        return $this;
    }

    /**
     * Order by bidder.preferred
     * @param bool $ascending
     * @return static
     */
    public function orderByPreferred(bool $ascending = true): static
    {
        $this->order($this->alias . '.preferred', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterPreferredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.preferred', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterPreferredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.preferred', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterPreferredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.preferred', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterPreferredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.preferred', $filterValue, '<=');
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
     * Group by bidder.house
     * @return static
     */
    public function groupByHouse(): static
    {
        $this->group($this->alias . '.house');
        return $this;
    }

    /**
     * Order by bidder.house
     * @param bool $ascending
     * @return static
     */
    public function orderByHouse(bool $ascending = true): static
    {
        $this->order($this->alias . '.house', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.house
     * @param bool $filterValue
     * @return static
     */
    public function filterHouseGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.house', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.house
     * @param bool $filterValue
     * @return static
     */
    public function filterHouseGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.house', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.house
     * @param bool $filterValue
     * @return static
     */
    public function filterHouseLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.house', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.house
     * @param bool $filterValue
     * @return static
     */
    public function filterHouseLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.house', $filterValue, '<=');
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
     * Group by bidder.agent
     * @return static
     */
    public function groupByAgent(): static
    {
        $this->group($this->alias . '.agent');
        return $this;
    }

    /**
     * Order by bidder.agent
     * @param bool $ascending
     * @return static
     */
    public function orderByAgent(bool $ascending = true): static
    {
        $this->order($this->alias . '.agent', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.agent
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.agent
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.agent
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.agent
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent', $filterValue, '<=');
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
     * Group by bidder.bp_range_calculation_live
     * @return static
     */
    public function groupByBpRangeCalculationLive(): static
    {
        $this->group($this->alias . '.bp_range_calculation_live');
        return $this;
    }

    /**
     * Order by bidder.bp_range_calculation_live
     * @param bool $ascending
     * @return static
     */
    public function orderByBpRangeCalculationLive(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_range_calculation_live', $ascending);
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
     * Group by bidder.bp_range_calculation_timed
     * @return static
     */
    public function groupByBpRangeCalculationTimed(): static
    {
        $this->group($this->alias . '.bp_range_calculation_timed');
        return $this;
    }

    /**
     * Order by bidder.bp_range_calculation_timed
     * @param bool $ascending
     * @return static
     */
    public function orderByBpRangeCalculationTimed(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_range_calculation_timed', $ascending);
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
     * Group by bidder.bp_range_calculation_hybrid
     * @return static
     */
    public function groupByBpRangeCalculationHybrid(): static
    {
        $this->group($this->alias . '.bp_range_calculation_hybrid');
        return $this;
    }

    /**
     * Order by bidder.bp_range_calculation_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByBpRangeCalculationHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_range_calculation_hybrid', $ascending);
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
     * Group by bidder.additional_bp_internet_live
     * @return static
     */
    public function groupByAdditionalBpInternetLive(): static
    {
        $this->group($this->alias . '.additional_bp_internet_live');
        return $this;
    }

    /**
     * Order by bidder.additional_bp_internet_live
     * @param bool $ascending
     * @return static
     */
    public function orderByAdditionalBpInternetLive(bool $ascending = true): static
    {
        $this->order($this->alias . '.additional_bp_internet_live', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.additional_bp_internet_live
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLiveGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_live', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.additional_bp_internet_live
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLiveGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_live', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.additional_bp_internet_live
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLiveLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_live', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.additional_bp_internet_live
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLiveLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_live', $filterValue, '<=');
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
     * Group by bidder.additional_bp_internet_hybrid
     * @return static
     */
    public function groupByAdditionalBpInternetHybrid(): static
    {
        $this->group($this->alias . '.additional_bp_internet_hybrid');
        return $this;
    }

    /**
     * Order by bidder.additional_bp_internet_hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByAdditionalBpInternetHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.additional_bp_internet_hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.additional_bp_internet_hybrid
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetHybridGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.additional_bp_internet_hybrid
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetHybridGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.additional_bp_internet_hybrid
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetHybridLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.additional_bp_internet_hybrid
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetHybridLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet_hybrid', $filterValue, '<=');
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
     * Group by bidder.buyers_premium_id
     * @return static
     */
    public function groupByBuyersPremiumId(): static
    {
        $this->group($this->alias . '.buyers_premium_id');
        return $this;
    }

    /**
     * Order by bidder.buyers_premium_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremiumId(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<=');
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
     * Group by bidder.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by bidder.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by bidder.agent_id
     * @return static
     */
    public function groupByAgentId(): static
    {
        $this->group($this->alias . '.agent_id');
        return $this;
    }

    /**
     * Order by bidder.agent_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAgentId(bool $ascending = true): static
    {
        $this->order($this->alias . '.agent_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.agent_id
     * @param int $filterValue
     * @return static
     */
    public function filterAgentIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.agent_id
     * @param int $filterValue
     * @return static
     */
    public function filterAgentIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.agent_id
     * @param int $filterValue
     * @return static
     */
    public function filterAgentIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.agent_id
     * @param int $filterValue
     * @return static
     */
    public function filterAgentIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_id', $filterValue, '<=');
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
     * Group by bidder.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by bidder.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by bidder.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by bidder.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by bidder.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by bidder.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by bidder.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by bidder.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
