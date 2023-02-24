<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemCache;

use AuctionLotItemCache;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionLotItemCacheReadRepository
 * @method AuctionLotItemCache[] loadEntities()
 * @method AuctionLotItemCache|null loadEntity()
 */
abstract class AbstractAuctionLotItemCacheReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_LOT_ITEM_CACHE;
    protected string $alias = Db::A_AUCTION_LOT_ITEM_CACHE;

    /**
     * Filter by auction_lot_item_cache.auction_lot_item_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionLotItemId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.auction_lot_item_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionLotItemId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.auction_lot_item_id
     * @return static
     */
    public function groupByAuctionLotItemId(): static
    {
        $this->group($this->alias . '.auction_lot_item_id');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.auction_lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.current_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCurrentBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.current_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.current_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCurrentBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.current_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.current_bid
     * @return static
     */
    public function groupByCurrentBid(): static
    {
        $this->group($this->alias . '.current_bid');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.current_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrentBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.current_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.current_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.current_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.current_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.current_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.current_max_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCurrentMaxBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.current_max_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.current_max_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCurrentMaxBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.current_max_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.current_max_bid
     * @return static
     */
    public function groupByCurrentMaxBid(): static
    {
        $this->group($this->alias . '.current_max_bid');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.current_max_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrentMaxBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.current_max_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.current_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentMaxBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_max_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.current_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentMaxBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_max_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.current_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentMaxBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_max_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.current_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterCurrentMaxBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_max_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.current_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCurrentBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.current_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.current_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCurrentBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.current_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.current_bidder_id
     * @return static
     */
    public function groupByCurrentBidderId(): static
    {
        $this->group($this->alias . '.current_bidder_id');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.current_bidder_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrentBidderId(bool $ascending = true): static
    {
        $this->order($this->alias . '.current_bidder_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.current_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidderIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bidder_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.current_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidderIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bidder_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.current_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidderIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bidder_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.current_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidderIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bidder_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.current_bid_placed
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterCurrentBidPlaced(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.current_bid_placed', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.current_bid_placed from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipCurrentBidPlaced(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.current_bid_placed', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.current_bid_placed
     * @return static
     */
    public function groupByCurrentBidPlaced(): static
    {
        $this->group($this->alias . '.current_bid_placed');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.current_bid_placed
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrentBidPlaced(bool $ascending = true): static
    {
        $this->order($this->alias . '.current_bid_placed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.current_bid_placed
     * @param string $filterValue
     * @return static
     */
    public function filterCurrentBidPlacedGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_placed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.current_bid_placed
     * @param string $filterValue
     * @return static
     */
    public function filterCurrentBidPlacedGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_placed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.current_bid_placed
     * @param string $filterValue
     * @return static
     */
    public function filterCurrentBidPlacedLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_placed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.current_bid_placed
     * @param string $filterValue
     * @return static
     */
    public function filterCurrentBidPlacedLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_placed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.second_max_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSecondMaxBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.second_max_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.second_max_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSecondMaxBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.second_max_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.second_max_bid
     * @return static
     */
    public function groupBySecondMaxBid(): static
    {
        $this->group($this->alias . '.second_max_bid');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.second_max_bid
     * @param bool $ascending
     * @return static
     */
    public function orderBySecondMaxBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.second_max_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.second_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterSecondMaxBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_max_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.second_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterSecondMaxBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_max_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.second_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterSecondMaxBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_max_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.second_max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterSecondMaxBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_max_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.second_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSecondBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.second_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.second_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSecondBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.second_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.second_bidder_id
     * @return static
     */
    public function groupBySecondBidderId(): static
    {
        $this->group($this->alias . '.second_bidder_id');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.second_bidder_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySecondBidderId(bool $ascending = true): static
    {
        $this->order($this->alias . '.second_bidder_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.second_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterSecondBidderIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_bidder_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.second_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterSecondBidderIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_bidder_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.second_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterSecondBidderIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_bidder_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.second_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterSecondBidderIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.second_bidder_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.asking_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAskingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.asking_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.asking_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAskingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.asking_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.asking_bid
     * @return static
     */
    public function groupByAskingBid(): static
    {
        $this->group($this->alias . '.asking_bid');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.asking_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAskingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.asking_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskingBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.asking_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskingBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.asking_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskingBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.asking_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterAskingBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.asking_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.bulk_master_asking_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBulkMasterAskingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bulk_master_asking_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.bulk_master_asking_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBulkMasterAskingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bulk_master_asking_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.bulk_master_asking_bid
     * @return static
     */
    public function groupByBulkMasterAskingBid(): static
    {
        $this->group($this->alias . '.bulk_master_asking_bid');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.bulk_master_asking_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByBulkMasterAskingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.bulk_master_asking_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.bulk_master_asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterAskingBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_asking_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.bulk_master_asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterAskingBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_asking_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.bulk_master_asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterAskingBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_asking_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.bulk_master_asking_bid
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterAskingBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_asking_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.bulk_master_suggested_reserve
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBulkMasterSuggestedReserve(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bulk_master_suggested_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.bulk_master_suggested_reserve from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBulkMasterSuggestedReserve(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bulk_master_suggested_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.bulk_master_suggested_reserve
     * @return static
     */
    public function groupByBulkMasterSuggestedReserve(): static
    {
        $this->group($this->alias . '.bulk_master_suggested_reserve');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.bulk_master_suggested_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByBulkMasterSuggestedReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.bulk_master_suggested_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.bulk_master_suggested_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterSuggestedReserveGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_suggested_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.bulk_master_suggested_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterSuggestedReserveGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_suggested_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.bulk_master_suggested_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterSuggestedReserveLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_suggested_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.bulk_master_suggested_reserve
     * @param float $filterValue
     * @return static
     */
    public function filterBulkMasterSuggestedReserveLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_suggested_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.starting_bid_normalized
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterStartingBidNormalized(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.starting_bid_normalized', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.starting_bid_normalized from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipStartingBidNormalized(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.starting_bid_normalized', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.starting_bid_normalized
     * @return static
     */
    public function groupByStartingBidNormalized(): static
    {
        $this->group($this->alias . '.starting_bid_normalized');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.starting_bid_normalized
     * @param bool $ascending
     * @return static
     */
    public function orderByStartingBidNormalized(bool $ascending = true): static
    {
        $this->order($this->alias . '.starting_bid_normalized', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.starting_bid_normalized
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidNormalizedGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid_normalized', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.starting_bid_normalized
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidNormalizedGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid_normalized', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.starting_bid_normalized
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidNormalizedLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid_normalized', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.starting_bid_normalized
     * @param float $filterValue
     * @return static
     */
    public function filterStartingBidNormalizedLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.starting_bid_normalized', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.bid_count
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBidCount(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_count', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.bid_count from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBidCount(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_count', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.bid_count
     * @return static
     */
    public function groupByBidCount(): static
    {
        $this->group($this->alias . '.bid_count');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.bid_count
     * @param bool $ascending
     * @return static
     */
    public function orderByBidCount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_count', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.bid_count
     * @param int $filterValue
     * @return static
     */
    public function filterBidCountGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_count', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.bid_count
     * @param int $filterValue
     * @return static
     */
    public function filterBidCountGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_count', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.bid_count
     * @param int $filterValue
     * @return static
     */
    public function filterBidCountLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_count', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.bid_count
     * @param int $filterValue
     * @return static
     */
    public function filterBidCountLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_count', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.view_count
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterViewCount(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.view_count', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.view_count from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipViewCount(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.view_count', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.view_count
     * @return static
     */
    public function groupByViewCount(): static
    {
        $this->group($this->alias . '.view_count');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.view_count
     * @param bool $ascending
     * @return static
     */
    public function orderByViewCount(bool $ascending = true): static
    {
        $this->order($this->alias . '.view_count', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.view_count
     * @param int $filterValue
     * @return static
     */
    public function filterViewCountGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_count', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.view_count
     * @param int $filterValue
     * @return static
     */
    public function filterViewCountGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_count', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.view_count
     * @param int $filterValue
     * @return static
     */
    public function filterViewCountLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_count', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.view_count
     * @param int $filterValue
     * @return static
     */
    public function filterViewCountLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_count', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.start_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.start_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.start_date
     * @return static
     */
    public function groupByStartDate(): static
    {
        $this->group($this->alias . '.start_date');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.start_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.end_date
     * @return static
     */
    public function groupByEndDate(): static
    {
        $this->group($this->alias . '.end_date');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.end_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.seo_url
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterSeoUrl(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_url', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.seo_url from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipSeoUrl(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_url', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.seo_url
     * @return static
     */
    public function groupBySeoUrl(): static
    {
        $this->group($this->alias . '.seo_url');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.seo_url
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_url', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item_cache.seo_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoUrl(string $filterValue): static
    {
        $this->like($this->alias . '.seo_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item_cache.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item_cache.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item_cache.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_lot_item_cache.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item_cache.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
