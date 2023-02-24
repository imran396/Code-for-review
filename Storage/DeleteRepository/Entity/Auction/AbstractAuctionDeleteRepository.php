<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Auction;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_AUCTION;
    protected string $alias = Db::A_AUCTION;

    /**
     * Filter by auction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.sale_num
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterSaleNum(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_num from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipSaleNum(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_num', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.sale_num_ext
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSaleNumExt(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_num_ext', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_num_ext from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSaleNumExt(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_num_ext', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.terms_and_conditions
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTermsAndConditions(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.terms_and_conditions', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.terms_and_conditions from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTermsAndConditions(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.terms_and_conditions', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.start_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.event_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEventType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.event_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEventType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.event_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.listing_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterListingOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.listing_only', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.listing_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipListingOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.listing_only', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_status_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.cc_threshold_domestic
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCcThresholdDomestic(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_threshold_domestic', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.cc_threshold_domestic from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCcThresholdDomestic(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_threshold_domestic', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.cc_threshold_international
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCcThresholdInternational(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_threshold_international', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.cc_threshold_international from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCcThresholdInternational(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_threshold_international', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_held_in
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionHeldIn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_held_in', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_held_in from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionHeldIn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_held_in', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.stream_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStreamDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stream_display', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.stream_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStreamDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stream_display', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.parcel_choice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterParcelChoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parcel_choice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.parcel_choice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipParcelChoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parcel_choice', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.date_assignment_strategy
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.date_assignment_strategy', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.date_assignment_strategy from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipDateAssignmentStrategy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.date_assignment_strategy', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_auctioneer_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_auctioneer_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_auctioneer_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionAuctioneerId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_auctioneer_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.clerking_style
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterClerkingStyle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clerking_style', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.clerking_style from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipClerkingStyle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clerking_style', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.stagger_closing
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStaggerClosing(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stagger_closing', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.stagger_closing from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStaggerClosing(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stagger_closing', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lots_per_interval
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsPerInterval(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_per_interval', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lots_per_interval from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsPerInterval(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_per_interval', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.gcal_event_key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGcalEventKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.gcal_event_key', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.gcal_event_key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGcalEventKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.gcal_event_key', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.gcal_event_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGcalEventId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.gcal_event_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.gcal_event_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGcalEventId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.gcal_event_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.additional_bp_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.additional_bp_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.bp_range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bp_range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.authorization_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAuthorizationAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.authorization_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.authorization_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAuthorizationAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.authorization_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.sale_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSaleGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_group', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSaleGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_group', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.payment_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaymentTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.payment_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaymentTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.simultaneous
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSimultaneous(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.simultaneous', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.simultaneous from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSimultaneous(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.simultaneous', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.currency
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCurrency(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.currency', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.currency from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCurrency(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.currency', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.tax_percent
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxPercent(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_percent', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.tax_percent from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxPercent(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_percent', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.invoice_location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.invoice_location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_location_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.event_location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEventLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.event_location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEventLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.event_location_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.only_ongoing_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyOngoingLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_ongoing_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.only_ongoing_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyOngoingLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_ongoing_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.not_show_upcoming_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.not_show_upcoming_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.not_show_upcoming_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotShowUpcomingLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.not_show_upcoming_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.notify_x_lots
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNotifyXLots(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_x_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_x_lots from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNotifyXLots(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_x_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.notify_x_minutes
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNotifyXMinutes(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_x_minutes', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_x_minutes from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNotifyXMinutes(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_x_minutes', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.text_msg_notification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgNotification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.text_msg_notification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgNotification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_notification', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.event_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEventId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.event_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEventId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.event_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_type', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.concatenate_lot_order_columns
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumns(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.concatenate_lot_order_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.concatenate_lot_order_columns from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConcatenateLotOrderColumns(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.concatenate_lot_order_columns', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_visibility_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionVisibilityAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_visibility_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_visibility_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionVisibilityAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_visibility_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_info_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionInfoAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_info_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionInfoAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_catalog_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionCatalogAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_catalog_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_catalog_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionCatalogAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_catalog_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.live_view_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLiveViewAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.live_view_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.live_view_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLiveViewAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.live_view_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_details_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotDetailsAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_details_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_details_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotDetailsAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_details_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_bidding_history_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotBiddingHistoryAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_history_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_bidding_history_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotBiddingHistoryAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_history_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_bidding_info_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotBiddingInfoAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_bidding_info_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotBiddingInfoAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_info_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_starting_bid_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotStartingBidAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_starting_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_starting_bid_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotStartingBidAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_starting_bid_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.bidding_paused
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBiddingPaused(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidding_paused', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bidding_paused from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBiddingPaused(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidding_paused', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.default_lot_period
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDefaultLotPeriod(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_lot_period', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.default_lot_period from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDefaultLotPeriod(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_lot_period', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auto_populate_lot_from_category
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategory(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_populate_lot_from_category', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auto_populate_lot_from_category from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPopulateLotFromCategory(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_populate_lot_from_category', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auto_populate_empty_lot_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_populate_empty_lot_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auto_populate_empty_lot_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPopulateEmptyLotNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_populate_empty_lot_num', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.blacklist_phrase
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBlacklistPhrase(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.blacklist_phrase', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.blacklist_phrase from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBlacklistPhrase(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.blacklist_phrase', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.default_lot_postal_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultLotPostalCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_lot_postal_code', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.default_lot_postal_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultLotPostalCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_lot_postal_code', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.require_lot_change_confirmation
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmation(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_lot_change_confirmation', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.require_lot_change_confirmation from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireLotChangeConfirmation(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_lot_change_confirmation', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.exclude_closed_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExcludeClosedLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.exclude_closed_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.exclude_closed_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExcludeClosedLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.exclude_closed_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_winning_bid_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotWinningBidAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_winning_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_winning_bid_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotWinningBidAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_winning_bid_access', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.test_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTestAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.test_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.test_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTestAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.test_auction', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.reverse
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReverse(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reverse', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reverse from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReverse(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reverse', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.invoice_notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterInvoiceNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_notes', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.invoice_notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipInvoiceNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_notes', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.shipping_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShippingInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_info', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.shipping_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShippingInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_info', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.tax_default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.tax_default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_default_country', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.allow_force_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowForceBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_force_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.allow_force_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowForceBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_force_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.take_max_bids_under_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.take_max_bids_under_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.take_max_bids_under_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTakeMaxBidsUnderReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.take_max_bids_under_reserve', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.post_auc_import_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterPostAucImportPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.post_auc_import_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.post_auc_import_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipPostAucImportPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.post_auc_import_premium', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.absentee_bids_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidsDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bids_display', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.absentee_bids_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidsDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bids_display', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_reserve', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.notify_absentee_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_absentee_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_absentee_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyAbsenteeBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_absentee_bidders', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.reserve_not_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveNotMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_not_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reserve_not_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveNotMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_not_met_notice', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.reserve_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reserve_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_met_notice', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.no_lower_maxbid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoLowerMaxbid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_lower_maxbid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.no_lower_maxbid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoLowerMaxbid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_lower_maxbid', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.suggested_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSuggestedStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.suggested_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.suggested_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSuggestedStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.suggested_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.extend_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExtendAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_all', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExtendAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_all', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.extend_from_current_time
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTime(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_from_current_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_from_current_time from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExtendFromCurrentTime(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_from_current_time', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.extend_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.manual_bidder_approval_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.manual_bidder_approval_only', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.manual_bidder_approval_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipManualBidderApprovalOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.manual_bidder_approval_only', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.max_clerk
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMaxClerk(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_clerk', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.max_clerk from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMaxClerk(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_clerk', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_spacing
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotSpacing(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_spacing', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_spacing from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotSpacing(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_spacing', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.fb_og_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_title', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_title', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.fb_og_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_description', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.fb_og_image_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgImageUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_image_url', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_image_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgImageUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_image_url', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.max_outstanding
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxOutstanding(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_outstanding', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.max_outstanding from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxOutstanding(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_outstanding', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.hide_unsold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideUnsoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_unsold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.hide_unsold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideUnsoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_unsold_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.next_bid_button
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNextBidButton(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.next_bid_button', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.next_bid_button from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNextBidButton(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.next_bid_button', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.bidding_console_access_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidding_console_access_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bidding_console_access_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipBiddingConsoleAccessDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidding_console_access_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.lot_start_gap_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStartGapTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_start_gap_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStartGapTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.allow_bidding_during_start_gap
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGap(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_bidding_during_start_gap', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.allow_bidding_during_start_gap from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowBiddingDuringStartGap(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_bidding_during_start_gap', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.auction_info_link
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionInfoLink(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info_link', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_info_link from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionInfoLink(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info_link', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.seo_meta_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_title', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_title', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.seo_meta_keywords
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaKeywords(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_keywords', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_keywords from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaKeywords(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_keywords', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.seo_meta_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_description', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.wavebid_auction_guid
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWavebidAuctionGuid(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.wavebid_auction_guid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.wavebid_auction_guid from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWavebidAuctionGuid(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.wavebid_auction_guid', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.publish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.publish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.publish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.publish_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.start_register_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartRegisterDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_register_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_register_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartRegisterDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_register_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.end_register_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndRegisterDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_register_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_register_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndRegisterDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_register_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.start_bidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartBiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_bidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_bidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartBiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_bidding_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.end_prebidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndPrebiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_prebidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_prebidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndPrebiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_prebidding_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.unpublish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterUnpublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.unpublish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.unpublish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipUnpublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.unpublish_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.start_closing_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartClosingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_closing_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_closing_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartClosingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_closing_date', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction.services_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.services_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.services_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipServicesTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.services_tax_schema_id', $skipValue);
        return $this;
    }
}
