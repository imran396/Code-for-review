<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TimedOnlineOfferBid;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractTimedOnlineOfferBidDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_TIMED_ONLINE_OFFER_BID;
    protected string $alias = Db::A_TIMED_ONLINE_OFFER_BID;

    /**
     * Filter by timed_online_offer_bid.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.auction_lot_item_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionLotItemId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.auction_lot_item_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionLotItemId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.bid
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBid(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.bid from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBid(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.is_counter_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIsCounterBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.is_counter_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.is_counter_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIsCounterBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.is_counter_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.status
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStatus(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.status', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.status from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStatus(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.status', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.deleted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDeleted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.deleted', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.deleted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDeleted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.deleted', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.date_added
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDateAdded(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.date_added', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.date_added from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDateAdded(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.date_added', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by timed_online_offer_bid.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_offer_bid.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
