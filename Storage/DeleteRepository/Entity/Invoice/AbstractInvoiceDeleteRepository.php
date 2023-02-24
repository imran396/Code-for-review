<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Invoice;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_INVOICE;
    protected string $alias = Db::A_INVOICE;

    /**
     * Filter by invoice.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.invoice_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_no', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.invoice_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_status_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.consignor_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.consignor_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.shipping
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterShipping(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipShipping(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.shipping_note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShippingNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping_note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShippingNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_note', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.exclude_in_threshold
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExcludeInThreshold(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.exclude_in_threshold', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.exclude_in_threshold from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExcludeInThreshold(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.exclude_in_threshold', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.invoice_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterInvoiceDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_date', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.invoice_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipInvoiceDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_date', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.cash_discount
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCashDiscount(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cash_discount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.cash_discount from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCashDiscount(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cash_discount', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.first_sent_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterFirstSentOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.first_sent_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.first_sent_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipFirstSentOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.first_sent_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.sent_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterSentOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sent_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.sent_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipSentOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sent_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bidder_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidderNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_number', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bidder_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidderNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_number', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bid_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBidTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bid_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBidTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax_service
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxService(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_service', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_service from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxService(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_service', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.extra_charges
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExtraCharges(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extra_charges', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.extra_charges from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExtraCharges(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extra_charges', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.shipping_fees
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterShippingFees(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_fees', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.shipping_fees from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipShippingFees(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_fees', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.total_payment
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTotalPayment(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.total_payment', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.total_payment from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTotalPayment(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.total_payment', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.currency_sign
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCurrencySign(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.currency_sign', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.currency_sign from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCurrencySign(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.currency_sign', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.ex_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterExRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ex_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.ex_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipExRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ex_rate', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.internal_note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterInternalNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.internal_note', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.internal_note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipInternalNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.internal_note', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.internal_note_modified
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterInternalNoteModified(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.internal_note_modified', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.internal_note_modified from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipInternalNoteModified(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.internal_note_modified', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax_charges_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxChargesRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_charges_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_charges_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxChargesRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_charges_rate', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax_fees_rate
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxFeesRate(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_fees_rate', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_fees_rate from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxFeesRate(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_fees_rate', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax_designation
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxDesignation(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_designation', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_designation from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxDesignation(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_designation', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.tax_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_country', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.tax_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_country', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.hp_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHpTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHpTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.hp_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.hp_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.hp_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.hp_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.hp_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bp_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBpTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBpTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bp_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bp_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bp_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.bp_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.bp_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.services_tax_total
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterServicesTaxTotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.services_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_tax_total from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipServicesTaxTotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.services_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.services_country_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCountryTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_country_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_country_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCountryTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_country_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.services_state_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesStateTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_state_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_state_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesStateTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_state_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.services_county_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCountyTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_county_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_county_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCountyTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_county_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.services_city_tax_total
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterServicesCityTaxTotal(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.services_city_tax_total', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.services_city_tax_total from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipServicesCityTaxTotal(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.services_city_tax_total', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
