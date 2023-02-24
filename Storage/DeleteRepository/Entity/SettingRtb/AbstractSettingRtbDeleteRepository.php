<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingRtb;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingRtbDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_RTB;
    protected string $alias = Db::A_SETTING_RTB;

    /**
     * Filter by setting_rtb.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.switch_frame_seconds
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSwitchFrameSeconds(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.switch_frame_seconds', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.switch_frame_seconds from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSwitchFrameSeconds(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.switch_frame_seconds', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.slideshow_projector_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSlideshowProjectorOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.slideshow_projector_only', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.slideshow_projector_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSlideshowProjectorOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.slideshow_projector_only', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.default_image_preview
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterDefaultImagePreview(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_image_preview', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.default_image_preview from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipDefaultImagePreview(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_image_preview', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.live_bidding_countdown
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLiveBiddingCountdown(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_bidding_countdown', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_bidding_countdown from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLiveBiddingCountdown(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_bidding_countdown', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.show_port_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowPortNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_port_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.show_port_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowPortNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_port_notice', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.place_bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPlaceBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.place_bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.place_bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPlaceBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.place_bid_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.bid_accepted_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidAcceptedSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_accepted_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.bid_accepted_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidAcceptedSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_accepted_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.out_bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOutBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.out_bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.out_bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOutBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.out_bid_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.sold_not_won_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSoldNotWonSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sold_not_won_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.sold_not_won_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSoldNotWonSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sold_not_won_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.sold_won_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSoldWonSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sold_won_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.sold_won_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSoldWonSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sold_won_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.passed_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPassedSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.passed_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.passed_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPassedSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.passed_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.fair_warning_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFairWarningSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warning_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.fair_warning_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFairWarningSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warning_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.online_bid_incoming_on_admin_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOnlineBidIncomingOnAdminSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.online_bid_incoming_on_admin_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.online_bid_incoming_on_admin_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOnlineBidIncomingOnAdminSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.online_bid_incoming_on_admin_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.bid_sound
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidSound(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_sound', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.bid_sound from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidSound(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_sound', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.onlinebid_button_info
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOnlinebidButtonInfo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.onlinebid_button_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.onlinebid_button_info from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOnlinebidButtonInfo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.onlinebid_button_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.live_chat
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLiveChat(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_chat', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_chat from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLiveChat(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_chat', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.live_chat_view_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLiveChatViewAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_chat_view_all', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.live_chat_view_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLiveChatViewAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_chat_view_all', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.clear_message_center
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterClearMessageCenter(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clear_message_center', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.clear_message_center from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipClearMessageCenter(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clear_message_center', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.twenty_messages_max
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTwentyMessagesMax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.twenty_messages_max', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.twenty_messages_max from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTwentyMessagesMax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.twenty_messages_max', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.clear_message_center_log
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterClearMessageCenterLog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clear_message_center_log', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.clear_message_center_log from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipClearMessageCenterLog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clear_message_center_log', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.multi_currency
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMultiCurrency(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.multi_currency', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.multi_currency from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMultiCurrency(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.multi_currency', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_sold_item
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelaySoldItem(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_sold_item', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_sold_item from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelaySoldItem(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_sold_item', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_block_sell
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelayBlockSell(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_block_sell', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_block_sell from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelayBlockSell(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_block_sell', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.delay_after_bid_accepted
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDelayAfterBidAccepted(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.delay_after_bid_accepted', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.delay_after_bid_accepted from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDelayAfterBidAccepted(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.delay_after_bid_accepted', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.enable_consignor_company_clerking
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableConsignorCompanyClerking(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_consignor_company_clerking', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.enable_consignor_company_clerking from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableConsignorCompanyClerking(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_consignor_company_clerking', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.floor_bidders_from_dropdown
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFloorBiddersFromDropdown(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.floor_bidders_from_dropdown', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.floor_bidders_from_dropdown from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFloorBiddersFromDropdown(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.floor_bidders_from_dropdown', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.buy_now_restriction
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBuyNowRestriction(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_restriction', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.buy_now_restriction from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBuyNowRestriction(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_restriction', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.suggested_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSuggestedStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.suggested_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.suggested_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSuggestedStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.suggested_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.auto_create_floor_bidder_record
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoCreateFloorBidderRecord(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_create_floor_bidder_record', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.auto_create_floor_bidder_record from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoCreateFloorBidderRecord(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_create_floor_bidder_record', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.allow_bidding_during_start_gap_hybrid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapHybrid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_bidding_during_start_gap_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.allow_bidding_during_start_gap_hybrid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowBiddingDuringStartGapHybrid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_bidding_during_start_gap_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.reset_timer_on_undo_hybrid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResetTimerOnUndoHybrid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reset_timer_on_undo_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.reset_timer_on_undo_hybrid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResetTimerOnUndoHybrid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reset_timer_on_undo_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.extend_time_hybrid
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTimeHybrid(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.extend_time_hybrid from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTimeHybrid(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.pending_action_timeout_hybrid
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPendingActionTimeoutHybrid(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pending_action_timeout_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.pending_action_timeout_hybrid from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPendingActionTimeoutHybrid(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pending_action_timeout_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.fair_warnings_hybrid
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFairWarningsHybrid(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fair_warnings_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.fair_warnings_hybrid from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFairWarningsHybrid(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fair_warnings_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.lot_start_gap_time_hybrid
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotStartGapTimeHybrid(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time_hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.lot_start_gap_time_hybrid from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotStartGapTimeHybrid(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time_hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_rtb.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_rtb.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
