<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyersPremiumRange;

use BuyersPremiumRange;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractBuyersPremiumRangeReadRepository
 * @method BuyersPremiumRange[] loadEntities()
 * @method BuyersPremiumRange|null loadEntity()
 */
abstract class AbstractBuyersPremiumRangeReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_BUYERS_PREMIUM_RANGE;
    protected string $alias = Db::A_BUYERS_PREMIUM_RANGE;

    /**
     * Filter by buyers_premium_range.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.buyers_premium_id
     * @return static
     */
    public function groupByBuyersPremiumId(): static
    {
        $this->group($this->alias . '.buyers_premium_id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.buyers_premium_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremiumId(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.auction_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.auction_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_type', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.auction_type
     * @return static
     */
    public function groupByAuctionType(): static
    {
        $this->group($this->alias . '.auction_type');
        return $this;
    }

    /**
     * Order by buyers_premium_range.auction_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_type', $ascending);
        return $this;
    }

    /**
     * Filter buyers_premium_range.auction_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionType(string $filterValue): static
    {
        $this->like($this->alias . '.auction_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by buyers_premium_range.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by buyers_premium_range.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by buyers_premium_range.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.fixed
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterFixed(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fixed', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.fixed from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipFixed(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fixed', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.fixed
     * @return static
     */
    public function groupByFixed(): static
    {
        $this->group($this->alias . '.fixed');
        return $this;
    }

    /**
     * Order by buyers_premium_range.fixed
     * @param bool $ascending
     * @return static
     */
    public function orderByFixed(bool $ascending = true): static
    {
        $this->order($this->alias . '.fixed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.fixed
     * @param float $filterValue
     * @return static
     */
    public function filterFixedLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.fixed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.percent
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterPercent(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.percent', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.percent from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipPercent(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.percent', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.percent
     * @return static
     */
    public function groupByPercent(): static
    {
        $this->group($this->alias . '.percent');
        return $this;
    }

    /**
     * Order by buyers_premium_range.percent
     * @param bool $ascending
     * @return static
     */
    public function orderByPercent(bool $ascending = true): static
    {
        $this->order($this->alias . '.percent', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.percent
     * @param float $filterValue
     * @return static
     */
    public function filterPercentLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.percent', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.mode
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterMode(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.mode', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.mode from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipMode(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.mode', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.mode
     * @return static
     */
    public function groupByMode(): static
    {
        $this->group($this->alias . '.mode');
        return $this;
    }

    /**
     * Order by buyers_premium_range.mode
     * @param bool $ascending
     * @return static
     */
    public function orderByMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.mode', $ascending);
        return $this;
    }

    /**
     * Filter by buyers_premium_range.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by buyers_premium_range.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by buyers_premium_range.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by buyers_premium_range.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by buyers_premium_range.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by buyers_premium_range.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyers_premium_range.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by buyers_premium_range.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by buyers_premium_range.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than buyers_premium_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than buyers_premium_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than buyers_premium_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than buyers_premium_range.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }
}
