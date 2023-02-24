<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionCache;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionCacheDeleteRepository extends DeleteRepositoryBase
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
}
