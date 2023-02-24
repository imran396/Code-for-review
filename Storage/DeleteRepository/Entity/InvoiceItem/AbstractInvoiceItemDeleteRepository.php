<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceItem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceItemDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_ITEM;
    protected string $alias = Db::A_INVOICE_ITEM;

    /**
     * Filter by invoice_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.invoice_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.invoice_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_country_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_country_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_country_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountryTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_country_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_state_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpStateTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_state_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_state_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpStateTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_state_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_county_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_county_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_county_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountyTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_county_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_city_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCityTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_city_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_city_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCityTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_city_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.winning_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWinningBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.winning_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.winning_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWinningBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.winning_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_country_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_country_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_country_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountryTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_country_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_state_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpStateTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_state_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_state_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpStateTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_state_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_county_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_county_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_county_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountyTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_county_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_city_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCityTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_city_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_city_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCityTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_city_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.tax_application
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTaxApplication(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_application', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.tax_application from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTaxApplication(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_application', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.subtotal
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSubtotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.subtotal', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.subtotal from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSubtotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.subtotal', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.lot_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_name', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.item_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterItemNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.item_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipItemNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_no', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.lot_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_no', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.quantity
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterQuantity(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.quantity from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipQuantity(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.release
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRelease(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.release', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.release from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRelease(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.release', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
