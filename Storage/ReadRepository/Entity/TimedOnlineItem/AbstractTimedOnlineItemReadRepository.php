<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineItem;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use TimedOnlineItem;

/**
 * Abstract class AbstractTimedOnlineItemReadRepository
 * @method TimedOnlineItem[] loadEntities()
 * @method TimedOnlineItem|null loadEntity()
 */
abstract class AbstractTimedOnlineItemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_TIMED_ONLINE_ITEM;
    protected string $alias = Db::A_TIMED_ONLINE_ITEM;

    /**
     * Filter by timed_online_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by timed_online_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by timed_online_item.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by timed_online_item.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.no_bidding
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoBidding(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_bidding', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.no_bidding from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoBidding(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_bidding', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.no_bidding
     * @return static
     */
    public function groupByNoBidding(): static
    {
        $this->group($this->alias . '.no_bidding');
        return $this;
    }

    /**
     * Order by timed_online_item.no_bidding
     * @param bool $ascending
     * @return static
     */
    public function orderByNoBidding(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_bidding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.no_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterNoBiddingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_bidding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.no_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterNoBiddingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_bidding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.no_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterNoBiddingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_bidding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.no_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterNoBiddingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_bidding', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by timed_online_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by timed_online_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by timed_online_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by timed_online_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.best_offer
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBestOffer(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.best_offer', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.best_offer from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBestOffer(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.best_offer', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.best_offer
     * @return static
     */
    public function groupByBestOffer(): static
    {
        $this->group($this->alias . '.best_offer');
        return $this;
    }

    /**
     * Order by timed_online_item.best_offer
     * @param bool $ascending
     * @return static
     */
    public function orderByBestOffer(bool $ascending = true): static
    {
        $this->order($this->alias . '.best_offer', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by timed_online_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out timed_online_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by timed_online_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by timed_online_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than timed_online_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than timed_online_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than timed_online_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than timed_online_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
