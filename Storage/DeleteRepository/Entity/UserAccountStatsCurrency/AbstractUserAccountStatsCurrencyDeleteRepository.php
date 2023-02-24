<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAccountStatsCurrency;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserAccountStatsCurrencyDeleteRepository extends DeleteRepositoryBase
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
}
