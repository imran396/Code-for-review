<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStats;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserAccountStats;

/**
 * Abstract class AbstractUserAccountStatsReadRepository
 * @method UserAccountStats[] loadEntities()
 * @method UserAccountStats|null loadEntity()
 */
abstract class AbstractUserAccountStatsReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_ACCOUNT_STATS;
    protected string $alias = Db::A_USER_ACCOUNT_STATS;

    /**
     * Filter by user_account_stats.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by user_account_stats.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_account_stats.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.registered_auctions_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterRegisteredAuctionsNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.registered_auctions_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.registered_auctions_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipRegisteredAuctionsNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.registered_auctions_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.registered_auctions_num
     * @return static
     */
    public function groupByRegisteredAuctionsNum(): static
    {
        $this->group($this->alias . '.registered_auctions_num');
        return $this;
    }

    /**
     * Order by user_account_stats.registered_auctions_num
     * @param bool $ascending
     * @return static
     */
    public function orderByRegisteredAuctionsNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.registered_auctions_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.registered_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterRegisteredAuctionsNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_auctions_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.registered_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterRegisteredAuctionsNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_auctions_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.registered_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterRegisteredAuctionsNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_auctions_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.registered_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterRegisteredAuctionsNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_auctions_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.approved_auctions_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterApprovedAuctionsNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.approved_auctions_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.approved_auctions_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipApprovedAuctionsNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.approved_auctions_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.approved_auctions_num
     * @return static
     */
    public function groupByApprovedAuctionsNum(): static
    {
        $this->group($this->alias . '.approved_auctions_num');
        return $this;
    }

    /**
     * Order by user_account_stats.approved_auctions_num
     * @param bool $ascending
     * @return static
     */
    public function orderByApprovedAuctionsNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.approved_auctions_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.approved_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterApprovedAuctionsNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.approved_auctions_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.approved_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterApprovedAuctionsNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.approved_auctions_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.approved_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterApprovedAuctionsNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.approved_auctions_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.approved_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterApprovedAuctionsNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.approved_auctions_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.participated_auctions_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.participated_auctions_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.participated_auctions_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipParticipatedAuctionsNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.participated_auctions_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.participated_auctions_num
     * @return static
     */
    public function groupByParticipatedAuctionsNum(): static
    {
        $this->group($this->alias . '.participated_auctions_num');
        return $this;
    }

    /**
     * Order by user_account_stats.participated_auctions_num
     * @param bool $ascending
     * @return static
     */
    public function orderByParticipatedAuctionsNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.participated_auctions_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.participated_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.participated_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.participated_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.participated_auctions_num
     * @param int $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.participated_auctions_perc
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsPerc(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.participated_auctions_perc', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.participated_auctions_perc from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipParticipatedAuctionsPerc(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.participated_auctions_perc', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.participated_auctions_perc
     * @return static
     */
    public function groupByParticipatedAuctionsPerc(): static
    {
        $this->group($this->alias . '.participated_auctions_perc');
        return $this;
    }

    /**
     * Order by user_account_stats.participated_auctions_perc
     * @param bool $ascending
     * @return static
     */
    public function orderByParticipatedAuctionsPerc(bool $ascending = true): static
    {
        $this->order($this->alias . '.participated_auctions_perc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.participated_auctions_perc
     * @param float $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsPercGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_perc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.participated_auctions_perc
     * @param float $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsPercGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_perc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.participated_auctions_perc
     * @param float $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsPercLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_perc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.participated_auctions_perc
     * @param float $filterValue
     * @return static
     */
    public function filterParticipatedAuctionsPercLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_auctions_perc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.auctions_won_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionsWonNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auctions_won_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.auctions_won_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionsWonNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auctions_won_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.auctions_won_num
     * @return static
     */
    public function groupByAuctionsWonNum(): static
    {
        $this->group($this->alias . '.auctions_won_num');
        return $this;
    }

    /**
     * Order by user_account_stats.auctions_won_num
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionsWonNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.auctions_won_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.auctions_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionsWonNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.auctions_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionsWonNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.auctions_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionsWonNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.auctions_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionsWonNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.auctions_won_perc
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAuctionsWonPerc(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auctions_won_perc', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.auctions_won_perc from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAuctionsWonPerc(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auctions_won_perc', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.auctions_won_perc
     * @return static
     */
    public function groupByAuctionsWonPerc(): static
    {
        $this->group($this->alias . '.auctions_won_perc');
        return $this;
    }

    /**
     * Order by user_account_stats.auctions_won_perc
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionsWonPerc(bool $ascending = true): static
    {
        $this->order($this->alias . '.auctions_won_perc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.auctions_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterAuctionsWonPercGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_perc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.auctions_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterAuctionsWonPercGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_perc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.auctions_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterAuctionsWonPercLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_perc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.auctions_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterAuctionsWonPercLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctions_won_perc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.lots_bid_on_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsBidOnNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_bid_on_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.lots_bid_on_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsBidOnNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_bid_on_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.lots_bid_on_num
     * @return static
     */
    public function groupByLotsBidOnNum(): static
    {
        $this->group($this->alias . '.lots_bid_on_num');
        return $this;
    }

    /**
     * Order by user_account_stats.lots_bid_on_num
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsBidOnNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_bid_on_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.lots_bid_on_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsBidOnNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.lots_bid_on_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsBidOnNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.lots_bid_on_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsBidOnNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.lots_bid_on_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsBidOnNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.lots_won_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsWonNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_won_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.lots_won_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsWonNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_won_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.lots_won_num
     * @return static
     */
    public function groupByLotsWonNum(): static
    {
        $this->group($this->alias . '.lots_won_num');
        return $this;
    }

    /**
     * Order by user_account_stats.lots_won_num
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsWonNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_won_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.lots_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWonNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.lots_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWonNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.lots_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWonNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.lots_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWonNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.lots_won_perc
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLotsWonPerc(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_won_perc', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.lots_won_perc from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLotsWonPerc(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_won_perc', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.lots_won_perc
     * @return static
     */
    public function groupByLotsWonPerc(): static
    {
        $this->group($this->alias . '.lots_won_perc');
        return $this;
    }

    /**
     * Order by user_account_stats.lots_won_perc
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsWonPerc(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_won_perc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.lots_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonPercGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_perc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.lots_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonPercGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_perc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.lots_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonPercLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_perc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.lots_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonPercLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_perc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.lots_consigned_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsConsignedNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_consigned_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.lots_consigned_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsConsignedNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_consigned_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.lots_consigned_num
     * @return static
     */
    public function groupByLotsConsignedNum(): static
    {
        $this->group($this->alias . '.lots_consigned_num');
        return $this;
    }

    /**
     * Order by user_account_stats.lots_consigned_num
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsConsignedNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_consigned_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.lots_consigned_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.lots_consigned_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.lots_consigned_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.lots_consigned_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.lots_consigned_sold_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_consigned_sold_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.lots_consigned_sold_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsConsignedSoldNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_consigned_sold_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.lots_consigned_sold_num
     * @return static
     */
    public function groupByLotsConsignedSoldNum(): static
    {
        $this->group($this->alias . '.lots_consigned_sold_num');
        return $this;
    }

    /**
     * Order by user_account_stats.lots_consigned_sold_num
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsConsignedSoldNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_consigned_sold_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.lots_consigned_sold_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.lots_consigned_sold_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.lots_consigned_sold_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.lots_consigned_sold_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.watchlist_items_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.watchlist_items_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.watchlist_items_num
     * @return static
     */
    public function groupByWatchlistItemsNum(): static
    {
        $this->group($this->alias . '.watchlist_items_num');
        return $this;
    }

    /**
     * Order by user_account_stats.watchlist_items_num
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.watchlist_items_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.watchlist_items_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.watchlist_items_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.watchlist_items_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.watchlist_items_won_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_won_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.watchlist_items_won_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsWonNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_won_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.watchlist_items_won_num
     * @return static
     */
    public function groupByWatchlistItemsWonNum(): static
    {
        $this->group($this->alias . '.watchlist_items_won_num');
        return $this;
    }

    /**
     * Order by user_account_stats.watchlist_items_won_num
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsWonNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_won_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.watchlist_items_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.watchlist_items_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.watchlist_items_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.watchlist_items_won_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.watchlist_items_won_perc
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonPerc(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_won_perc', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.watchlist_items_won_perc from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsWonPerc(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_won_perc', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.watchlist_items_won_perc
     * @return static
     */
    public function groupByWatchlistItemsWonPerc(): static
    {
        $this->group($this->alias . '.watchlist_items_won_perc');
        return $this;
    }

    /**
     * Order by user_account_stats.watchlist_items_won_perc
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsWonPerc(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_won_perc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.watchlist_items_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonPercGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_perc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.watchlist_items_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonPercGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_perc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.watchlist_items_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonPercLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_perc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.watchlist_items_won_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonPercLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_perc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.watchlist_items_bid_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_bid_num', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.watchlist_items_bid_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsBidNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_bid_num', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.watchlist_items_bid_num
     * @return static
     */
    public function groupByWatchlistItemsBidNum(): static
    {
        $this->group($this->alias . '.watchlist_items_bid_num');
        return $this;
    }

    /**
     * Order by user_account_stats.watchlist_items_bid_num
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsBidNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_bid_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.watchlist_items_bid_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.watchlist_items_bid_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.watchlist_items_bid_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.watchlist_items_bid_num
     * @param int $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.watchlist_items_bid_perc
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidPerc(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_bid_perc', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.watchlist_items_bid_perc from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsBidPerc(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_bid_perc', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.watchlist_items_bid_perc
     * @return static
     */
    public function groupByWatchlistItemsBidPerc(): static
    {
        $this->group($this->alias . '.watchlist_items_bid_perc');
        return $this;
    }

    /**
     * Order by user_account_stats.watchlist_items_bid_perc
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsBidPerc(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_bid_perc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.watchlist_items_bid_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidPercGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_perc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.watchlist_items_bid_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidPercGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_perc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.watchlist_items_bid_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidPercLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_perc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.watchlist_items_bid_perc
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidPercLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_perc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.last_date_logged_in
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastDateLoggedIn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_date_logged_in', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.last_date_logged_in from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastDateLoggedIn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_date_logged_in', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.last_date_logged_in
     * @return static
     */
    public function groupByLastDateLoggedIn(): static
    {
        $this->group($this->alias . '.last_date_logged_in');
        return $this;
    }

    /**
     * Order by user_account_stats.last_date_logged_in
     * @param bool $ascending
     * @return static
     */
    public function orderByLastDateLoggedIn(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_date_logged_in', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.last_date_logged_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateLoggedInGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_logged_in', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.last_date_logged_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateLoggedInGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_logged_in', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.last_date_logged_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateLoggedInLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_logged_in', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.last_date_logged_in
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateLoggedInLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_logged_in', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.last_date_auction_registered
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastDateAuctionRegistered(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_date_auction_registered', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.last_date_auction_registered from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastDateAuctionRegistered(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_date_auction_registered', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.last_date_auction_registered
     * @return static
     */
    public function groupByLastDateAuctionRegistered(): static
    {
        $this->group($this->alias . '.last_date_auction_registered');
        return $this;
    }

    /**
     * Order by user_account_stats.last_date_auction_registered
     * @param bool $ascending
     * @return static
     */
    public function orderByLastDateAuctionRegistered(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_date_auction_registered', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.last_date_auction_registered
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionRegisteredGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_registered', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.last_date_auction_registered
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionRegisteredGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_registered', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.last_date_auction_registered
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionRegisteredLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_registered', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.last_date_auction_registered
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionRegisteredLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_registered', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.last_date_auction_approved
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastDateAuctionApproved(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_date_auction_approved', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.last_date_auction_approved from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastDateAuctionApproved(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_date_auction_approved', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.last_date_auction_approved
     * @return static
     */
    public function groupByLastDateAuctionApproved(): static
    {
        $this->group($this->alias . '.last_date_auction_approved');
        return $this;
    }

    /**
     * Order by user_account_stats.last_date_auction_approved
     * @param bool $ascending
     * @return static
     */
    public function orderByLastDateAuctionApproved(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_date_auction_approved', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.last_date_auction_approved
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionApprovedGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_approved', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.last_date_auction_approved
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionApprovedGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_approved', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.last_date_auction_approved
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionApprovedLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_approved', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.last_date_auction_approved
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateAuctionApprovedLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_auction_approved', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.last_date_bid
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastDateBid(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_date_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.last_date_bid from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastDateBid(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_date_bid', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.last_date_bid
     * @return static
     */
    public function groupByLastDateBid(): static
    {
        $this->group($this->alias . '.last_date_bid');
        return $this;
    }

    /**
     * Order by user_account_stats.last_date_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByLastDateBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_date_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.last_date_bid
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateBidGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.last_date_bid
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateBidGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.last_date_bid
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateBidLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.last_date_bid
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateBidLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.last_date_won
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLastDateWon(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.last_date_won', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.last_date_won from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLastDateWon(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.last_date_won', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.last_date_won
     * @return static
     */
    public function groupByLastDateWon(): static
    {
        $this->group($this->alias . '.last_date_won');
        return $this;
    }

    /**
     * Order by user_account_stats.last_date_won
     * @param bool $ascending
     * @return static
     */
    public function orderByLastDateWon(bool $ascending = true): static
    {
        $this->order($this->alias . '.last_date_won', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.last_date_won
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateWonGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_won', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.last_date_won
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateWonGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_won', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.last_date_won
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateWonLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_won', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.last_date_won
     * @param string $filterValue
     * @return static
     */
    public function filterLastDateWonLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.last_date_won', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_account_stats.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.purchased_category
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPurchasedCategory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.purchased_category', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.purchased_category from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPurchasedCategory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.purchased_category', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.purchased_category
     * @return static
     */
    public function groupByPurchasedCategory(): static
    {
        $this->group($this->alias . '.purchased_category');
        return $this;
    }

    /**
     * Order by user_account_stats.purchased_category
     * @param bool $ascending
     * @return static
     */
    public function orderByPurchasedCategory(bool $ascending = true): static
    {
        $this->order($this->alias . '.purchased_category', $ascending);
        return $this;
    }

    /**
     * Filter user_account_stats.purchased_category by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePurchasedCategory(string $filterValue): static
    {
        $this->like($this->alias . '.purchased_category', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_account_stats.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_account_stats.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_account_stats.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_account_stats.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_account_stats.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.calculated_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterCalculatedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.calculated_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.calculated_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipCalculatedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.calculated_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.calculated_on
     * @return static
     */
    public function groupByCalculatedOn(): static
    {
        $this->group($this->alias . '.calculated_on');
        return $this;
    }

    /**
     * Order by user_account_stats.calculated_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCalculatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.calculated_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats.expired_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterExpiredOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.expired_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats.expired_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipExpiredOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.expired_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats.expired_on
     * @return static
     */
    public function groupByExpiredOn(): static
    {
        $this->group($this->alias . '.expired_on');
        return $this;
    }

    /**
     * Order by user_account_stats.expired_on
     * @param bool $ascending
     * @return static
     */
    public function orderByExpiredOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.expired_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats.expired_on
     * @param string $filterValue
     * @return static
     */
    public function filterExpiredOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.expired_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats.expired_on
     * @param string $filterValue
     * @return static
     */
    public function filterExpiredOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.expired_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats.expired_on
     * @param string $filterValue
     * @return static
     */
    public function filterExpiredOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.expired_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats.expired_on
     * @param string $filterValue
     * @return static
     */
    public function filterExpiredOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.expired_on', $filterValue, '<=');
        return $this;
    }
}
