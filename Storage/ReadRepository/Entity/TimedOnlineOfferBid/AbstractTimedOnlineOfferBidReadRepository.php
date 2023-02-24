<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use TimedOnlineOfferBid;

/**
 * Abstract class AbstractTimedOnlineOfferBidReadRepository
 * @method TimedOnlineOfferBid[] loadEntities()
 * @method TimedOnlineOfferBid|null loadEntity()
 */
abstract class AbstractTimedOnlineOfferBidReadRepository extends ReadRepositoryBase
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
     * Group by timed_online_offer_bid.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.auction_lot_item_id
     * @return static
     */
    public function groupByAuctionLotItemId(): static
    {
        $this->group($this->alias . '.auction_lot_item_id');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.auction_lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.bid
     * @return static
     */
    public function groupByBid(): static
    {
        $this->group($this->alias . '.bid');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.bid
     * @param bool $ascending
     * @return static
     */
    public function orderByBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.is_counter_bid
     * @return static
     */
    public function groupByIsCounterBid(): static
    {
        $this->group($this->alias . '.is_counter_bid');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.is_counter_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByIsCounterBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.is_counter_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.is_counter_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterIsCounterBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_counter_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.is_counter_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterIsCounterBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_counter_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.is_counter_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterIsCounterBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_counter_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.is_counter_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterIsCounterBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_counter_bid', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.status
     * @return static
     */
    public function groupByStatus(): static
    {
        $this->group($this->alias . '.status');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.status
     * @param bool $ascending
     * @return static
     */
    public function orderByStatus(bool $ascending = true): static
    {
        $this->order($this->alias . '.status', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.deleted
     * @return static
     */
    public function groupByDeleted(): static
    {
        $this->group($this->alias . '.deleted');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.deleted
     * @param bool $ascending
     * @return static
     */
    public function orderByDeleted(bool $ascending = true): static
    {
        $this->order($this->alias . '.deleted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.date_added
     * @return static
     */
    public function groupByDateAdded(): static
    {
        $this->group($this->alias . '.date_added');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.date_added
     * @param bool $ascending
     * @return static
     */
    public function orderByDateAdded(bool $ascending = true): static
    {
        $this->order($this->alias . '.date_added', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.date_added
     * @param string $filterValue
     * @return static
     */
    public function filterDateAddedGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_added', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.date_added
     * @param string $filterValue
     * @return static
     */
    public function filterDateAddedGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_added', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.date_added
     * @param string $filterValue
     * @return static
     */
    public function filterDateAddedLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_added', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.date_added
     * @param string $filterValue
     * @return static
     */
    public function filterDateAddedLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_added', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by timed_online_offer_bid.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by timed_online_offer_bid.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by timed_online_offer_bid.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_offer_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_offer_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_offer_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_offer_bid.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
