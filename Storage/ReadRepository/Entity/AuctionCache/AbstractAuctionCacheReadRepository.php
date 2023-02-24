<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCache;

use AuctionCache;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionCacheReadRepository
 * @method AuctionCache[] loadEntities()
 * @method AuctionCache|null loadEntity()
 */
abstract class AbstractAuctionCacheReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_CACHE;
    protected string $alias = Db::A_AUCTION_CACHE;

    /**
     * Filter by auction_cache.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by auction_cache.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_bid
     * @return static
     */
    public function groupByTotalBid(): static
    {
        $this->group($this->alias . '.total_bid');
        return $this;
    }

    /**
     * Order by auction_cache.total_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_max_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalMaxBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_max_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_max_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalMaxBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_max_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_max_bid
     * @return static
     */
    public function groupByTotalMaxBid(): static
    {
        $this->group($this->alias . '.total_max_bid');
        return $this;
    }

    /**
     * Order by auction_cache.total_max_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalMaxBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_max_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalMaxBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_max_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalMaxBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_max_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalMaxBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_max_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalMaxBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_max_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_starting_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalStartingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_starting_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalStartingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_starting_bid
     * @return static
     */
    public function groupByTotalStartingBid(): static
    {
        $this->group($this->alias . '.total_starting_bid');
        return $this;
    }

    /**
     * Order by auction_cache.total_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalStartingBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalStartingBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalStartingBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_starting_bid
     * @param float $filterValue
     * @return static
     */
    public function filterTotalStartingBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_low_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalLowEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_low_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_low_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalLowEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_low_estimate', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_low_estimate
     * @return static
     */
    public function groupByTotalLowEstimate(): static
    {
        $this->group($this->alias . '.total_low_estimate');
        return $this;
    }

    /**
     * Order by auction_cache.total_low_estimate
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalLowEstimate(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_low_estimate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalLowEstimateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_low_estimate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalLowEstimateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_low_estimate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalLowEstimateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_low_estimate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_low_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalLowEstimateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_low_estimate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_high_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalHighEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_high_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_high_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalHighEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_high_estimate', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_high_estimate
     * @return static
     */
    public function groupByTotalHighEstimate(): static
    {
        $this->group($this->alias . '.total_high_estimate');
        return $this;
    }

    /**
     * Order by auction_cache.total_high_estimate
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalHighEstimate(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_high_estimate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHighEstimateGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_high_estimate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHighEstimateGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_high_estimate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHighEstimateLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_high_estimate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_high_estimate
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHighEstimateLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_high_estimate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_reserve
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalReserve(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_reserve from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalReserve(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_reserve
     * @return static
     */
    public function groupByTotalReserve(): static
    {
        $this->group($this->alias . '.total_reserve');
        return $this;
    }

    /**
     * Order by auction_cache.total_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_reserve_met
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalReserveMet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_reserve_met', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_reserve_met from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalReserveMet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_reserve_met', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_reserve_met
     * @return static
     */
    public function groupByTotalReserveMet(): static
    {
        $this->group($this->alias . '.total_reserve_met');
        return $this;
    }

    /**
     * Order by auction_cache.total_reserve_met
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalReserveMet(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_reserve_met', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_reserve_met
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveMetGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve_met', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_reserve_met
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveMetGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve_met', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_reserve_met
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveMetLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve_met', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_reserve_met
     * @param float $filterValue
     * @return static
     */
    public function filterTotalReserveMetLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_reserve_met', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_hammer_price', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_hammer_price
     * @return static
     */
    public function groupByTotalHammerPrice(): static
    {
        $this->group($this->alias . '.total_hammer_price');
        return $this;
    }

    /**
     * Order by auction_cache.total_hammer_price
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalHammerPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_hammer_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_hammer_price_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalHammerPriceInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_hammer_price_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_hammer_price_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalHammerPriceInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_hammer_price_internet', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_hammer_price_internet
     * @return static
     */
    public function groupByTotalHammerPriceInternet(): static
    {
        $this->group($this->alias . '.total_hammer_price_internet');
        return $this;
    }

    /**
     * Order by auction_cache.total_hammer_price_internet
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalHammerPriceInternet(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_hammer_price_internet', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_hammer_price_internet
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceInternetGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price_internet', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_hammer_price_internet
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceInternetGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price_internet', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_hammer_price_internet
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceInternetLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price_internet', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_hammer_price_internet
     * @param float $filterValue
     * @return static
     */
    public function filterTotalHammerPriceInternetLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_hammer_price_internet', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_buyers_premium
     * @return static
     */
    public function groupByTotalBuyersPremium(): static
    {
        $this->group($this->alias . '.total_buyers_premium');
        return $this;
    }

    /**
     * Order by auction_cache.total_buyers_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalBuyersPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_buyers_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBuyersPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_buyers_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBuyersPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_buyers_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBuyersPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_buyers_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalBuyersPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_buyers_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_fees
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalFees(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_fees', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_fees from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalFees(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_fees', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_fees
     * @return static
     */
    public function groupByTotalFees(): static
    {
        $this->group($this->alias . '.total_fees');
        return $this;
    }

    /**
     * Order by auction_cache.total_fees
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalFees(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_fees', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalFeesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_fees', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalFeesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_fees', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalFeesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_fees', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalFeesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_fees', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_paid_hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPaidHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_paid_hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_paid_hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPaidHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_paid_hammer_price', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_paid_hammer_price
     * @return static
     */
    public function groupByTotalPaidHammerPrice(): static
    {
        $this->group($this->alias . '.total_paid_hammer_price');
        return $this;
    }

    /**
     * Order by auction_cache.total_paid_hammer_price
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalPaidHammerPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_paid_hammer_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_paid_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidHammerPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_hammer_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_paid_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidHammerPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_hammer_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_paid_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidHammerPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_hammer_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_paid_hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidHammerPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_hammer_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_paid_buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPaidBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_paid_buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_paid_buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPaidBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_paid_buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_paid_buyers_premium
     * @return static
     */
    public function groupByTotalPaidBuyersPremium(): static
    {
        $this->group($this->alias . '.total_paid_buyers_premium');
        return $this;
    }

    /**
     * Order by auction_cache.total_paid_buyers_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalPaidBuyersPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_paid_buyers_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_paid_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidBuyersPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_buyers_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_paid_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidBuyersPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_buyers_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_paid_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidBuyersPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_buyers_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_paid_buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidBuyersPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_buyers_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_paid_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPaidTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_paid_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_paid_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPaidTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_paid_tax', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_paid_tax
     * @return static
     */
    public function groupByTotalPaidTax(): static
    {
        $this->group($this->alias . '.total_paid_tax');
        return $this;
    }

    /**
     * Order by auction_cache.total_paid_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalPaidTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_paid_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_paid_tax
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_paid_tax
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_paid_tax
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_paid_tax
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_paid_fees
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPaidFees(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_paid_fees', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_paid_fees from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPaidFees(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_paid_fees', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_paid_fees
     * @return static
     */
    public function groupByTotalPaidFees(): static
    {
        $this->group($this->alias . '.total_paid_fees');
        return $this;
    }

    /**
     * Order by auction_cache.total_paid_fees
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalPaidFees(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_paid_fees', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_paid_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidFeesGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_fees', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_paid_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidFeesGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_fees', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_paid_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidFeesLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_fees', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_paid_fees
     * @param float $filterValue
     * @return static
     */
    public function filterTotalPaidFeesLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_paid_fees', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_views
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTotalViews(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_views', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_views from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTotalViews(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_views', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_views
     * @return static
     */
    public function groupByTotalViews(): static
    {
        $this->group($this->alias . '.total_views');
        return $this;
    }

    /**
     * Order by auction_cache.total_views
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalViews(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_views', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_views
     * @param int $filterValue
     * @return static
     */
    public function filterTotalViewsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_views', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_views
     * @param int $filterValue
     * @return static
     */
    public function filterTotalViewsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_views', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_views
     * @param int $filterValue
     * @return static
     */
    public function filterTotalViewsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_views', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_views
     * @param int $filterValue
     * @return static
     */
    public function filterTotalViewsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_views', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_lots
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTotalLots(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_lots from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTotalLots(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_lots
     * @return static
     */
    public function groupByTotalLots(): static
    {
        $this->group($this->alias . '.total_lots');
        return $this;
    }

    /**
     * Order by auction_cache.total_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalLotsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalLotsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalLotsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalLotsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.total_active_lots
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTotalActiveLots(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_active_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.total_active_lots from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTotalActiveLots(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_active_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.total_active_lots
     * @return static
     */
    public function groupByTotalActiveLots(): static
    {
        $this->group($this->alias . '.total_active_lots');
        return $this;
    }

    /**
     * Order by auction_cache.total_active_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByTotalActiveLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.total_active_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.total_active_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalActiveLotsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_active_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.total_active_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalActiveLotsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_active_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.total_active_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalActiveLotsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_active_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.total_active_lots
     * @param int $filterValue
     * @return static
     */
    public function filterTotalActiveLotsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.total_active_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.lots_with_bids
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsWithBids(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_with_bids', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.lots_with_bids from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsWithBids(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_with_bids', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.lots_with_bids
     * @return static
     */
    public function groupByLotsWithBids(): static
    {
        $this->group($this->alias . '.lots_with_bids');
        return $this;
    }

    /**
     * Order by auction_cache.lots_with_bids
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsWithBids(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_with_bids', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.lots_with_bids
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWithBidsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_with_bids', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.lots_with_bids
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWithBidsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_with_bids', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.lots_with_bids
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWithBidsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_with_bids', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.lots_with_bids
     * @param int $filterValue
     * @return static
     */
    public function filterLotsWithBidsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_with_bids', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.lots_above_high_estimate
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsAboveHighEstimate(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_above_high_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.lots_above_high_estimate from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsAboveHighEstimate(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_above_high_estimate', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.lots_above_high_estimate
     * @return static
     */
    public function groupByLotsAboveHighEstimate(): static
    {
        $this->group($this->alias . '.lots_above_high_estimate');
        return $this;
    }

    /**
     * Order by auction_cache.lots_above_high_estimate
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsAboveHighEstimate(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_above_high_estimate', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.lots_above_high_estimate
     * @param int $filterValue
     * @return static
     */
    public function filterLotsAboveHighEstimateGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_above_high_estimate', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.lots_above_high_estimate
     * @param int $filterValue
     * @return static
     */
    public function filterLotsAboveHighEstimateGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_above_high_estimate', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.lots_above_high_estimate
     * @param int $filterValue
     * @return static
     */
    public function filterLotsAboveHighEstimateLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_above_high_estimate', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.lots_above_high_estimate
     * @param int $filterValue
     * @return static
     */
    public function filterLotsAboveHighEstimateLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_above_high_estimate', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.lots_sold
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsSold(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_sold', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.lots_sold from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsSold(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_sold', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.lots_sold
     * @return static
     */
    public function groupByLotsSold(): static
    {
        $this->group($this->alias . '.lots_sold');
        return $this;
    }

    /**
     * Order by auction_cache.lots_sold
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsSold(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_sold', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.lots_sold
     * @param int $filterValue
     * @return static
     */
    public function filterLotsSoldGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_sold', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.lots_sold
     * @param int $filterValue
     * @return static
     */
    public function filterLotsSoldGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_sold', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.lots_sold
     * @param int $filterValue
     * @return static
     */
    public function filterLotsSoldLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_sold', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.lots_sold
     * @param int $filterValue
     * @return static
     */
    public function filterLotsSoldLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_sold', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.bidders
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBidders(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.bidders from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBidders(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidders', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.bidders
     * @return static
     */
    public function groupByBidders(): static
    {
        $this->group($this->alias . '.bidders');
        return $this;
    }

    /**
     * Order by auction_cache.bidders
     * @param bool $ascending
     * @return static
     */
    public function orderByBidders(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidders', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.bidders
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.bidders
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.bidders
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.bidders
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.bidders_approved
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBiddersApproved(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidders_approved', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.bidders_approved from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBiddersApproved(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidders_approved', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.bidders_approved
     * @return static
     */
    public function groupByBiddersApproved(): static
    {
        $this->group($this->alias . '.bidders_approved');
        return $this;
    }

    /**
     * Order by auction_cache.bidders_approved
     * @param bool $ascending
     * @return static
     */
    public function orderByBiddersApproved(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidders_approved', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.bidders_approved
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersApprovedGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_approved', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.bidders_approved
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersApprovedGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_approved', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.bidders_approved
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersApprovedLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_approved', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.bidders_approved
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersApprovedLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_approved', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.bidders_bidding
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBiddersBidding(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidders_bidding', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.bidders_bidding from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBiddersBidding(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidders_bidding', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.bidders_bidding
     * @return static
     */
    public function groupByBiddersBidding(): static
    {
        $this->group($this->alias . '.bidders_bidding');
        return $this;
    }

    /**
     * Order by auction_cache.bidders_bidding
     * @param bool $ascending
     * @return static
     */
    public function orderByBiddersBidding(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidders_bidding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.bidders_bidding
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersBiddingGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_bidding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.bidders_bidding
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersBiddingGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_bidding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.bidders_bidding
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersBiddingLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_bidding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.bidders_bidding
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersBiddingLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_bidding', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.bidders_winning
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBiddersWinning(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidders_winning', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.bidders_winning from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBiddersWinning(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidders_winning', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.bidders_winning
     * @return static
     */
    public function groupByBiddersWinning(): static
    {
        $this->group($this->alias . '.bidders_winning');
        return $this;
    }

    /**
     * Order by auction_cache.bidders_winning
     * @param bool $ascending
     * @return static
     */
    public function orderByBiddersWinning(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidders_winning', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.bidders_winning
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersWinningGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_winning', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.bidders_winning
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersWinningGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_winning', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.bidders_winning
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersWinningLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_winning', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.bidders_winning
     * @param int $filterValue
     * @return static
     */
    public function filterBiddersWinningLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders_winning', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.bids
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBids(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bids', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.bids from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBids(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bids', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.bids
     * @return static
     */
    public function groupByBids(): static
    {
        $this->group($this->alias . '.bids');
        return $this;
    }

    /**
     * Order by auction_cache.bids
     * @param bool $ascending
     * @return static
     */
    public function orderByBids(bool $ascending = true): static
    {
        $this->order($this->alias . '.bids', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.bids
     * @param int $filterValue
     * @return static
     */
    public function filterBidsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bids', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.bids
     * @param int $filterValue
     * @return static
     */
    public function filterBidsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bids', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.bids
     * @param int $filterValue
     * @return static
     */
    public function filterBidsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bids', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.bids
     * @param int $filterValue
     * @return static
     */
    public function filterBidsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bids', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.calculated_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterCalculatedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.calculated_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.calculated_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipCalculatedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.calculated_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.calculated_on
     * @return static
     */
    public function groupByCalculatedOn(): static
    {
        $this->group($this->alias . '.calculated_on');
        return $this;
    }

    /**
     * Order by auction_cache.calculated_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCalculatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.calculated_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.calculated_on
     * @param string $filterValue
     * @return static
     */
    public function filterCalculatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.calculated_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.gcal_changed_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterGcalChangedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.gcal_changed_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.gcal_changed_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipGcalChangedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.gcal_changed_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.gcal_changed_on
     * @return static
     */
    public function groupByGcalChangedOn(): static
    {
        $this->group($this->alias . '.gcal_changed_on');
        return $this;
    }

    /**
     * Order by auction_cache.gcal_changed_on
     * @param bool $ascending
     * @return static
     */
    public function orderByGcalChangedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.gcal_changed_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.gcal_changed_on
     * @param string $filterValue
     * @return static
     */
    public function filterGcalChangedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.gcal_changed_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.gcal_changed_on
     * @param string $filterValue
     * @return static
     */
    public function filterGcalChangedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.gcal_changed_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.gcal_changed_on
     * @param string $filterValue
     * @return static
     */
    public function filterGcalChangedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.gcal_changed_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.gcal_changed_on
     * @param string $filterValue
     * @return static
     */
    public function filterGcalChangedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.gcal_changed_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_cache.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_cache.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_cache.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_cache.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cache.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cache.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cache.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_cache.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
