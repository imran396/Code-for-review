<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAuction;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingAuction;

/**
 * Abstract class AbstractSettingAuctionReadRepository
 * @method SettingAuction[] loadEntities()
 * @method SettingAuction|null loadEntity()
 */
abstract class AbstractSettingAuctionReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_AUCTION;
    protected string $alias = Db::A_SETTING_AUCTION;

    /**
     * Filter by setting_auction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_auction.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_auction.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.lot_status
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStatus(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_status', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.lot_status from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStatus(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_status', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.lot_status
     * @return static
     */
    public function groupByLotStatus(): static
    {
        $this->group($this->alias . '.lot_status');
        return $this;
    }

    /**
     * Order by setting_auction.lot_status
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStatus(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_status', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.lot_status
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.lot_status
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.lot_status
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.lot_status
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.auction_links_to
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionLinksTo(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_links_to', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.auction_links_to from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionLinksTo(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_links_to', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.auction_links_to
     * @return static
     */
    public function groupByAuctionLinksTo(): static
    {
        $this->group($this->alias . '.auction_links_to');
        return $this;
    }

    /**
     * Order by setting_auction.auction_links_to
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionLinksTo(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_links_to', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.auction_links_to
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLinksToGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_links_to', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.auction_links_to
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLinksToGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_links_to', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.auction_links_to
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLinksToLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_links_to', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.auction_links_to
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLinksToLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_links_to', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.enable_second_chance
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableSecondChance(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_second_chance', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.enable_second_chance from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableSecondChance(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_second_chance', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.enable_second_chance
     * @return static
     */
    public function groupByEnableSecondChance(): static
    {
        $this->group($this->alias . '.enable_second_chance');
        return $this;
    }

    /**
     * Order by setting_auction.enable_second_chance
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableSecondChance(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_second_chance', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.enable_second_chance
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSecondChanceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_second_chance', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.enable_second_chance
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSecondChanceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_second_chance', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.enable_second_chance
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSecondChanceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_second_chance', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.enable_second_chance
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableSecondChanceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_second_chance', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.show_low_est
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowLowEst(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_low_est', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_low_est from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowLowEst(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_low_est', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.show_low_est
     * @return static
     */
    public function groupByShowLowEst(): static
    {
        $this->group($this->alias . '.show_low_est');
        return $this;
    }

    /**
     * Order by setting_auction.show_low_est
     * @param bool $ascending
     * @return static
     */
    public function orderByShowLowEst(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_low_est', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.show_low_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowLowEstGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_low_est', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.show_low_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowLowEstGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_low_est', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.show_low_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowLowEstLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_low_est', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.show_low_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowLowEstLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_low_est', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.show_high_est
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowHighEst(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_high_est', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_high_est from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowHighEst(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_high_est', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.show_high_est
     * @return static
     */
    public function groupByShowHighEst(): static
    {
        $this->group($this->alias . '.show_high_est');
        return $this;
    }

    /**
     * Order by setting_auction.show_high_est
     * @param bool $ascending
     * @return static
     */
    public function orderByShowHighEst(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_high_est', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.show_high_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowHighEstGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_high_est', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.show_high_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowHighEstGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_high_est', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.show_high_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowHighEstLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_high_est', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.show_high_est
     * @param bool $filterValue
     * @return static
     */
    public function filterShowHighEstLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_high_est', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_timed_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmTimedBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_timed_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_timed_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmTimedBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_timed_bid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_timed_bid
     * @return static
     */
    public function groupByConfirmTimedBid(): static
    {
        $this->group($this->alias . '.confirm_timed_bid');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_timed_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmTimedBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_timed_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.confirm_timed_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTimedBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_timed_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.confirm_timed_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTimedBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_timed_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.confirm_timed_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTimedBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_timed_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.confirm_timed_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTimedBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_timed_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_timed_bid_text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterConfirmTimedBidText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_timed_bid_text', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_timed_bid_text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipConfirmTimedBidText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_timed_bid_text', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_timed_bid_text
     * @return static
     */
    public function groupByConfirmTimedBidText(): static
    {
        $this->group($this->alias . '.confirm_timed_bid_text');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_timed_bid_text
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmTimedBidText(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_timed_bid_text', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.confirm_timed_bid_text by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeConfirmTimedBidText(string $filterValue): static
    {
        $this->like($this->alias . '.confirm_timed_bid_text', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.allow_multibids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowMultibids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_multibids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_multibids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowMultibids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_multibids', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.allow_multibids
     * @return static
     */
    public function groupByAllowMultibids(): static
    {
        $this->group($this->alias . '.allow_multibids');
        return $this;
    }

    /**
     * Order by setting_auction.allow_multibids
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowMultibids(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_multibids', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.allow_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowMultibidsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_multibids', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.allow_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowMultibidsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_multibids', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.allow_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowMultibidsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_multibids', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.allow_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowMultibidsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_multibids', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_multibids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmMultibids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_multibids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_multibids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmMultibids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_multibids', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_multibids
     * @return static
     */
    public function groupByConfirmMultibids(): static
    {
        $this->group($this->alias . '.confirm_multibids');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_multibids
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmMultibids(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_multibids', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.confirm_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmMultibidsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_multibids', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.confirm_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmMultibidsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_multibids', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.confirm_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmMultibidsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_multibids', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.confirm_multibids
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmMultibidsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_multibids', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_multibids_text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterConfirmMultibidsText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_multibids_text', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_multibids_text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipConfirmMultibidsText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_multibids_text', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_multibids_text
     * @return static
     */
    public function groupByConfirmMultibidsText(): static
    {
        $this->group($this->alias . '.confirm_multibids_text');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_multibids_text
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmMultibidsText(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_multibids_text', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.confirm_multibids_text by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeConfirmMultibidsText(string $filterValue): static
    {
        $this->like($this->alias . '.confirm_multibids_text', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.hide_bidder_number
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideBidderNumber(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_bidder_number', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_bidder_number from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideBidderNumber(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_bidder_number', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.hide_bidder_number
     * @return static
     */
    public function groupByHideBidderNumber(): static
    {
        $this->group($this->alias . '.hide_bidder_number');
        return $this;
    }

    /**
     * Order by setting_auction.hide_bidder_number
     * @param bool $ascending
     * @return static
     */
    public function orderByHideBidderNumber(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_bidder_number', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.hide_bidder_number
     * @param bool $filterValue
     * @return static
     */
    public function filterHideBidderNumberGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_bidder_number', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.hide_bidder_number
     * @param bool $filterValue
     * @return static
     */
    public function filterHideBidderNumberGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_bidder_number', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.hide_bidder_number
     * @param bool $filterValue
     * @return static
     */
    public function filterHideBidderNumberLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_bidder_number', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.hide_bidder_number
     * @param bool $filterValue
     * @return static
     */
    public function filterHideBidderNumberLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_bidder_number', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.tell_a_friend
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTellAFriend(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tell_a_friend', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.tell_a_friend from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTellAFriend(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tell_a_friend', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.tell_a_friend
     * @return static
     */
    public function groupByTellAFriend(): static
    {
        $this->group($this->alias . '.tell_a_friend');
        return $this;
    }

    /**
     * Order by setting_auction.tell_a_friend
     * @param bool $ascending
     * @return static
     */
    public function orderByTellAFriend(bool $ascending = true): static
    {
        $this->order($this->alias . '.tell_a_friend', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterTellAFriendGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tell_a_friend', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterTellAFriendGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tell_a_friend', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterTellAFriendLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tell_a_friend', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterTellAFriendLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.tell_a_friend', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.hide_movetosale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideMovetosale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_movetosale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_movetosale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideMovetosale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_movetosale', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.hide_movetosale
     * @return static
     */
    public function groupByHideMovetosale(): static
    {
        $this->group($this->alias . '.hide_movetosale');
        return $this;
    }

    /**
     * Order by setting_auction.hide_movetosale
     * @param bool $ascending
     * @return static
     */
    public function orderByHideMovetosale(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_movetosale', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.hide_movetosale
     * @param bool $filterValue
     * @return static
     */
    public function filterHideMovetosaleGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_movetosale', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.hide_movetosale
     * @param bool $filterValue
     * @return static
     */
    public function filterHideMovetosaleGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_movetosale', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.hide_movetosale
     * @param bool $filterValue
     * @return static
     */
    public function filterHideMovetosaleLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_movetosale', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.hide_movetosale
     * @param bool $filterValue
     * @return static
     */
    public function filterHideMovetosaleLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_movetosale', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.shipping_info_box
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterShippingInfoBox(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_info_box', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.shipping_info_box from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipShippingInfoBox(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_info_box', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.shipping_info_box
     * @return static
     */
    public function groupByShippingInfoBox(): static
    {
        $this->group($this->alias . '.shipping_info_box');
        return $this;
    }

    /**
     * Order by setting_auction.shipping_info_box
     * @param bool $ascending
     * @return static
     */
    public function orderByShippingInfoBox(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping_info_box', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.shipping_info_box
     * @param int $filterValue
     * @return static
     */
    public function filterShippingInfoBoxGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_info_box', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.shipping_info_box
     * @param int $filterValue
     * @return static
     */
    public function filterShippingInfoBoxGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_info_box', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.shipping_info_box
     * @param int $filterValue
     * @return static
     */
    public function filterShippingInfoBoxLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_info_box', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.shipping_info_box
     * @param int $filterValue
     * @return static
     */
    public function filterShippingInfoBoxLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_info_box', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.add_bids_to_watchlist
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlist(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.add_bids_to_watchlist', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.add_bids_to_watchlist from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAddBidsToWatchlist(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.add_bids_to_watchlist', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.add_bids_to_watchlist
     * @return static
     */
    public function groupByAddBidsToWatchlist(): static
    {
        $this->group($this->alias . '.add_bids_to_watchlist');
        return $this;
    }

    /**
     * Order by setting_auction.add_bids_to_watchlist
     * @param bool $ascending
     * @return static
     */
    public function orderByAddBidsToWatchlist(bool $ascending = true): static
    {
        $this->order($this->alias . '.add_bids_to_watchlist', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.add_bids_to_watchlist
     * @param bool $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlistGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_bids_to_watchlist', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.add_bids_to_watchlist
     * @param bool $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlistGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_bids_to_watchlist', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.add_bids_to_watchlist
     * @param bool $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlistLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_bids_to_watchlist', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.add_bids_to_watchlist
     * @param bool $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlistLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_bids_to_watchlist', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.max_stored_searches
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMaxStoredSearches(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_stored_searches', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.max_stored_searches from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMaxStoredSearches(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_stored_searches', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.max_stored_searches
     * @return static
     */
    public function groupByMaxStoredSearches(): static
    {
        $this->group($this->alias . '.max_stored_searches');
        return $this;
    }

    /**
     * Order by setting_auction.max_stored_searches
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxStoredSearches(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_stored_searches', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.max_stored_searches
     * @param int $filterValue
     * @return static
     */
    public function filterMaxStoredSearchesGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_stored_searches', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.max_stored_searches
     * @param int $filterValue
     * @return static
     */
    public function filterMaxStoredSearchesGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_stored_searches', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.max_stored_searches
     * @param int $filterValue
     * @return static
     */
    public function filterMaxStoredSearchesLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_stored_searches', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.max_stored_searches
     * @param int $filterValue
     * @return static
     */
    public function filterMaxStoredSearchesLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_stored_searches', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.send_results_once
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendResultsOnce(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_results_once', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.send_results_once from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendResultsOnce(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_results_once', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.send_results_once
     * @return static
     */
    public function groupBySendResultsOnce(): static
    {
        $this->group($this->alias . '.send_results_once');
        return $this;
    }

    /**
     * Order by setting_auction.send_results_once
     * @param bool $ascending
     * @return static
     */
    public function orderBySendResultsOnce(bool $ascending = true): static
    {
        $this->order($this->alias . '.send_results_once', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.send_results_once
     * @param bool $filterValue
     * @return static
     */
    public function filterSendResultsOnceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_results_once', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.send_results_once
     * @param bool $filterValue
     * @return static
     */
    public function filterSendResultsOnceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_results_once', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.send_results_once
     * @param bool $filterValue
     * @return static
     */
    public function filterSendResultsOnceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_results_once', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.send_results_once
     * @param bool $filterValue
     * @return static
     */
    public function filterSendResultsOnceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_results_once', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.allow_anyone_to_tell_a_friend
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriend(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_anyone_to_tell_a_friend from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowAnyoneToTellAFriend(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_anyone_to_tell_a_friend', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.allow_anyone_to_tell_a_friend
     * @return static
     */
    public function groupByAllowAnyoneToTellAFriend(): static
    {
        $this->group($this->alias . '.allow_anyone_to_tell_a_friend');
        return $this;
    }

    /**
     * Order by setting_auction.allow_anyone_to_tell_a_friend
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowAnyoneToTellAFriend(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_anyone_to_tell_a_friend', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.allow_anyone_to_tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriendGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.allow_anyone_to_tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriendGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.allow_anyone_to_tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriendLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.allow_anyone_to_tell_a_friend
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriendLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.visible_auction_statuses
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatuses(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.visible_auction_statuses', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.visible_auction_statuses from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipVisibleAuctionStatuses(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.visible_auction_statuses', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.visible_auction_statuses
     * @return static
     */
    public function groupByVisibleAuctionStatuses(): static
    {
        $this->group($this->alias . '.visible_auction_statuses');
        return $this;
    }

    /**
     * Order by setting_auction.visible_auction_statuses
     * @param bool $ascending
     * @return static
     */
    public function orderByVisibleAuctionStatuses(bool $ascending = true): static
    {
        $this->order($this->alias . '.visible_auction_statuses', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.visible_auction_statuses
     * @param int $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatusesGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible_auction_statuses', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.visible_auction_statuses
     * @param int $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatusesGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible_auction_statuses', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.visible_auction_statuses
     * @param int $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatusesLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible_auction_statuses', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.visible_auction_statuses
     * @param int $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatusesLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.visible_auction_statuses', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_address_sale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmAddressSale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_address_sale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_address_sale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmAddressSale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_address_sale', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_address_sale
     * @return static
     */
    public function groupByConfirmAddressSale(): static
    {
        $this->group($this->alias . '.confirm_address_sale');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_address_sale
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmAddressSale(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_address_sale', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.confirm_address_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmAddressSaleGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_address_sale', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.confirm_address_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmAddressSaleGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_address_sale', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.confirm_address_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmAddressSaleLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_address_sale', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.confirm_address_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmAddressSaleLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_address_sale', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_terms_and_conditions_sale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_terms_and_conditions_sale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_terms_and_conditions_sale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmTermsAndConditionsSale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_terms_and_conditions_sale', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.confirm_terms_and_conditions_sale
     * @return static
     */
    public function groupByConfirmTermsAndConditionsSale(): static
    {
        $this->group($this->alias . '.confirm_terms_and_conditions_sale');
        return $this;
    }

    /**
     * Order by setting_auction.confirm_terms_and_conditions_sale
     * @param bool $ascending
     * @return static
     */
    public function orderByConfirmTermsAndConditionsSale(bool $ascending = true): static
    {
        $this->order($this->alias . '.confirm_terms_and_conditions_sale', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.confirm_terms_and_conditions_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSaleGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_terms_and_conditions_sale', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.confirm_terms_and_conditions_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSaleGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_terms_and_conditions_sale', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.confirm_terms_and_conditions_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSaleLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_terms_and_conditions_sale', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.confirm_terms_and_conditions_sale
     * @param bool $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSaleLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.confirm_terms_and_conditions_sale', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reg_use_high_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegUseHighBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_use_high_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_use_high_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegUseHighBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_use_high_bidder', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reg_use_high_bidder
     * @return static
     */
    public function groupByRegUseHighBidder(): static
    {
        $this->group($this->alias . '.reg_use_high_bidder');
        return $this;
    }

    /**
     * Order by setting_auction.reg_use_high_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByRegUseHighBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_use_high_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.reg_use_high_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRegUseHighBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_use_high_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.reg_use_high_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRegUseHighBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_use_high_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.reg_use_high_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRegUseHighBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_use_high_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.reg_use_high_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRegUseHighBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_use_high_bidder', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_auto_approve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApprove(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_auto_approve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_auto_approve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegConfirmAutoApprove(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_auto_approve', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reg_confirm_auto_approve
     * @return static
     */
    public function groupByRegConfirmAutoApprove(): static
    {
        $this->group($this->alias . '.reg_confirm_auto_approve');
        return $this;
    }

    /**
     * Order by setting_auction.reg_confirm_auto_approve
     * @param bool $ascending
     * @return static
     */
    public function orderByRegConfirmAutoApprove(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_confirm_auto_approve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.reg_confirm_auto_approve
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApproveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_auto_approve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.reg_confirm_auto_approve
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApproveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_auto_approve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.reg_confirm_auto_approve
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApproveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_auto_approve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.reg_confirm_auto_approve
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApproveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_auto_approve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_page
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegConfirmPage(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_page from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegConfirmPage(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_page', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reg_confirm_page
     * @return static
     */
    public function groupByRegConfirmPage(): static
    {
        $this->group($this->alias . '.reg_confirm_page');
        return $this;
    }

    /**
     * Order by setting_auction.reg_confirm_page
     * @param bool $ascending
     * @return static
     */
    public function orderByRegConfirmPage(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_confirm_page', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.reg_confirm_page
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmPageGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_page', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.reg_confirm_page
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmPageGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_page', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.reg_confirm_page
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmPageLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_page', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.reg_confirm_page
     * @param bool $filterValue
     * @return static
     */
    public function filterRegConfirmPageLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_confirm_page', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_page_content
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRegConfirmPageContent(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_page_content', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_page_content from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRegConfirmPageContent(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_page_content', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reg_confirm_page_content
     * @return static
     */
    public function groupByRegConfirmPageContent(): static
    {
        $this->group($this->alias . '.reg_confirm_page_content');
        return $this;
    }

    /**
     * Order by setting_auction.reg_confirm_page_content
     * @param bool $ascending
     * @return static
     */
    public function orderByRegConfirmPageContent(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_confirm_page_content', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.reg_confirm_page_content by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRegConfirmPageContent(string $filterValue): static
    {
        $this->like($this->alias . '.reg_confirm_page_content', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.payment_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaymentTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.payment_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaymentTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.payment_tracking_code
     * @return static
     */
    public function groupByPaymentTrackingCode(): static
    {
        $this->group($this->alias . '.payment_tracking_code');
        return $this;
    }

    /**
     * Order by setting_auction.payment_tracking_code
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentTrackingCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_tracking_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.payment_tracking_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaymentTrackingCode(string $filterValue): static
    {
        $this->like($this->alias . '.payment_tracking_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.notify_absentee_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_absentee_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.notify_absentee_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyAbsenteeBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_absentee_bidders', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.notify_absentee_bidders
     * @return static
     */
    public function groupByNotifyAbsenteeBidders(): static
    {
        $this->group($this->alias . '.notify_absentee_bidders');
        return $this;
    }

    /**
     * Order by setting_auction.notify_absentee_bidders
     * @param bool $ascending
     * @return static
     */
    public function orderByNotifyAbsenteeBidders(bool $ascending = true): static
    {
        $this->order($this->alias . '.notify_absentee_bidders', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.use_alternate_pdf_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.use_alternate_pdf_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.use_alternate_pdf_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUseAlternatePdfCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.use_alternate_pdf_catalog', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.use_alternate_pdf_catalog
     * @return static
     */
    public function groupByUseAlternatePdfCatalog(): static
    {
        $this->group($this->alias . '.use_alternate_pdf_catalog');
        return $this;
    }

    /**
     * Order by setting_auction.use_alternate_pdf_catalog
     * @param bool $ascending
     * @return static
     */
    public function orderByUseAlternatePdfCatalog(bool $ascending = true): static
    {
        $this->order($this->alias . '.use_alternate_pdf_catalog', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.use_alternate_pdf_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_alternate_pdf_catalog', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.use_alternate_pdf_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_alternate_pdf_catalog', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.use_alternate_pdf_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_alternate_pdf_catalog', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.use_alternate_pdf_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_alternate_pdf_catalog', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.bid_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.bid_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.bid_tracking_code
     * @return static
     */
    public function groupByBidTrackingCode(): static
    {
        $this->group($this->alias . '.bid_tracking_code');
        return $this;
    }

    /**
     * Order by setting_auction.bid_tracking_code
     * @param bool $ascending
     * @return static
     */
    public function orderByBidTrackingCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_tracking_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.bid_tracking_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidTrackingCode(string $filterValue): static
    {
        $this->like($this->alias . '.bid_tracking_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.display_item_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisplayItemNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_item_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_item_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisplayItemNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_item_num', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.display_item_num
     * @return static
     */
    public function groupByDisplayItemNum(): static
    {
        $this->group($this->alias . '.display_item_num');
        return $this;
    }

    /**
     * Order by setting_auction.display_item_num
     * @param bool $ascending
     * @return static
     */
    public function orderByDisplayItemNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.display_item_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.display_item_num
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayItemNumGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_item_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.display_item_num
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayItemNumGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_item_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.display_item_num
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayItemNumLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_item_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.display_item_num
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayItemNumLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_item_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.absentee_bids_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidsDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bids_display', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.absentee_bids_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidsDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bids_display', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.absentee_bids_display
     * @return static
     */
    public function groupByAbsenteeBidsDisplay(): static
    {
        $this->group($this->alias . '.absentee_bids_display');
        return $this;
    }

    /**
     * Order by setting_auction.absentee_bids_display
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBidsDisplay(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bids_display', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.absentee_bids_display by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAbsenteeBidsDisplay(string $filterValue): static
    {
        $this->like($this->alias . '.absentee_bids_display', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.show_countdown_seconds
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowCountdownSeconds(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_countdown_seconds', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_countdown_seconds from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowCountdownSeconds(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_countdown_seconds', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.show_countdown_seconds
     * @return static
     */
    public function groupByShowCountdownSeconds(): static
    {
        $this->group($this->alias . '.show_countdown_seconds');
        return $this;
    }

    /**
     * Order by setting_auction.show_countdown_seconds
     * @param bool $ascending
     * @return static
     */
    public function orderByShowCountdownSeconds(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_countdown_seconds', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.show_countdown_seconds
     * @param bool $filterValue
     * @return static
     */
    public function filterShowCountdownSecondsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_countdown_seconds', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.show_countdown_seconds
     * @param bool $filterValue
     * @return static
     */
    public function filterShowCountdownSecondsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_countdown_seconds', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.show_countdown_seconds
     * @param bool $filterValue
     * @return static
     */
    public function filterShowCountdownSecondsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_countdown_seconds', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.show_countdown_seconds
     * @param bool $filterValue
     * @return static
     */
    public function filterShowCountdownSecondsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_countdown_seconds', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.above_reserve
     * @return static
     */
    public function groupByAboveReserve(): static
    {
        $this->group($this->alias . '.above_reserve');
        return $this;
    }

    /**
     * Order by setting_auction.above_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByAboveReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.above_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.display_quantity
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisplayQuantity(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_quantity from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisplayQuantity(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_quantity', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.display_quantity
     * @return static
     */
    public function groupByDisplayQuantity(): static
    {
        $this->group($this->alias . '.display_quantity');
        return $this;
    }

    /**
     * Order by setting_auction.display_quantity
     * @param bool $ascending
     * @return static
     */
    public function orderByDisplayQuantity(bool $ascending = true): static
    {
        $this->order($this->alias . '.display_quantity', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.display_quantity
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayQuantityGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_quantity', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.display_quantity
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayQuantityGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_quantity', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.display_quantity
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayQuantityLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_quantity', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.display_quantity
     * @param bool $filterValue
     * @return static
     */
    public function filterDisplayQuantityLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.display_quantity', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.quantity_digits
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.quantity_digits from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.quantity_digits
     * @return static
     */
    public function groupByQuantityDigits(): static
    {
        $this->group($this->alias . '.quantity_digits');
        return $this;
    }

    /**
     * Order by setting_auction.quantity_digits
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityDigits(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_digits', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.hammer_price_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHammerPriceBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hammer_price_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHammerPriceBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price_bp', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.hammer_price_bp
     * @return static
     */
    public function groupByHammerPriceBp(): static
    {
        $this->group($this->alias . '.hammer_price_bp');
        return $this;
    }

    /**
     * Order by setting_auction.hammer_price_bp
     * @param bool $ascending
     * @return static
     */
    public function orderByHammerPriceBp(bool $ascending = true): static
    {
        $this->order($this->alias . '.hammer_price_bp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.hammer_price_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterHammerPriceBpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price_bp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.hammer_price_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterHammerPriceBpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price_bp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.hammer_price_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterHammerPriceBpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price_bp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.hammer_price_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterHammerPriceBpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price_bp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.extend_time_timed
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTimeTimed(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time_timed', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.extend_time_timed from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTimeTimed(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time_timed', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.extend_time_timed
     * @return static
     */
    public function groupByExtendTimeTimed(): static
    {
        $this->group($this->alias . '.extend_time_timed');
        return $this;
    }

    /**
     * Order by setting_auction.extend_time_timed
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendTimeTimed(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_time_timed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.extend_time_timed
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeTimedGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_timed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.extend_time_timed
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeTimedGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_timed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.extend_time_timed
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeTimedLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_timed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.extend_time_timed
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeTimedLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time_timed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.ga_bid_tracking
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterGaBidTracking(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ga_bid_tracking', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.ga_bid_tracking from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipGaBidTracking(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ga_bid_tracking', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.ga_bid_tracking
     * @return static
     */
    public function groupByGaBidTracking(): static
    {
        $this->group($this->alias . '.ga_bid_tracking');
        return $this;
    }

    /**
     * Order by setting_auction.ga_bid_tracking
     * @param bool $ascending
     * @return static
     */
    public function orderByGaBidTracking(bool $ascending = true): static
    {
        $this->order($this->alias . '.ga_bid_tracking', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.ga_bid_tracking
     * @param bool $filterValue
     * @return static
     */
    public function filterGaBidTrackingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ga_bid_tracking', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.ga_bid_tracking
     * @param bool $filterValue
     * @return static
     */
    public function filterGaBidTrackingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ga_bid_tracking', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.ga_bid_tracking
     * @param bool $filterValue
     * @return static
     */
    public function filterGaBidTrackingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ga_bid_tracking', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.ga_bid_tracking
     * @param bool $filterValue
     * @return static
     */
    public function filterGaBidTrackingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.ga_bid_tracking', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.place_bid_require_cc
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCc(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.place_bid_require_cc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.place_bid_require_cc from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPlaceBidRequireCc(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.place_bid_require_cc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.place_bid_require_cc
     * @return static
     */
    public function groupByPlaceBidRequireCc(): static
    {
        $this->group($this->alias . '.place_bid_require_cc');
        return $this;
    }

    /**
     * Order by setting_auction.place_bid_require_cc
     * @param bool $ascending
     * @return static
     */
    public function orderByPlaceBidRequireCc(bool $ascending = true): static
    {
        $this->order($this->alias . '.place_bid_require_cc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.place_bid_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCcGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.place_bid_require_cc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.place_bid_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCcGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.place_bid_require_cc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.place_bid_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCcLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.place_bid_require_cc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.place_bid_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCcLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.place_bid_require_cc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.next_bid_button
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNextBidButton(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.next_bid_button', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.next_bid_button from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNextBidButton(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.next_bid_button', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.next_bid_button
     * @return static
     */
    public function groupByNextBidButton(): static
    {
        $this->group($this->alias . '.next_bid_button');
        return $this;
    }

    /**
     * Order by setting_auction.next_bid_button
     * @param bool $ascending
     * @return static
     */
    public function orderByNextBidButton(bool $ascending = true): static
    {
        $this->order($this->alias . '.next_bid_button', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterOnAuctionRegistration(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipOnAuctionRegistration(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.on_auction_registration
     * @return static
     */
    public function groupByOnAuctionRegistration(): static
    {
        $this->group($this->alias . '.on_auction_registration');
        return $this;
    }

    /**
     * Order by setting_auction.on_auction_registration
     * @param bool $ascending
     * @return static
     */
    public function orderByOnAuctionRegistration(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_auction_registration', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.on_auction_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.on_auction_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.on_auction_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.on_auction_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_amount', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.on_auction_registration_amount
     * @return static
     */
    public function groupByOnAuctionRegistrationAmount(): static
    {
        $this->group($this->alias . '.on_auction_registration_amount');
        return $this;
    }

    /**
     * Order by setting_auction.on_auction_registration_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByOnAuctionRegistrationAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_auction_registration_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.on_auction_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.on_auction_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.on_auction_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.on_auction_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_expires
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpires(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_expires', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_expires from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationExpires(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_expires', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.on_auction_registration_expires
     * @return static
     */
    public function groupByOnAuctionRegistrationExpires(): static
    {
        $this->group($this->alias . '.on_auction_registration_expires');
        return $this;
    }

    /**
     * Order by setting_auction.on_auction_registration_expires
     * @param bool $ascending
     * @return static
     */
    public function orderByOnAuctionRegistrationExpires(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_auction_registration_expires', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.on_auction_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpiresGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_expires', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.on_auction_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpiresGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_expires', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.on_auction_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpiresLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_expires', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.on_auction_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpiresLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_expires', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_auto
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAuto(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_auto', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_auto from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationAuto(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_auto', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.on_auction_registration_auto
     * @return static
     */
    public function groupByOnAuctionRegistrationAuto(): static
    {
        $this->group($this->alias . '.on_auction_registration_auto');
        return $this;
    }

    /**
     * Order by setting_auction.on_auction_registration_auto
     * @param bool $ascending
     * @return static
     */
    public function orderByOnAuctionRegistrationAuto(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_auction_registration_auto', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.on_auction_registration_auto
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAutoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_auto', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.on_auction_registration_auto
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAutoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_auto', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.on_auction_registration_auto
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAutoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_auto', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.on_auction_registration_auto
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAutoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_auction_registration_auto', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.require_reenter_cc
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireReenterCc(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_reenter_cc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.require_reenter_cc from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireReenterCc(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_reenter_cc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.require_reenter_cc
     * @return static
     */
    public function groupByRequireReenterCc(): static
    {
        $this->group($this->alias . '.require_reenter_cc');
        return $this;
    }

    /**
     * Order by setting_auction.require_reenter_cc
     * @param bool $ascending
     * @return static
     */
    public function orderByRequireReenterCc(bool $ascending = true): static
    {
        $this->order($this->alias . '.require_reenter_cc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.require_reenter_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireReenterCcGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_reenter_cc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.require_reenter_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireReenterCcGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_reenter_cc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.require_reenter_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireReenterCcLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_reenter_cc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.require_reenter_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireReenterCcLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_reenter_cc', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.display_bidder_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDisplayBidderInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_bidder_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_bidder_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDisplayBidderInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_bidder_info', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.display_bidder_info
     * @return static
     */
    public function groupByDisplayBidderInfo(): static
    {
        $this->group($this->alias . '.display_bidder_info');
        return $this;
    }

    /**
     * Order by setting_auction.display_bidder_info
     * @param bool $ascending
     * @return static
     */
    public function orderByDisplayBidderInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.display_bidder_info', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.display_bidder_info by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDisplayBidderInfo(string $filterValue): static
    {
        $this->like($this->alias . '.display_bidder_info', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.all_user_require_cc_auth
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuth(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.all_user_require_cc_auth', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.all_user_require_cc_auth from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllUserRequireCcAuth(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.all_user_require_cc_auth', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.all_user_require_cc_auth
     * @return static
     */
    public function groupByAllUserRequireCcAuth(): static
    {
        $this->group($this->alias . '.all_user_require_cc_auth');
        return $this;
    }

    /**
     * Order by setting_auction.all_user_require_cc_auth
     * @param bool $ascending
     * @return static
     */
    public function orderByAllUserRequireCcAuth(bool $ascending = true): static
    {
        $this->order($this->alias . '.all_user_require_cc_auth', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.all_user_require_cc_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuthGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.all_user_require_cc_auth', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.all_user_require_cc_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuthGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.all_user_require_cc_auth', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.all_user_require_cc_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuthLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.all_user_require_cc_auth', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.all_user_require_cc_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuthLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.all_user_require_cc_auth', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reserve_not_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveNotMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_not_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reserve_not_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveNotMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_not_met_notice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reserve_not_met_notice
     * @return static
     */
    public function groupByReserveNotMetNotice(): static
    {
        $this->group($this->alias . '.reserve_not_met_notice');
        return $this;
    }

    /**
     * Order by setting_auction.reserve_not_met_notice
     * @param bool $ascending
     * @return static
     */
    public function orderByReserveNotMetNotice(bool $ascending = true): static
    {
        $this->order($this->alias . '.reserve_not_met_notice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.reserve_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reserve_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_met_notice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.reserve_met_notice
     * @return static
     */
    public function groupByReserveMetNotice(): static
    {
        $this->group($this->alias . '.reserve_met_notice');
        return $this;
    }

    /**
     * Order by setting_auction.reserve_met_notice
     * @param bool $ascending
     * @return static
     */
    public function orderByReserveMetNotice(bool $ascending = true): static
    {
        $this->order($this->alias . '.reserve_met_notice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.absentee_bid_lot_notification
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotification(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid_lot_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.absentee_bid_lot_notification from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidLotNotification(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid_lot_notification', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.absentee_bid_lot_notification
     * @return static
     */
    public function groupByAbsenteeBidLotNotification(): static
    {
        $this->group($this->alias . '.absentee_bid_lot_notification');
        return $this;
    }

    /**
     * Order by setting_auction.absentee_bid_lot_notification
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBidLotNotification(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bid_lot_notification', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.absentee_bid_lot_notification
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotificationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid_lot_notification', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.absentee_bid_lot_notification
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotificationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid_lot_notification', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.absentee_bid_lot_notification
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotificationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid_lot_notification', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.absentee_bid_lot_notification
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotificationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid_lot_notification', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.show_auction_starts_ending
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEnding(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_auction_starts_ending', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_auction_starts_ending from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowAuctionStartsEnding(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_auction_starts_ending', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.show_auction_starts_ending
     * @return static
     */
    public function groupByShowAuctionStartsEnding(): static
    {
        $this->group($this->alias . '.show_auction_starts_ending');
        return $this;
    }

    /**
     * Order by setting_auction.show_auction_starts_ending
     * @param bool $ascending
     * @return static
     */
    public function orderByShowAuctionStartsEnding(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_auction_starts_ending', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.show_auction_starts_ending
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEndingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_auction_starts_ending', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.show_auction_starts_ending
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEndingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_auction_starts_ending', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.show_auction_starts_ending
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEndingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_auction_starts_ending', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.show_auction_starts_ending
     * @param bool $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEndingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_auction_starts_ending', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.assigned_lots_restriction
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAssignedLotsRestriction(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.assigned_lots_restriction', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.assigned_lots_restriction from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAssignedLotsRestriction(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.assigned_lots_restriction', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.assigned_lots_restriction
     * @return static
     */
    public function groupByAssignedLotsRestriction(): static
    {
        $this->group($this->alias . '.assigned_lots_restriction');
        return $this;
    }

    /**
     * Order by setting_auction.assigned_lots_restriction
     * @param bool $ascending
     * @return static
     */
    public function orderByAssignedLotsRestriction(bool $ascending = true): static
    {
        $this->order($this->alias . '.assigned_lots_restriction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.assigned_lots_restriction
     * @param int $filterValue
     * @return static
     */
    public function filterAssignedLotsRestrictionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.assigned_lots_restriction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.assigned_lots_restriction
     * @param int $filterValue
     * @return static
     */
    public function filterAssignedLotsRestrictionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.assigned_lots_restriction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.assigned_lots_restriction
     * @param int $filterValue
     * @return static
     */
    public function filterAssignedLotsRestrictionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.assigned_lots_restriction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.assigned_lots_restriction
     * @param int $filterValue
     * @return static
     */
    public function filterAssignedLotsRestrictionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.assigned_lots_restriction', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.show_winner_in_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_winner_in_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_winner_in_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowWinnerInCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_winner_in_catalog', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.show_winner_in_catalog
     * @return static
     */
    public function groupByShowWinnerInCatalog(): static
    {
        $this->group($this->alias . '.show_winner_in_catalog');
        return $this;
    }

    /**
     * Order by setting_auction.show_winner_in_catalog
     * @param bool $ascending
     * @return static
     */
    public function orderByShowWinnerInCatalog(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_winner_in_catalog', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.show_winner_in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_winner_in_catalog', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.show_winner_in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_winner_in_catalog', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.show_winner_in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_winner_in_catalog', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.show_winner_in_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_winner_in_catalog', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.buy_now_unsold
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowUnsold(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_unsold', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.buy_now_unsold from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowUnsold(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_unsold', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.buy_now_unsold
     * @return static
     */
    public function groupByBuyNowUnsold(): static
    {
        $this->group($this->alias . '.buy_now_unsold');
        return $this;
    }

    /**
     * Order by setting_auction.buy_now_unsold
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowUnsold(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_unsold', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.buy_now_unsold
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowUnsoldGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_unsold', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.buy_now_unsold
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowUnsoldGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_unsold', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.buy_now_unsold
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowUnsoldLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_unsold', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.buy_now_unsold
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowUnsoldLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_unsold', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.no_lower_maxbid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoLowerMaxbid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_lower_maxbid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.no_lower_maxbid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoLowerMaxbid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_lower_maxbid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.no_lower_maxbid
     * @return static
     */
    public function groupByNoLowerMaxbid(): static
    {
        $this->group($this->alias . '.no_lower_maxbid');
        return $this;
    }

    /**
     * Order by setting_auction.no_lower_maxbid
     * @param bool $ascending
     * @return static
     */
    public function orderByNoLowerMaxbid(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_lower_maxbid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.block_sold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBlockSoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.block_sold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.block_sold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBlockSoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.block_sold_lots', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.block_sold_lots
     * @return static
     */
    public function groupByBlockSoldLots(): static
    {
        $this->group($this->alias . '.block_sold_lots');
        return $this;
    }

    /**
     * Order by setting_auction.block_sold_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByBlockSoldLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.block_sold_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.block_sold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockSoldLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.block_sold_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.block_sold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockSoldLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.block_sold_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.block_sold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockSoldLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.block_sold_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.block_sold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockSoldLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.block_sold_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.blacklist_phrase
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBlacklistPhrase(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.blacklist_phrase', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.blacklist_phrase from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBlacklistPhrase(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.blacklist_phrase', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.blacklist_phrase
     * @return static
     */
    public function groupByBlacklistPhrase(): static
    {
        $this->group($this->alias . '.blacklist_phrase');
        return $this;
    }

    /**
     * Order by setting_auction.blacklist_phrase
     * @param bool $ascending
     * @return static
     */
    public function orderByBlacklistPhrase(bool $ascending = true): static
    {
        $this->order($this->alias . '.blacklist_phrase', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.blacklist_phrase by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBlacklistPhrase(string $filterValue): static
    {
        $this->like($this->alias . '.blacklist_phrase', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.conditional_sales
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConditionalSales(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.conditional_sales', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.conditional_sales from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConditionalSales(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.conditional_sales', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.conditional_sales
     * @return static
     */
    public function groupByConditionalSales(): static
    {
        $this->group($this->alias . '.conditional_sales');
        return $this;
    }

    /**
     * Order by setting_auction.conditional_sales
     * @param bool $ascending
     * @return static
     */
    public function orderByConditionalSales(bool $ascending = true): static
    {
        $this->order($this->alias . '.conditional_sales', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.conditional_sales
     * @param bool $filterValue
     * @return static
     */
    public function filterConditionalSalesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.conditional_sales', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.conditional_sales
     * @param bool $filterValue
     * @return static
     */
    public function filterConditionalSalesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.conditional_sales', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.conditional_sales
     * @param bool $filterValue
     * @return static
     */
    public function filterConditionalSalesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.conditional_sales', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.conditional_sales
     * @param bool $filterValue
     * @return static
     */
    public function filterConditionalSalesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.conditional_sales', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.above_starting_bid
     * @return static
     */
    public function groupByAboveStartingBid(): static
    {
        $this->group($this->alias . '.above_starting_bid');
        return $this;
    }

    /**
     * Order by setting_auction.above_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAboveStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.above_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.timed_above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed_above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.timed_above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimedAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed_above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.timed_above_starting_bid
     * @return static
     */
    public function groupByTimedAboveStartingBid(): static
    {
        $this->group($this->alias . '.timed_above_starting_bid');
        return $this;
    }

    /**
     * Order by setting_auction.timed_above_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByTimedAboveStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.timed_above_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.timed_above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.timed_above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.timed_above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.timed_above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.timed_above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimedAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed_above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.timed_above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimedAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed_above_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.timed_above_reserve
     * @return static
     */
    public function groupByTimedAboveReserve(): static
    {
        $this->group($this->alias . '.timed_above_reserve');
        return $this;
    }

    /**
     * Order by setting_auction.timed_above_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByTimedAboveReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.timed_above_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.timed_above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveReserveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.timed_above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveReserveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.timed_above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveReserveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.timed_above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedAboveReserveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_above_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.add_description_in_lot_name_column
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumn(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.add_description_in_lot_name_column', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.add_description_in_lot_name_column from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAddDescriptionInLotNameColumn(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.add_description_in_lot_name_column', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.add_description_in_lot_name_column
     * @return static
     */
    public function groupByAddDescriptionInLotNameColumn(): static
    {
        $this->group($this->alias . '.add_description_in_lot_name_column');
        return $this;
    }

    /**
     * Order by setting_auction.add_description_in_lot_name_column
     * @param bool $ascending
     * @return static
     */
    public function orderByAddDescriptionInLotNameColumn(bool $ascending = true): static
    {
        $this->order($this->alias . '.add_description_in_lot_name_column', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.add_description_in_lot_name_column
     * @param bool $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumnGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_description_in_lot_name_column', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.add_description_in_lot_name_column
     * @param bool $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumnGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_description_in_lot_name_column', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.add_description_in_lot_name_column
     * @param bool $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumnLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_description_in_lot_name_column', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.add_description_in_lot_name_column
     * @param bool $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumnLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.add_description_in_lot_name_column', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.require_on_inc_bids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireOnIncBids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_on_inc_bids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.require_on_inc_bids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireOnIncBids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_on_inc_bids', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.require_on_inc_bids
     * @return static
     */
    public function groupByRequireOnIncBids(): static
    {
        $this->group($this->alias . '.require_on_inc_bids');
        return $this;
    }

    /**
     * Order by setting_auction.require_on_inc_bids
     * @param bool $ascending
     * @return static
     */
    public function orderByRequireOnIncBids(bool $ascending = true): static
    {
        $this->order($this->alias . '.require_on_inc_bids', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.require_on_inc_bids
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireOnIncBidsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_on_inc_bids', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.require_on_inc_bids
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireOnIncBidsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_on_inc_bids', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.require_on_inc_bids
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireOnIncBidsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_on_inc_bids', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.require_on_inc_bids
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireOnIncBidsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_on_inc_bids', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.inline_bid_confirm
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInlineBidConfirm(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.inline_bid_confirm', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.inline_bid_confirm from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInlineBidConfirm(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.inline_bid_confirm', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.inline_bid_confirm
     * @return static
     */
    public function groupByInlineBidConfirm(): static
    {
        $this->group($this->alias . '.inline_bid_confirm');
        return $this;
    }

    /**
     * Order by setting_auction.inline_bid_confirm
     * @param bool $ascending
     * @return static
     */
    public function orderByInlineBidConfirm(bool $ascending = true): static
    {
        $this->order($this->alias . '.inline_bid_confirm', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.inline_bid_confirm
     * @param bool $filterValue
     * @return static
     */
    public function filterInlineBidConfirmGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.inline_bid_confirm', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.inline_bid_confirm
     * @param bool $filterValue
     * @return static
     */
    public function filterInlineBidConfirmGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.inline_bid_confirm', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.inline_bid_confirm
     * @param bool $filterValue
     * @return static
     */
    public function filterInlineBidConfirmLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.inline_bid_confirm', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.inline_bid_confirm
     * @param bool $filterValue
     * @return static
     */
    public function filterInlineBidConfirmLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.inline_bid_confirm', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.allow_force_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowForceBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_force_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_force_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowForceBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_force_bid', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.allow_force_bid
     * @return static
     */
    public function groupByAllowForceBid(): static
    {
        $this->group($this->alias . '.allow_force_bid');
        return $this;
    }

    /**
     * Order by setting_auction.allow_force_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowForceBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_force_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.take_max_bids_under_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.take_max_bids_under_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.take_max_bids_under_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTakeMaxBidsUnderReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.take_max_bids_under_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.take_max_bids_under_reserve
     * @return static
     */
    public function groupByTakeMaxBidsUnderReserve(): static
    {
        $this->group($this->alias . '.take_max_bids_under_reserve');
        return $this;
    }

    /**
     * Order by setting_auction.take_max_bids_under_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByTakeMaxBidsUnderReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.take_max_bids_under_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.only_one_reg_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_one_reg_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.only_one_reg_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyOneRegEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_one_reg_email', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.only_one_reg_email
     * @return static
     */
    public function groupByOnlyOneRegEmail(): static
    {
        $this->group($this->alias . '.only_one_reg_email');
        return $this;
    }

    /**
     * Order by setting_auction.only_one_reg_email
     * @param bool $ascending
     * @return static
     */
    public function orderByOnlyOneRegEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.only_one_reg_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.only_one_reg_email
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmailGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_one_reg_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.only_one_reg_email
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmailGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_one_reg_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.only_one_reg_email
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmailLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_one_reg_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.only_one_reg_email
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmailLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_one_reg_email', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_manual_bidder_for_flagged_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowManualBidderForFlaggedBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_manual_bidder_for_flagged_bidders', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.allow_manual_bidder_for_flagged_bidders
     * @return static
     */
    public function groupByAllowManualBidderForFlaggedBidders(): static
    {
        $this->group($this->alias . '.allow_manual_bidder_for_flagged_bidders');
        return $this;
    }

    /**
     * Order by setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowManualBidderForFlaggedBidders(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_manual_bidder_for_flagged_bidders', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBiddersGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBiddersGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBiddersLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBiddersLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.item_num_lock
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterItemNumLock(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num_lock', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.item_num_lock from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipItemNumLock(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num_lock', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.item_num_lock
     * @return static
     */
    public function groupByItemNumLock(): static
    {
        $this->group($this->alias . '.item_num_lock');
        return $this;
    }

    /**
     * Order by setting_auction.item_num_lock
     * @param bool $ascending
     * @return static
     */
    public function orderByItemNumLock(bool $ascending = true): static
    {
        $this->order($this->alias . '.item_num_lock', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.item_num_lock
     * @param bool $filterValue
     * @return static
     */
    public function filterItemNumLockGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num_lock', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.item_num_lock
     * @param bool $filterValue
     * @return static
     */
    public function filterItemNumLockGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num_lock', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.item_num_lock
     * @param bool $filterValue
     * @return static
     */
    public function filterItemNumLockLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num_lock', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.item_num_lock
     * @param bool $filterValue
     * @return static
     */
    public function filterItemNumLockLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.item_num_lock', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.hide_unsold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideUnsoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_unsold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_unsold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideUnsoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_unsold_lots', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.hide_unsold_lots
     * @return static
     */
    public function groupByHideUnsoldLots(): static
    {
        $this->group($this->alias . '.hide_unsold_lots');
        return $this;
    }

    /**
     * Order by setting_auction.hide_unsold_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByHideUnsoldLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_unsold_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.auction_domain_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionDomainMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_domain_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.auction_domain_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionDomainMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_domain_mode', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.auction_domain_mode
     * @return static
     */
    public function groupByAuctionDomainMode(): static
    {
        $this->group($this->alias . '.auction_domain_mode');
        return $this;
    }

    /**
     * Order by setting_auction.auction_domain_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionDomainMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_domain_mode', $ascending);
        return $this;
    }

    /**
     * Filter setting_auction.auction_domain_mode by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionDomainMode(string $filterValue): static
    {
        $this->like($this->alias . '.auction_domain_mode', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_auction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_auction.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_auction.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_auction.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_auction.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_auction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_auction.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_auction.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
