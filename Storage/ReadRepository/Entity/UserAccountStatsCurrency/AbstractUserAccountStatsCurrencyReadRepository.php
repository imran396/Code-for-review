<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserAccountStatsCurrency;

/**
 * Abstract class AbstractUserAccountStatsCurrencyReadRepository
 * @method UserAccountStatsCurrency[] loadEntities()
 * @method UserAccountStatsCurrency|null loadEntity()
 */
abstract class AbstractUserAccountStatsCurrencyReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_ACCOUNT_STATS_CURRENCY;
    protected string $alias = Db::A_USER_ACCOUNT_STATS_CURRENCY;

    /**
     * Filter by user_account_stats_currency.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.currency_sign
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCurrencySign(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.currency_sign', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.currency_sign from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCurrencySign(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.currency_sign', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.currency_sign
     * @return static
     */
    public function groupByCurrencySign(): static
    {
        $this->group($this->alias . '.currency_sign');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.currency_sign
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrencySign(bool $ascending = true): static
    {
        $this->order($this->alias . '.currency_sign', $ascending);
        return $this;
    }

    /**
     * Filter user_account_stats_currency.currency_sign by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCurrencySign(string $filterValue): static
    {
        $this->like($this->alias . '.currency_sign', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.lots_bid_on_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLotsBidOnAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_bid_on_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.lots_bid_on_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLotsBidOnAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_bid_on_amt', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.lots_bid_on_amt
     * @return static
     */
    public function groupByLotsBidOnAmt(): static
    {
        $this->group($this->alias . '.lots_bid_on_amt');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.lots_bid_on_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsBidOnAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_bid_on_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.lots_bid_on_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsBidOnAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.lots_bid_on_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsBidOnAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.lots_bid_on_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsBidOnAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.lots_bid_on_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsBidOnAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_bid_on_amt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.lots_won_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLotsWonAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_won_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.lots_won_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLotsWonAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_won_amt', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.lots_won_amt
     * @return static
     */
    public function groupByLotsWonAmt(): static
    {
        $this->group($this->alias . '.lots_won_amt');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.lots_won_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsWonAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_won_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.lots_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.lots_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.lots_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.lots_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsWonAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_won_amt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.lots_consigned_sold_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_consigned_sold_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.lots_consigned_sold_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLotsConsignedSoldAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_consigned_sold_amt', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.lots_consigned_sold_amt
     * @return static
     */
    public function groupByLotsConsignedSoldAmt(): static
    {
        $this->group($this->alias . '.lots_consigned_sold_amt');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.lots_consigned_sold_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsConsignedSoldAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_consigned_sold_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.lots_consigned_sold_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.lots_consigned_sold_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.lots_consigned_sold_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.lots_consigned_sold_amt
     * @param float $filterValue
     * @return static
     */
    public function filterLotsConsignedSoldAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_consigned_sold_amt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.watchlist_items_won_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_won_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.watchlist_items_won_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsWonAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_won_amt', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.watchlist_items_won_amt
     * @return static
     */
    public function groupByWatchlistItemsWonAmt(): static
    {
        $this->group($this->alias . '.watchlist_items_won_amt');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.watchlist_items_won_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsWonAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_won_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.watchlist_items_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.watchlist_items_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.watchlist_items_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.watchlist_items_won_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsWonAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_won_amt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.watchlist_items_bid_amt
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidAmt(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.watchlist_items_bid_amt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.watchlist_items_bid_amt from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipWatchlistItemsBidAmt(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.watchlist_items_bid_amt', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.watchlist_items_bid_amt
     * @return static
     */
    public function groupByWatchlistItemsBidAmt(): static
    {
        $this->group($this->alias . '.watchlist_items_bid_amt');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.watchlist_items_bid_amt
     * @param bool $ascending
     * @return static
     */
    public function orderByWatchlistItemsBidAmt(bool $ascending = true): static
    {
        $this->order($this->alias . '.watchlist_items_bid_amt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.watchlist_items_bid_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidAmtGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_amt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.watchlist_items_bid_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidAmtGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_amt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.watchlist_items_bid_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidAmtLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_amt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.watchlist_items_bid_amt
     * @param float $filterValue
     * @return static
     */
    public function filterWatchlistItemsBidAmtLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.watchlist_items_bid_amt', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_account_stats_currency.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_account_stats_currency.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_account_stats_currency.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_account_stats_currency.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_account_stats_currency.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_account_stats_currency.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_account_stats_currency.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_account_stats_currency.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
