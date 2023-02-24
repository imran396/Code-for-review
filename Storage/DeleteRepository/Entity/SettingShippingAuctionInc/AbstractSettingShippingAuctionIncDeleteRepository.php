<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingShippingAuctionInc;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingShippingAuctionIncDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SHIPPING_AUCTION_INC;
    protected string $alias = Db::A_SETTING_SHIPPING_AUCTION_INC;

    /**
     * Filter by setting_shipping_auction_inc.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_account_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAucIncAccountId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_account_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAucIncAccountId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_business_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAucIncBusinessId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_business_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_business_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAucIncBusinessId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_business_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_method
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAucIncMethod(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_method', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_method from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAucIncMethod(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_method', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_pickup
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAucIncPickup(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_pickup', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_pickup from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAucIncPickup(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_pickup', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_ups
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAucIncUps(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_ups', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_ups from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAucIncUps(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_ups', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_usps
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAucIncUsps(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_usps', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_usps from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAucIncUsps(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_usps', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_dhl
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAucIncDhl(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_dhl', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_dhl from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAucIncDhl(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_dhl', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_fedex
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAucIncFedex(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_fedex', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_fedex from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAucIncFedex(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_fedex', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncDhlAccessKey(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_dhl_access_key', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_dhl_access_key from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncDhlAccessKey(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_dhl_access_key', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncDhlPostalCode(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_dhl_postal_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_dhl_postal_code from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncDhlPostalCode(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_dhl_postal_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncWeightCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_weight_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_weight_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncWeightCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_weight_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncWidthCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_width_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_width_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncWidthCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_width_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncHeightCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_height_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_height_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncHeightCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_height_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAucIncLengthCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_length_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_length_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAucIncLengthCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_length_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_weight_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAucIncWeightType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_weight_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_weight_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAucIncWeightType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_weight_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.auc_inc_dimension_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAucIncDimensionType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auc_inc_dimension_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.auc_inc_dimension_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAucIncDimensionType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auc_inc_dimension_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_shipping_auction_inc.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_shipping_auction_inc.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
