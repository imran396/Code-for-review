<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingShippingAuctionInc;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingShippingAuctionInc;

/**
 * Abstract class AbstractSettingShippingAuctionIncReadRepository
 * @method SettingShippingAuctionInc[] loadEntities()
 * @method SettingShippingAuctionInc|null loadEntity()
 */
abstract class AbstractSettingShippingAuctionIncReadRepository extends ReadRepositoryBase
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
     * Group by setting_shipping_auction_inc.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_account_id
     * @return static
     */
    public function groupByAucIncAccountId(): static
    {
        $this->group($this->alias . '.auc_inc_account_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_account_id', $ascending);
        return $this;
    }

    /**
     * Filter setting_shipping_auction_inc.auc_inc_account_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAucIncAccountId(string $filterValue): static
    {
        $this->like($this->alias . '.auc_inc_account_id', "%{$filterValue}%");
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
     * Group by setting_shipping_auction_inc.auc_inc_business_id
     * @return static
     */
    public function groupByAucIncBusinessId(): static
    {
        $this->group($this->alias . '.auc_inc_business_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_business_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncBusinessId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_business_id', $ascending);
        return $this;
    }

    /**
     * Filter setting_shipping_auction_inc.auc_inc_business_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAucIncBusinessId(string $filterValue): static
    {
        $this->like($this->alias . '.auc_inc_business_id', "%{$filterValue}%");
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
     * Group by setting_shipping_auction_inc.auc_inc_method
     * @return static
     */
    public function groupByAucIncMethod(): static
    {
        $this->group($this->alias . '.auc_inc_method');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_method
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncMethod(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_method', $ascending);
        return $this;
    }

    /**
     * Filter setting_shipping_auction_inc.auc_inc_method by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAucIncMethod(string $filterValue): static
    {
        $this->like($this->alias . '.auc_inc_method', "%{$filterValue}%");
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
     * Group by setting_shipping_auction_inc.auc_inc_pickup
     * @return static
     */
    public function groupByAucIncPickup(): static
    {
        $this->group($this->alias . '.auc_inc_pickup');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_pickup
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncPickup(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_pickup', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_pickup
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncPickupGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_pickup', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_pickup
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncPickupGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_pickup', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_pickup
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncPickupLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_pickup', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_pickup
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncPickupLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_pickup', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_ups
     * @return static
     */
    public function groupByAucIncUps(): static
    {
        $this->group($this->alias . '.auc_inc_ups');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_ups
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncUps(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_ups', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_ups
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUpsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_ups', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_ups
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUpsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_ups', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_ups
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUpsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_ups', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_ups
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUpsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_ups', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_usps
     * @return static
     */
    public function groupByAucIncUsps(): static
    {
        $this->group($this->alias . '.auc_inc_usps');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_usps
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncUsps(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_usps', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_usps
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUspsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_usps', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_usps
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUspsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_usps', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_usps
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUspsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_usps', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_usps
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncUspsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_usps', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_dhl
     * @return static
     */
    public function groupByAucIncDhl(): static
    {
        $this->group($this->alias . '.auc_inc_dhl');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_dhl
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncDhl(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_dhl', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_dhl
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncDhlGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_dhl
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncDhlGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_dhl
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncDhlLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_dhl
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncDhlLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_fedex
     * @return static
     */
    public function groupByAucIncFedex(): static
    {
        $this->group($this->alias . '.auc_inc_fedex');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_fedex
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncFedex(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_fedex', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_fedex
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncFedexGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_fedex', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_fedex
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncFedexGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_fedex', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_fedex
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncFedexLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_fedex', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_fedex
     * @param bool $filterValue
     * @return static
     */
    public function filterAucIncFedexLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_fedex', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @return static
     */
    public function groupByAucIncDhlAccessKey(): static
    {
        $this->group($this->alias . '.auc_inc_dhl_access_key');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncDhlAccessKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_dhl_access_key', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlAccessKeyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_access_key', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlAccessKeyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_access_key', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlAccessKeyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_access_key', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_dhl_access_key
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlAccessKeyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_access_key', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @return static
     */
    public function groupByAucIncDhlPostalCode(): static
    {
        $this->group($this->alias . '.auc_inc_dhl_postal_code');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncDhlPostalCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_dhl_postal_code', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlPostalCodeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_postal_code', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlPostalCodeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_postal_code', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlPostalCodeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_postal_code', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_dhl_postal_code
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDhlPostalCodeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dhl_postal_code', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @return static
     */
    public function groupByAucIncWeightCustFieldId(): static
    {
        $this->group($this->alias . '.auc_inc_weight_cust_field_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncWeightCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_weight_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_weight_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_cust_field_id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @return static
     */
    public function groupByAucIncWidthCustFieldId(): static
    {
        $this->group($this->alias . '.auc_inc_width_cust_field_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncWidthCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_width_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWidthCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_width_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWidthCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_width_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWidthCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_width_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_width_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWidthCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_width_cust_field_id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @return static
     */
    public function groupByAucIncHeightCustFieldId(): static
    {
        $this->group($this->alias . '.auc_inc_height_cust_field_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncHeightCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_height_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncHeightCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_height_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncHeightCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_height_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncHeightCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_height_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_height_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncHeightCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_height_cust_field_id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @return static
     */
    public function groupByAucIncLengthCustFieldId(): static
    {
        $this->group($this->alias . '.auc_inc_length_cust_field_id');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncLengthCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_length_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncLengthCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_length_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncLengthCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_length_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncLengthCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_length_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_length_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncLengthCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_length_cust_field_id', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_weight_type
     * @return static
     */
    public function groupByAucIncWeightType(): static
    {
        $this->group($this->alias . '.auc_inc_weight_type');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_weight_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncWeightType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_weight_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_weight_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_weight_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_weight_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_weight_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncWeightTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_weight_type', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.auc_inc_dimension_type
     * @return static
     */
    public function groupByAucIncDimensionType(): static
    {
        $this->group($this->alias . '.auc_inc_dimension_type');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.auc_inc_dimension_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAucIncDimensionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auc_inc_dimension_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.auc_inc_dimension_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDimensionTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dimension_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.auc_inc_dimension_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDimensionTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dimension_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.auc_inc_dimension_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDimensionTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dimension_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.auc_inc_dimension_type
     * @param int $filterValue
     * @return static
     */
    public function filterAucIncDimensionTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auc_inc_dimension_type', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_shipping_auction_inc.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_shipping_auction_inc.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_shipping_auction_inc.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_shipping_auction_inc.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_shipping_auction_inc.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_shipping_auction_inc.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_shipping_auction_inc.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
