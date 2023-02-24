<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItem;

use AuctionLotItem;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionLotItemReadRepository
 * @method AuctionLotItem[] loadEntities()
 * @method AuctionLotItem|null loadEntity()
 */
abstract class AbstractAuctionLotItemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_LOT_ITEM;
    protected string $alias = Db::A_AUCTION_LOT_ITEM;

    /**
     * Filter by auction_lot_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction_lot_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.group_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroupId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.group_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroupId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.group_id
     * @return static
     */
    public function groupByGroupId(): static
    {
        $this->group($this->alias . '.group_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.group_id
     * @param bool $ascending
     * @return static
     */
    public function orderByGroupId(bool $ascending = true): static
    {
        $this->order($this->alias . '.group_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.group_id
     * @param int $filterValue
     * @return static
     */
    public function filterGroupIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.group_id
     * @param int $filterValue
     * @return static
     */
    public function filterGroupIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.group_id
     * @param int $filterValue
     * @return static
     */
    public function filterGroupIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.group_id
     * @param int $filterValue
     * @return static
     */
    public function filterGroupIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.group_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.current_bid_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCurrentBidId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.current_bid_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.current_bid_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCurrentBidId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.current_bid_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.current_bid_id
     * @return static
     */
    public function groupByCurrentBidId(): static
    {
        $this->group($this->alias . '.current_bid_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.current_bid_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrentBidId(bool $ascending = true): static
    {
        $this->order($this->alias . '.current_bid_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.current_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.current_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.current_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.current_bid_id
     * @param int $filterValue
     * @return static
     */
    public function filterCurrentBidIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.current_bid_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.lot_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.lot_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_status_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.lot_status_id
     * @return static
     */
    public function groupByLotStatusId(): static
    {
        $this->group($this->alias . '.lot_status_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.lot_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.lot_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.lot_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.lot_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.lot_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_status_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.lot_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.lot_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.lot_num
     * @return static
     */
    public function groupByLotNum(): static
    {
        $this->group($this->alias . '.lot_num');
        return $this;
    }

    /**
     * Order by auction_lot_item.lot_num
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.lot_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.lot_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.lot_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.lot_num
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.lot_num_ext
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotNumExt(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num_ext', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.lot_num_ext from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotNumExt(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num_ext', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.lot_num_ext
     * @return static
     */
    public function groupByLotNumExt(): static
    {
        $this->group($this->alias . '.lot_num_ext');
        return $this;
    }

    /**
     * Order by auction_lot_item.lot_num_ext
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNumExt(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_num_ext', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.lot_num_ext by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotNumExt(string $filterValue): static
    {
        $this->like($this->alias . '.lot_num_ext', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.lot_num_prefix
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotNumPrefix(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num_prefix', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.lot_num_prefix from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotNumPrefix(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num_prefix', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.lot_num_prefix
     * @return static
     */
    public function groupByLotNumPrefix(): static
    {
        $this->group($this->alias . '.lot_num_prefix');
        return $this;
    }

    /**
     * Order by auction_lot_item.lot_num_prefix
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNumPrefix(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_num_prefix', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.lot_num_prefix by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotNumPrefix(string $filterValue): static
    {
        $this->like($this->alias . '.lot_num_prefix', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.sample_lot
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSampleLot(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sample_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.sample_lot from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSampleLot(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sample_lot', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.sample_lot
     * @return static
     */
    public function groupBySampleLot(): static
    {
        $this->group($this->alias . '.sample_lot');
        return $this;
    }

    /**
     * Order by auction_lot_item.sample_lot
     * @param bool $ascending
     * @return static
     */
    public function orderBySampleLot(bool $ascending = true): static
    {
        $this->order($this->alias . '.sample_lot', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.sample_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterSampleLotGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sample_lot', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.sample_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterSampleLotGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sample_lot', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.sample_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterSampleLotLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sample_lot', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.sample_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterSampleLotLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sample_lot', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.note_to_clerk
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNoteToClerk(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note_to_clerk', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.note_to_clerk from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNoteToClerk(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note_to_clerk', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.note_to_clerk
     * @return static
     */
    public function groupByNoteToClerk(): static
    {
        $this->group($this->alias . '.note_to_clerk');
        return $this;
    }

    /**
     * Order by auction_lot_item.note_to_clerk
     * @param bool $ascending
     * @return static
     */
    public function orderByNoteToClerk(bool $ascending = true): static
    {
        $this->order($this->alias . '.note_to_clerk', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.note_to_clerk by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNoteToClerk(string $filterValue): static
    {
        $this->like($this->alias . '.note_to_clerk', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.general_note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGeneralNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.general_note', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.general_note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGeneralNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.general_note', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.general_note
     * @return static
     */
    public function groupByGeneralNote(): static
    {
        $this->group($this->alias . '.general_note');
        return $this;
    }

    /**
     * Order by auction_lot_item.general_note
     * @param bool $ascending
     * @return static
     */
    public function orderByGeneralNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.general_note', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.general_note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeGeneralNote(string $filterValue): static
    {
        $this->like($this->alias . '.general_note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.track_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTrackCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.track_code', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.track_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTrackCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.track_code', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.track_code
     * @return static
     */
    public function groupByTrackCode(): static
    {
        $this->group($this->alias . '.track_code');
        return $this;
    }

    /**
     * Order by auction_lot_item.track_code
     * @param bool $ascending
     * @return static
     */
    public function orderByTrackCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.track_code', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.track_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTrackCode(string $filterValue): static
    {
        $this->like($this->alias . '.track_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_lot_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_lot_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_lot_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_lot_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.quantity
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterQuantity(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.quantity from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipQuantity(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.quantity
     * @return static
     */
    public function groupByQuantity(): static
    {
        $this->group($this->alias . '.quantity');
        return $this;
    }

    /**
     * Order by auction_lot_item.quantity
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantity(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.quantity_digits
     * @return static
     */
    public function groupByQuantityDigits(): static
    {
        $this->group($this->alias . '.quantity_digits');
        return $this;
    }

    /**
     * Order by auction_lot_item.quantity_digits
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityDigits(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_digits', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.quantity_x_money
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterQuantityXMoney(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_x_money', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.quantity_x_money from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipQuantityXMoney(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_x_money', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.quantity_x_money
     * @return static
     */
    public function groupByQuantityXMoney(): static
    {
        $this->group($this->alias . '.quantity_x_money');
        return $this;
    }

    /**
     * Order by auction_lot_item.quantity_x_money
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityXMoney(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_x_money', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.quantity_x_money
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityXMoneyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_x_money', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.buy_now
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNow(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.buy_now from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNow(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.buy_now
     * @return static
     */
    public function groupByBuyNow(): static
    {
        $this->group($this->alias . '.buy_now');
        return $this;
    }

    /**
     * Order by auction_lot_item.buy_now
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNow(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.buy_now_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyNowAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.buy_now_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyNowAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_amount', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.buy_now_amount
     * @return static
     */
    public function groupByBuyNowAmount(): static
    {
        $this->group($this->alias . '.buy_now_amount');
        return $this;
    }

    /**
     * Order by auction_lot_item.buy_now_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.buy_now_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBuyNowAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.text_msg_notified
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTextMsgNotified(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_notified', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.text_msg_notified from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTextMsgNotified(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_notified', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.text_msg_notified
     * @return static
     */
    public function groupByTextMsgNotified(): static
    {
        $this->group($this->alias . '.text_msg_notified');
        return $this;
    }

    /**
     * Order by auction_lot_item.text_msg_notified
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgNotified(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_notified', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.text_msg_notified
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgNotifiedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_notified', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.text_msg_notified
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgNotifiedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_notified', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.text_msg_notified
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgNotifiedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_notified', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.text_msg_notified
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgNotifiedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_notified', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by auction_lot_item.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.terms_and_conditions
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTermsAndConditions(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.terms_and_conditions', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.terms_and_conditions from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTermsAndConditions(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.terms_and_conditions', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.terms_and_conditions
     * @return static
     */
    public function groupByTermsAndConditions(): static
    {
        $this->group($this->alias . '.terms_and_conditions');
        return $this;
    }

    /**
     * Order by auction_lot_item.terms_and_conditions
     * @param bool $ascending
     * @return static
     */
    public function orderByTermsAndConditions(bool $ascending = true): static
    {
        $this->order($this->alias . '.terms_and_conditions', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.terms_and_conditions by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTermsAndConditions(string $filterValue): static
    {
        $this->like($this->alias . '.terms_and_conditions', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.is_bulk_master
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIsBulkMaster(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.is_bulk_master', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.is_bulk_master from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIsBulkMaster(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.is_bulk_master', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.is_bulk_master
     * @return static
     */
    public function groupByIsBulkMaster(): static
    {
        $this->group($this->alias . '.is_bulk_master');
        return $this;
    }

    /**
     * Order by auction_lot_item.is_bulk_master
     * @param bool $ascending
     * @return static
     */
    public function orderByIsBulkMaster(bool $ascending = true): static
    {
        $this->order($this->alias . '.is_bulk_master', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.is_bulk_master
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBulkMasterGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_bulk_master', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.is_bulk_master
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBulkMasterGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_bulk_master', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.is_bulk_master
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBulkMasterLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_bulk_master', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.is_bulk_master
     * @param bool $filterValue
     * @return static
     */
    public function filterIsBulkMasterLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_bulk_master', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.bulk_master_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBulkMasterId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bulk_master_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.bulk_master_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBulkMasterId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bulk_master_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.bulk_master_id
     * @return static
     */
    public function groupByBulkMasterId(): static
    {
        $this->group($this->alias . '.bulk_master_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.bulk_master_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBulkMasterId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bulk_master_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.bulk_master_id
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.bulk_master_id
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.bulk_master_id
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.bulk_master_id
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.bulk_master_win_bid_distribution
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBulkMasterWinBidDistribution(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bulk_master_win_bid_distribution', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.bulk_master_win_bid_distribution from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBulkMasterWinBidDistribution(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bulk_master_win_bid_distribution', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.bulk_master_win_bid_distribution
     * @return static
     */
    public function groupByBulkMasterWinBidDistribution(): static
    {
        $this->group($this->alias . '.bulk_master_win_bid_distribution');
        return $this;
    }

    /**
     * Order by auction_lot_item.bulk_master_win_bid_distribution
     * @param bool $ascending
     * @return static
     */
    public function orderByBulkMasterWinBidDistribution(bool $ascending = true): static
    {
        $this->order($this->alias . '.bulk_master_win_bid_distribution', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.bulk_master_win_bid_distribution
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterWinBidDistributionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_win_bid_distribution', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.bulk_master_win_bid_distribution
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterWinBidDistributionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_win_bid_distribution', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.bulk_master_win_bid_distribution
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterWinBidDistributionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_win_bid_distribution', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.bulk_master_win_bid_distribution
     * @param int $filterValue
     * @return static
     */
    public function filterBulkMasterWinBidDistributionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_master_win_bid_distribution', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.listing_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterListingOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.listing_only', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.listing_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipListingOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.listing_only', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.listing_only
     * @return static
     */
    public function groupByListingOnly(): static
    {
        $this->group($this->alias . '.listing_only');
        return $this;
    }

    /**
     * Order by auction_lot_item.listing_only
     * @param bool $ascending
     * @return static
     */
    public function orderByListingOnly(bool $ascending = true): static
    {
        $this->order($this->alias . '.listing_only', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.seo_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_url', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.seo_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_url', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.seo_url
     * @return static
     */
    public function groupBySeoUrl(): static
    {
        $this->group($this->alias . '.seo_url');
        return $this;
    }

    /**
     * Order by auction_lot_item.seo_url
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_url', $ascending);
        return $this;
    }

    /**
     * Filter auction_lot_item.seo_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoUrl(string $filterValue): static
    {
        $this->like($this->alias . '.seo_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_lot_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_lot_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.publish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.publish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.publish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.publish_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.publish_date
     * @return static
     */
    public function groupByPublishDate(): static
    {
        $this->group($this->alias . '.publish_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.publish_date
     * @param bool $ascending
     * @return static
     */
    public function orderByPublishDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.publish_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.start_bidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartBiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_bidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.start_bidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartBiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_bidding_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.start_bidding_date
     * @return static
     */
    public function groupByStartBiddingDate(): static
    {
        $this->group($this->alias . '.start_bidding_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.start_bidding_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartBiddingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_bidding_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.end_prebidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndPrebiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_prebidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.end_prebidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndPrebiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_prebidding_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.end_prebidding_date
     * @return static
     */
    public function groupByEndPrebiddingDate(): static
    {
        $this->group($this->alias . '.end_prebidding_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.end_prebidding_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndPrebiddingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_prebidding_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.unpublish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterUnpublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.unpublish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.unpublish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipUnpublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.unpublish_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.unpublish_date
     * @return static
     */
    public function groupByUnpublishDate(): static
    {
        $this->group($this->alias . '.unpublish_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.unpublish_date
     * @param bool $ascending
     * @return static
     */
    public function orderByUnpublishDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.unpublish_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.timezone_id
     * @return static
     */
    public function groupByTimezoneId(): static
    {
        $this->group($this->alias . '.timezone_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.timezone_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneId(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.start_closing_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartClosingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_closing_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.start_closing_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartClosingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_closing_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.start_closing_date
     * @return static
     */
    public function groupByStartClosingDate(): static
    {
        $this->group($this->alias . '.start_closing_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.start_closing_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartClosingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_closing_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.start_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.start_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.start_date
     * @return static
     */
    public function groupByStartDate(): static
    {
        $this->group($this->alias . '.start_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.start_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.end_date
     * @return static
     */
    public function groupByEndDate(): static
    {
        $this->group($this->alias . '.end_date');
        return $this;
    }

    /**
     * Order by auction_lot_item.end_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.buy_now_select_quantity_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_select_quantity_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.buy_now_select_quantity_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowSelectQuantityEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_select_quantity_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.buy_now_select_quantity_enabled
     * @return static
     */
    public function groupByBuyNowSelectQuantityEnabled(): static
    {
        $this->group($this->alias . '.buy_now_select_quantity_enabled');
        return $this;
    }

    /**
     * Order by auction_lot_item.buy_now_select_quantity_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNowSelectQuantityEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now_select_quantity_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.buy_now_select_quantity_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now_select_quantity_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.consignor_commission_id
     * @return static
     */
    public function groupByConsignorCommissionId(): static
    {
        $this->group($this->alias . '.consignor_commission_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.consignor_commission_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorCommissionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_commission_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.consignor_sold_fee_id
     * @return static
     */
    public function groupByConsignorSoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_sold_fee_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.consignor_sold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorSoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_sold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.consignor_unsold_fee_id
     * @return static
     */
    public function groupByConsignorUnsoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_unsold_fee_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.consignor_unsold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorUnsoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_unsold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.hp_tax_schema_id
     * @return static
     */
    public function groupByHpTaxSchemaId(): static
    {
        $this->group($this->alias . '.hp_tax_schema_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.hp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_lot_item.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_lot_item.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_lot_item.bp_tax_schema_id
     * @return static
     */
    public function groupByBpTaxSchemaId(): static
    {
        $this->group($this->alias . '.bp_tax_schema_id');
        return $this;
    }

    /**
     * Order by auction_lot_item.bp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_lot_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<=');
        return $this;
    }
}
