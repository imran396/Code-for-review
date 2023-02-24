<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrentSnapshot;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractRtbCurrentSnapshotDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_RTB_CURRENT_SNAPSHOT;
    protected string $alias = Db::A_RTB_CURRENT_SNAPSHOT;

    /**
     * Filter by rtb_current_snapshot.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.command
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCommand(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.command', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.command from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCommand(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.command', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.rtb_current_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRtbCurrentId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_current_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.rtb_current_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRtbCurrentId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_current_id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_active
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotActive(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_active', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_active from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotActive(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_active', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.new_bid_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNewBidBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.new_bid_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.new_bid_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNewBidBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.new_bid_by', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.ask_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAskBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ask_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.ask_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAskBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ask_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.absentee_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.absentee_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.auto_start
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoStart(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_start', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.auto_start from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoStart(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_start', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.next_lot
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNextLot(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.next_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.next_lot from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNextLot(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.next_lot', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.increment', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.lot_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_group', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.lot_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_group', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.group_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroupUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.group_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroupUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group_user', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.default_increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterDefaultIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.default_increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipDefaultIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_increment', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.enable_decrement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableDecrement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_decrement', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.enable_decrement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableDecrement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_decrement', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.buyer_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyerUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.buyer_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyerUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_user', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.bid_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.bid_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_countdown', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current_snapshot.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current_snapshot.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }
}
