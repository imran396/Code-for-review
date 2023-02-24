<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrent;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractRtbCurrentDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_RTB_CURRENT;
    protected string $alias = Db::A_RTB_CURRENT;

    /**
     * Filter by rtb_current.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.lot_active
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotActive(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_active', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_active from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotActive(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_active', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.new_bid_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNewBidBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.new_bid_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.new_bid_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNewBidBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.new_bid_by', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.ask_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAskBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ask_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.ask_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAskBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ask_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.absentee_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.absentee_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.auto_start
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoStart(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_start', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.auto_start from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoStart(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_start', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.enter_floor_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEnterFloorNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enter_floor_no', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.enter_floor_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEnterFloorNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enter_floor_no', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.next_lot
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNextLot(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.next_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.next_lot from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNextLot(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.next_lot', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.increment', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.lot_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_group', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_group', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.group_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGroupUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.group_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.group_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGroupUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.group_user', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.default_increment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterDefaultIncrement(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_increment', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.default_increment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipDefaultIncrement(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_increment', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.enable_decrement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableDecrement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_decrement', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.enable_decrement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableDecrement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_decrement', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.buyer_user
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyerUser(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_user', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.buyer_user from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyerUser(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_user', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.bid_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.bid_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_countdown', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.lot_end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_end_date', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.pause_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPauseDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pause_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pause_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPauseDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pause_date', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.fair_warning_sec
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterFairWarningSec(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warning_sec', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.fair_warning_sec from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipFairWarningSec(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warning_sec', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.pending_action
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPendingAction(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pending_action from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPendingAction(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.pending_action_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPendingActionDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action_date', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.pending_action_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPendingActionDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action_date', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.running_interval
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRunningInterval(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.running_interval', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.running_interval from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRunningInterval(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.running_interval', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.extend_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.extend_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.lot_start_gap_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStartGapTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.lot_start_gap_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStartGapTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by rtb_current.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_current.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
