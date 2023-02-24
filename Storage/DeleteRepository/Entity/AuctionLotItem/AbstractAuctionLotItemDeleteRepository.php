<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionLotItemDeleteRepository extends DeleteRepositoryBase
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
}
