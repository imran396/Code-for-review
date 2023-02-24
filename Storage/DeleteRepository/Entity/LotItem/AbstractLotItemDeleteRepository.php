<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotItemDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_ITEM;
    protected string $alias = Db::A_LOT_ITEM;

    /**
     * Filter by lot_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.consignor_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.invoice_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.invoice_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.item_num
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterItemNum(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.item_num from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipItemNum(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.item_num_ext
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterItemNumExt(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_num_ext', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.item_num_ext from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipItemNumExt(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_num_ext', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.changes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterChanges(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.changes', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.changes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipChanges(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.changes', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.low_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterLowEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.low_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.low_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipLowEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.low_estimate', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.high_estimate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHighEstimate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.high_estimate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.high_estimate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHighEstimate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.high_estimate', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.starting_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterStartingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.starting_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipStartingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.cost
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCost(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cost', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.cost from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCost(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cost', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.replacement_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterReplacementPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.replacement_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.replacement_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipReplacementPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.replacement_price', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.reserve_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterReservePrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.reserve_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipReservePrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_price', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.winning_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWinningBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.winning_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.winning_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWinningBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.winning_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.internet_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInternetBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.internet_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.internet_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInternetBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.internet_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.date_sold
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterDateSold(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.date_sold', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.date_sold from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipDateSold(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.date_sold', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.tax_exempt
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTaxExempt(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_exempt', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.tax_exempt from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTaxExempt(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_exempt', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.no_tax_oos
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTaxOos(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax_oos', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.no_tax_oos from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTaxOos(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax_oos', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.lot_item_tax_arr
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotItemTaxArr(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_tax_arr', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.lot_item_tax_arr from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotItemTaxArr(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_tax_arr', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.returned
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReturned(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.returned', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.returned from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReturned(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.returned', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.warranty
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWarranty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.warranty', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.warranty from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWarranty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.warranty', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notes', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notes', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.changes_timestamp
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterChangesTimestamp(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.changes_timestamp', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.changes_timestamp from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipChangesTimestamp(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.changes_timestamp', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.only_tax_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyTaxBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_tax_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.only_tax_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyTaxBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_tax_bp', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.tax_default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.tax_default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_default_country', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.location_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.auction_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.auction_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.additional_bp_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.additional_bp_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.bp_range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.bp_range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_title', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_title', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_description', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.fb_og_image_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgImageUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_image_url', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.fb_og_image_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgImageUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_image_url', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_title', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_title', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_keywords
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaKeywords(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_keywords', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_keywords from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaKeywords(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_keywords', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.seo_meta_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_description', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.seo_meta_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_description', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.quantity
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterQuantity(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipQuantity(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.quantity_x_money
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterQuantityXMoney(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_x_money', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.quantity_x_money from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipQuantityXMoney(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_x_money', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.buy_now_select_quantity_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNowSelectQuantityEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_select_quantity_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.buy_now_select_quantity_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNowSelectQuantityEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_select_quantity_enabled', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }
}
