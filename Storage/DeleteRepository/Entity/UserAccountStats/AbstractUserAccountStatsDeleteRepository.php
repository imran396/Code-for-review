<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAccountStats;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserAccountStatsDeleteRepository extends DeleteRepositoryBase
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
}
