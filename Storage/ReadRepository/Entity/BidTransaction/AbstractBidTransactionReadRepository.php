<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BidTransaction;

use BidTransaction;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractBidTransactionReadRepository
 * @method BidTransaction[] loadEntities()
 * @method BidTransaction|null loadEntity()
 */
abstract class AbstractBidTransactionReadRepository extends ReadRepositoryBase
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
     * Group by bid_transaction.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by bid_transaction.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by bid_transaction.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by bid_transaction.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
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
     * Group by bid_transaction.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by bid_transaction.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
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
     * Group by bid_transaction.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by bid_transaction.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by bid_transaction.bid
     * @return static
     */
    public function groupByBid(): static
    {
        $this->group($this->alias . '.bid');
        return $this;
    }

    /**
     * Order by bid_transaction.bid
     * @param bool $ascending
     * @return static
     */
    public function orderByBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.bid
     * @param float $filterValue
     * @return static
     */
    public function filterBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid', $filterValue, '<=');
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
     * Group by bid_transaction.max_bid
     * @return static
     */
    public function groupByMaxBid(): static
    {
        $this->group($this->alias . '.max_bid');
        return $this;
    }

    /**
     * Order by bid_transaction.max_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.max_bid
     * @param float $filterValue
     * @return static
     */
    public function filterMaxBidLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_bid', $filterValue, '<=');
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
     * Group by bid_transaction.out_bid_id
     * @return static
     */
    public function groupByOutBidId(): static
    {
        $this->group($this->alias . '.out_bid_id');
        return $this;
    }

    /**
     * Order by bid_transaction.out_bid_id
     * @param bool $ascending
     * @return static
     */
    public function orderByOutBidId(bool $ascending = true): static
    {
        $this->order($this->alias . '.out_bid_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.out_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterOutBidIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.out_bid_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.out_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterOutBidIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.out_bid_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.out_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterOutBidIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.out_bid_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.out_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterOutBidIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.out_bid_id', $filterValue, '<=');
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
     * Group by bid_transaction.parent_bid_id
     * @return static
     */
    public function groupByParentBidId(): static
    {
        $this->group($this->alias . '.parent_bid_id');
        return $this;
    }

    /**
     * Order by bid_transaction.parent_bid_id
     * @param bool $ascending
     * @return static
     */
    public function orderByParentBidId(bool $ascending = true): static
    {
        $this->order($this->alias . '.parent_bid_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.parent_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentBidIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_bid_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.parent_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentBidIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_bid_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.parent_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentBidIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_bid_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.parent_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterParentBidIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.parent_bid_id', $filterValue, '<=');
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
     * Group by bid_transaction.floor_bidder
     * @return static
     */
    public function groupByFloorBidder(): static
    {
        $this->group($this->alias . '.floor_bidder');
        return $this;
    }

    /**
     * Order by bid_transaction.floor_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByFloorBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.floor_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterFloorBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.floor_bidder', $filterValue, '<=');
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
     * Group by bid_transaction.absentee_bid
     * @return static
     */
    public function groupByAbsenteeBid(): static
    {
        $this->group($this->alias . '.absentee_bid');
        return $this;
    }

    /**
     * Order by bid_transaction.absentee_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.absentee_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAbsenteeBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.absentee_bid', $filterValue, '<=');
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
     * Group by bid_transaction.timed_online_bid
     * @return static
     */
    public function groupByTimedOnlineBid(): static
    {
        $this->group($this->alias . '.timed_online_bid');
        return $this;
    }

    /**
     * Order by bid_transaction.timed_online_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByTimedOnlineBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.timed_online_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.timed_online_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedOnlineBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_online_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.timed_online_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedOnlineBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_online_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.timed_online_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedOnlineBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_online_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.timed_online_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedOnlineBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed_online_bid', $filterValue, '<=');
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
     * Group by bid_transaction.is_buy_now
     * @return static
     */
    public function groupByIsBuyNow(): static
    {
        $this->group($this->alias . '.is_buy_now');
        return $this;
    }

    /**
     * Order by bid_transaction.is_buy_now
     * @param bool $ascending
     * @return static
     */
    public function orderByIsBuyNow(bool $ascending = true): static
    {
        $this->order($this->alias . '.is_buy_now', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.is_buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBuyNowGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_buy_now', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.is_buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBuyNowGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_buy_now', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.is_buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBuyNowLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_buy_now', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.is_buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBuyNowLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_buy_now', $filterValue, '<=');
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
     * Group by bid_transaction.deleted
     * @return static
     */
    public function groupByDeleted(): static
    {
        $this->group($this->alias . '.deleted');
        return $this;
    }

    /**
     * Order by bid_transaction.deleted
     * @param bool $ascending
     * @return static
     */
    public function orderByDeleted(bool $ascending = true): static
    {
        $this->order($this->alias . '.deleted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<=');
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
     * Group by bid_transaction.bid_status
     * @return static
     */
    public function groupByBidStatus(): static
    {
        $this->group($this->alias . '.bid_status');
        return $this;
    }

    /**
     * Order by bid_transaction.bid_status
     * @param bool $ascending
     * @return static
     */
    public function orderByBidStatus(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_status', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.bid_status
     * @param int $filterValue
     * @return static
     */
    public function filterBidStatusGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_status', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.bid_status
     * @param int $filterValue
     * @return static
     */
    public function filterBidStatusGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_status', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.bid_status
     * @param int $filterValue
     * @return static
     */
    public function filterBidStatusLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_status', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.bid_status
     * @param int $filterValue
     * @return static
     */
    public function filterBidStatusLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_status', $filterValue, '<=');
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
     * Group by bid_transaction.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by bid_transaction.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by bid_transaction.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by bid_transaction.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by bid_transaction.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by bid_transaction.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by bid_transaction.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by bid_transaction.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by bid_transaction.failed
     * @return static
     */
    public function groupByFailed(): static
    {
        $this->group($this->alias . '.failed');
        return $this;
    }

    /**
     * Order by bid_transaction.failed
     * @param bool $ascending
     * @return static
     */
    public function orderByFailed(bool $ascending = true): static
    {
        $this->order($this->alias . '.failed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.failed
     * @param bool $filterValue
     * @return static
     */
    public function filterFailedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.failed
     * @param bool $filterValue
     * @return static
     */
    public function filterFailedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.failed
     * @param bool $filterValue
     * @return static
     */
    public function filterFailedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.failed
     * @param bool $filterValue
     * @return static
     */
    public function filterFailedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed', $filterValue, '<=');
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
     * Group by bid_transaction.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by bid_transaction.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter bid_transaction.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
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
     * Group by bid_transaction.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by bid_transaction.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter bid_transaction.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
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

    /**
     * Group by bid_transaction.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by bid_transaction.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than bid_transaction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than bid_transaction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than bid_transaction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than bid_transaction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
