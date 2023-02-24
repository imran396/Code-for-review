<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingAuction;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingAuctionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_AUCTION;
    protected string $alias = Db::A_SETTING_AUCTION;

    /**
     * Filter by setting_auction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.lot_status
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStatus(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_status', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.lot_status from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStatus(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_status', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.auction_links_to
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionLinksTo(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_links_to', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.auction_links_to from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionLinksTo(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_links_to', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.enable_second_chance
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableSecondChance(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_second_chance', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.enable_second_chance from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableSecondChance(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_second_chance', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.show_low_est
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowLowEst(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_low_est', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_low_est from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowLowEst(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_low_est', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.show_high_est
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowHighEst(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_high_est', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_high_est from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowHighEst(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_high_est', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_timed_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmTimedBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_timed_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_timed_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmTimedBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_timed_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_timed_bid_text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterConfirmTimedBidText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_timed_bid_text', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_timed_bid_text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipConfirmTimedBidText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_timed_bid_text', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.allow_multibids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowMultibids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_multibids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_multibids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowMultibids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_multibids', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_multibids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmMultibids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_multibids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_multibids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmMultibids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_multibids', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_multibids_text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterConfirmMultibidsText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_multibids_text', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_multibids_text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipConfirmMultibidsText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_multibids_text', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.hide_bidder_number
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideBidderNumber(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_bidder_number', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_bidder_number from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideBidderNumber(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_bidder_number', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.tell_a_friend
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTellAFriend(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tell_a_friend', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.tell_a_friend from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTellAFriend(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tell_a_friend', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.hide_movetosale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideMovetosale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_movetosale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_movetosale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideMovetosale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_movetosale', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.shipping_info_box
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterShippingInfoBox(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_info_box', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.shipping_info_box from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipShippingInfoBox(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_info_box', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.add_bids_to_watchlist
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAddBidsToWatchlist(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.add_bids_to_watchlist', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.add_bids_to_watchlist from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAddBidsToWatchlist(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.add_bids_to_watchlist', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.max_stored_searches
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMaxStoredSearches(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_stored_searches', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.max_stored_searches from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMaxStoredSearches(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_stored_searches', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.send_results_once
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendResultsOnce(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_results_once', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.send_results_once from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendResultsOnce(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_results_once', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.allow_anyone_to_tell_a_friend
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowAnyoneToTellAFriend(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_anyone_to_tell_a_friend', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_anyone_to_tell_a_friend from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowAnyoneToTellAFriend(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_anyone_to_tell_a_friend', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.visible_auction_statuses
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterVisibleAuctionStatuses(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.visible_auction_statuses', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.visible_auction_statuses from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipVisibleAuctionStatuses(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.visible_auction_statuses', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_address_sale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmAddressSale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_address_sale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_address_sale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmAddressSale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_address_sale', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.confirm_terms_and_conditions_sale
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConfirmTermsAndConditionsSale(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.confirm_terms_and_conditions_sale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.confirm_terms_and_conditions_sale from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConfirmTermsAndConditionsSale(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.confirm_terms_and_conditions_sale', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reg_use_high_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegUseHighBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_use_high_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_use_high_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegUseHighBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_use_high_bidder', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_auto_approve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegConfirmAutoApprove(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_auto_approve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_auto_approve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegConfirmAutoApprove(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_auto_approve', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_page
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegConfirmPage(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_page from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegConfirmPage(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_page', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reg_confirm_page_content
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRegConfirmPageContent(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_confirm_page_content', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reg_confirm_page_content from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRegConfirmPageContent(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_confirm_page_content', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.payment_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaymentTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.payment_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaymentTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.notify_absentee_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_absentee_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.notify_absentee_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyAbsenteeBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_absentee_bidders', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.use_alternate_pdf_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUseAlternatePdfCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.use_alternate_pdf_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.use_alternate_pdf_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUseAlternatePdfCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.use_alternate_pdf_catalog', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.bid_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.bid_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.display_item_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisplayItemNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_item_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_item_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisplayItemNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_item_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.absentee_bids_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidsDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bids_display', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.absentee_bids_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidsDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bids_display', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.show_countdown_seconds
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowCountdownSeconds(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_countdown_seconds', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_countdown_seconds from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowCountdownSeconds(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_countdown_seconds', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_reserve', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.display_quantity
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisplayQuantity(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_quantity from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisplayQuantity(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_quantity', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.quantity_digits
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.quantity_digits from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.hammer_price_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHammerPriceBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hammer_price_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHammerPriceBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price_bp', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.extend_time_timed
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTimeTimed(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time_timed', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.extend_time_timed from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTimeTimed(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time_timed', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.ga_bid_tracking
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterGaBidTracking(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ga_bid_tracking', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.ga_bid_tracking from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipGaBidTracking(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ga_bid_tracking', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.place_bid_require_cc
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPlaceBidRequireCc(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.place_bid_require_cc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.place_bid_require_cc from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPlaceBidRequireCc(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.place_bid_require_cc', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.next_bid_button
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNextBidButton(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.next_bid_button', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.next_bid_button from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNextBidButton(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.next_bid_button', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterOnAuctionRegistration(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipOnAuctionRegistration(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_expires
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationExpires(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_expires', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_expires from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationExpires(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_expires', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.on_auction_registration_auto
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnAuctionRegistrationAuto(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_auction_registration_auto', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.on_auction_registration_auto from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnAuctionRegistrationAuto(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_auction_registration_auto', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.require_reenter_cc
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireReenterCc(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_reenter_cc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.require_reenter_cc from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireReenterCc(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_reenter_cc', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.display_bidder_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDisplayBidderInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.display_bidder_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.display_bidder_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDisplayBidderInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.display_bidder_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.all_user_require_cc_auth
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllUserRequireCcAuth(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.all_user_require_cc_auth', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.all_user_require_cc_auth from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllUserRequireCcAuth(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.all_user_require_cc_auth', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reserve_not_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveNotMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_not_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reserve_not_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveNotMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_not_met_notice', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.reserve_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.reserve_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_met_notice', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.absentee_bid_lot_notification
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidLotNotification(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bid_lot_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.absentee_bid_lot_notification from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidLotNotification(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bid_lot_notification', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.show_auction_starts_ending
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowAuctionStartsEnding(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_auction_starts_ending', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_auction_starts_ending from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowAuctionStartsEnding(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_auction_starts_ending', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.assigned_lots_restriction
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAssignedLotsRestriction(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.assigned_lots_restriction', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.assigned_lots_restriction from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAssignedLotsRestriction(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.assigned_lots_restriction', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.show_winner_in_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowWinnerInCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_winner_in_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.show_winner_in_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowWinnerInCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_winner_in_catalog', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.buy_now_unsold
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowUnsold(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_unsold', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.buy_now_unsold from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowUnsold(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_unsold', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.no_lower_maxbid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoLowerMaxbid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_lower_maxbid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.no_lower_maxbid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoLowerMaxbid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_lower_maxbid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.block_sold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBlockSoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.block_sold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.block_sold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBlockSoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.block_sold_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.blacklist_phrase
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBlacklistPhrase(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.blacklist_phrase', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.blacklist_phrase from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBlacklistPhrase(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.blacklist_phrase', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.conditional_sales
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConditionalSales(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.conditional_sales', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.conditional_sales from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConditionalSales(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.conditional_sales', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.timed_above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimedAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed_above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.timed_above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimedAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed_above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.timed_above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimedAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed_above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.timed_above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimedAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed_above_reserve', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.add_description_in_lot_name_column
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAddDescriptionInLotNameColumn(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.add_description_in_lot_name_column', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.add_description_in_lot_name_column from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAddDescriptionInLotNameColumn(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.add_description_in_lot_name_column', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.require_on_inc_bids
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireOnIncBids(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_on_inc_bids', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.require_on_inc_bids from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireOnIncBids(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_on_inc_bids', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.inline_bid_confirm
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInlineBidConfirm(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.inline_bid_confirm', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.inline_bid_confirm from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInlineBidConfirm(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.inline_bid_confirm', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.allow_force_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowForceBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_force_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_force_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowForceBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_force_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.take_max_bids_under_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.take_max_bids_under_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.take_max_bids_under_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTakeMaxBidsUnderReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.take_max_bids_under_reserve', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.only_one_reg_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyOneRegEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_one_reg_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.only_one_reg_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyOneRegEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_one_reg_email', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.allow_manual_bidder_for_flagged_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowManualBidderForFlaggedBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_manual_bidder_for_flagged_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.allow_manual_bidder_for_flagged_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowManualBidderForFlaggedBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_manual_bidder_for_flagged_bidders', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.item_num_lock
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterItemNumLock(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num_lock', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.item_num_lock from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipItemNumLock(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num_lock', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.hide_unsold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideUnsoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_unsold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.hide_unsold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideUnsoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_unsold_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.auction_domain_mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionDomainMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_domain_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.auction_domain_mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionDomainMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_domain_mode', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_auction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_auction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
