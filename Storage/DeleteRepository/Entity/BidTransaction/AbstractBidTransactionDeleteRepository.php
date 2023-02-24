<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BidTransaction;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractBidTransactionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_BID_TRANSACTION;
    protected string $alias = Db::A_BID_TRANSACTION;

    /**
     * Filter by bid_transaction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bid', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bid', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.max_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.max_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.out_bid_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOutBidId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.out_bid_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.out_bid_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOutBidId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.out_bid_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.parent_bid_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterParentBidId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.parent_bid_id', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.parent_bid_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipParentBidId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.parent_bid_id', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.floor_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFloorBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.floor_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.floor_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFloorBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.floor_bidder', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.absentee_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.absentee_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.timed_online_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimedOnlineBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed_online_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.timed_online_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimedOnlineBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed_online_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.is_buy_now
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIsBuyNow(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.is_buy_now', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.is_buy_now from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIsBuyNow(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.is_buy_now', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.deleted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDeleted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.deleted', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.deleted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDeleted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.deleted', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.bid_status
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBidStatus(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_status', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.bid_status from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBidStatus(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_status', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.failed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFailed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.failed', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.failed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFailed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.failed', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Filter by bid_transaction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out bid_transaction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
