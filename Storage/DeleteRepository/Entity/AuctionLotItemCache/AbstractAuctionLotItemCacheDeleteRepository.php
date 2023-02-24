<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItemCache;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionLotItemCacheDeleteRepository extends DeleteRepositoryBase
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
}
