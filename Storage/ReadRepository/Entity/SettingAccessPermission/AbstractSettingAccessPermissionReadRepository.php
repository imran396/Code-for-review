<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAccessPermission;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingAccessPermission;

/**
 * Abstract class AbstractSettingAccessPermissionReadRepository
 * @method SettingAccessPermission[] loadEntities()
 * @method SettingAccessPermission|null loadEntity()
 */
abstract class AbstractSettingAccessPermissionReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_ACCESS_PERMISSION;
    protected string $alias = Db::A_SETTING_ACCESS_PERMISSION;

    /**
     * Filter by setting_access_permission.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_access_permission.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_access_permission.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.auction_visibility_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionVisibilityAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_visibility_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.auction_visibility_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionVisibilityAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_visibility_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.auction_visibility_access
     * @return static
     */
    public function groupByAuctionVisibilityAccess(): static
    {
        $this->group($this->alias . '.auction_visibility_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.auction_visibility_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionVisibilityAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_visibility_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.auction_info_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionInfoAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.auction_info_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionInfoAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.auction_info_access
     * @return static
     */
    public function groupByAuctionInfoAccess(): static
    {
        $this->group($this->alias . '.auction_info_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.auction_info_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionInfoAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_info_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.auction_catalog_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionCatalogAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_catalog_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.auction_catalog_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionCatalogAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_catalog_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.auction_catalog_access
     * @return static
     */
    public function groupByAuctionCatalogAccess(): static
    {
        $this->group($this->alias . '.auction_catalog_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.auction_catalog_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionCatalogAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_catalog_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.live_view_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLiveViewAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_view_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.live_view_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLiveViewAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_view_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.live_view_access
     * @return static
     */
    public function groupByLiveViewAccess(): static
    {
        $this->group($this->alias . '.live_view_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.live_view_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveViewAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_view_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.lot_details_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotDetailsAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_details_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.lot_details_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotDetailsAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_details_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.lot_details_access
     * @return static
     */
    public function groupByLotDetailsAccess(): static
    {
        $this->group($this->alias . '.lot_details_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.lot_details_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotDetailsAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_details_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.lot_bidding_history_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotBiddingHistoryAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_history_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.lot_bidding_history_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotBiddingHistoryAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_history_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.lot_bidding_history_access
     * @return static
     */
    public function groupByLotBiddingHistoryAccess(): static
    {
        $this->group($this->alias . '.lot_bidding_history_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.lot_bidding_history_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotBiddingHistoryAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_bidding_history_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.lot_bidding_info_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotBiddingInfoAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.lot_bidding_info_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotBiddingInfoAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_info_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.lot_bidding_info_access
     * @return static
     */
    public function groupByLotBiddingInfoAccess(): static
    {
        $this->group($this->alias . '.lot_bidding_info_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.lot_bidding_info_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotBiddingInfoAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_bidding_info_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.lot_starting_bid_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotStartingBidAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_starting_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.lot_starting_bid_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotStartingBidAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_starting_bid_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.lot_starting_bid_access
     * @return static
     */
    public function groupByLotStartingBidAccess(): static
    {
        $this->group($this->alias . '.lot_starting_bid_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.lot_starting_bid_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStartingBidAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_starting_bid_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.lot_winning_bid_access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotWinningBidAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_winning_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.lot_winning_bid_access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotWinningBidAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_winning_bid_access', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.lot_winning_bid_access
     * @return static
     */
    public function groupByLotWinningBidAccess(): static
    {
        $this->group($this->alias . '.lot_winning_bid_access');
        return $this;
    }

    /**
     * Order by setting_access_permission.lot_winning_bid_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotWinningBidAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_winning_bid_access', $ascending);
        return $this;
    }

    /**
     * Filter by setting_access_permission.allow_consignor_delete_item
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowConsignorDeleteItem(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_consignor_delete_item', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.allow_consignor_delete_item from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowConsignorDeleteItem(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_consignor_delete_item', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.allow_consignor_delete_item
     * @return static
     */
    public function groupByAllowConsignorDeleteItem(): static
    {
        $this->group($this->alias . '.allow_consignor_delete_item');
        return $this;
    }

    /**
     * Order by setting_access_permission.allow_consignor_delete_item
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowConsignorDeleteItem(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_consignor_delete_item', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.allow_consignor_delete_item
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowConsignorDeleteItemGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_consignor_delete_item', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.allow_consignor_delete_item
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowConsignorDeleteItemGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_consignor_delete_item', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.allow_consignor_delete_item
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowConsignorDeleteItemLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_consignor_delete_item', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.allow_consignor_delete_item
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowConsignorDeleteItemLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_consignor_delete_item', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.auto_consignor_privileges
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoConsignorPrivileges(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_consignor_privileges', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.auto_consignor_privileges from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoConsignorPrivileges(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_consignor_privileges', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.auto_consignor_privileges
     * @return static
     */
    public function groupByAutoConsignorPrivileges(): static
    {
        $this->group($this->alias . '.auto_consignor_privileges');
        return $this;
    }

    /**
     * Order by setting_access_permission.auto_consignor_privileges
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoConsignorPrivileges(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_consignor_privileges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.auto_consignor_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoConsignorPrivilegesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_consignor_privileges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.auto_consignor_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoConsignorPrivilegesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_consignor_privileges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.auto_consignor_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoConsignorPrivilegesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_consignor_privileges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.auto_consignor_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoConsignorPrivilegesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_consignor_privileges', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.auto_assign_account_admin_privileges
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoAssignAccountAdminPrivileges(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_assign_account_admin_privileges', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.auto_assign_account_admin_privileges from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoAssignAccountAdminPrivileges(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_assign_account_admin_privileges', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.auto_assign_account_admin_privileges
     * @return static
     */
    public function groupByAutoAssignAccountAdminPrivileges(): static
    {
        $this->group($this->alias . '.auto_assign_account_admin_privileges');
        return $this;
    }

    /**
     * Order by setting_access_permission.auto_assign_account_admin_privileges
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoAssignAccountAdminPrivileges(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_assign_account_admin_privileges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.auto_assign_account_admin_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoAssignAccountAdminPrivilegesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_assign_account_admin_privileges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.auto_assign_account_admin_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoAssignAccountAdminPrivilegesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_assign_account_admin_privileges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.auto_assign_account_admin_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoAssignAccountAdminPrivilegesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_assign_account_admin_privileges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.auto_assign_account_admin_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoAssignAccountAdminPrivilegesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_assign_account_admin_privileges', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.share_user_info
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterShareUserInfo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.share_user_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.share_user_info from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipShareUserInfo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.share_user_info', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.share_user_info
     * @return static
     */
    public function groupByShareUserInfo(): static
    {
        $this->group($this->alias . '.share_user_info');
        return $this;
    }

    /**
     * Order by setting_access_permission.share_user_info
     * @param bool $ascending
     * @return static
     */
    public function orderByShareUserInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.share_user_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.share_user_info
     * @param int $filterValue
     * @return static
     */
    public function filterShareUserInfoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.share_user_info
     * @param int $filterValue
     * @return static
     */
    public function filterShareUserInfoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.share_user_info
     * @param int $filterValue
     * @return static
     */
    public function filterShareUserInfoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.share_user_info
     * @param int $filterValue
     * @return static
     */
    public function filterShareUserInfoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_info', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.share_user_stats
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShareUserStats(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.share_user_stats', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.share_user_stats from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShareUserStats(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.share_user_stats', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.share_user_stats
     * @return static
     */
    public function groupByShareUserStats(): static
    {
        $this->group($this->alias . '.share_user_stats');
        return $this;
    }

    /**
     * Order by setting_access_permission.share_user_stats
     * @param bool $ascending
     * @return static
     */
    public function orderByShareUserStats(bool $ascending = true): static
    {
        $this->order($this->alias . '.share_user_stats', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.share_user_stats
     * @param bool $filterValue
     * @return static
     */
    public function filterShareUserStatsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_stats', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.share_user_stats
     * @param bool $filterValue
     * @return static
     */
    public function filterShareUserStatsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_stats', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.share_user_stats
     * @param bool $filterValue
     * @return static
     */
    public function filterShareUserStatsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_stats', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.share_user_stats
     * @param bool $filterValue
     * @return static
     */
    public function filterShareUserStatsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.share_user_stats', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.dont_make_user_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDontMakeUserBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.dont_make_user_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.dont_make_user_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDontMakeUserBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.dont_make_user_bidder', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.dont_make_user_bidder
     * @return static
     */
    public function groupByDontMakeUserBidder(): static
    {
        $this->group($this->alias . '.dont_make_user_bidder');
        return $this;
    }

    /**
     * Order by setting_access_permission.dont_make_user_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByDontMakeUserBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.dont_make_user_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.dont_make_user_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterDontMakeUserBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.dont_make_user_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.dont_make_user_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterDontMakeUserBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.dont_make_user_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.dont_make_user_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterDontMakeUserBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.dont_make_user_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.dont_make_user_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterDontMakeUserBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.dont_make_user_bidder', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowAccountAdminAddFloorBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_account_admin_add_floor_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.allow_account_admin_add_floor_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowAccountAdminAddFloorBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_account_admin_add_floor_bidder', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.allow_account_admin_add_floor_bidder
     * @return static
     */
    public function groupByAllowAccountAdminAddFloorBidder(): static
    {
        $this->group($this->alias . '.allow_account_admin_add_floor_bidder');
        return $this;
    }

    /**
     * Order by setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowAccountAdminAddFloorBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_account_admin_add_floor_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminAddFloorBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_add_floor_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminAddFloorBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_add_floor_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminAddFloorBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_add_floor_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.allow_account_admin_add_floor_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminAddFloorBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_add_floor_bidder', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowAccountAdminMakeBidderPreferred(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_account_admin_make_bidder_preferred', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.allow_account_admin_make_bidder_preferred from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowAccountAdminMakeBidderPreferred(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_account_admin_make_bidder_preferred', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.allow_account_admin_make_bidder_preferred
     * @return static
     */
    public function groupByAllowAccountAdminMakeBidderPreferred(): static
    {
        $this->group($this->alias . '.allow_account_admin_make_bidder_preferred');
        return $this;
    }

    /**
     * Order by setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowAccountAdminMakeBidderPreferred(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_account_admin_make_bidder_preferred', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminMakeBidderPreferredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_make_bidder_preferred', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminMakeBidderPreferredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_make_bidder_preferred', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminMakeBidderPreferredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_make_bidder_preferred', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.allow_account_admin_make_bidder_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowAccountAdminMakeBidderPreferredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_account_admin_make_bidder_preferred', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_access_permission.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_access_permission.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_access_permission.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_access_permission.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_access_permission.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_access_permission.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_access_permission.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_access_permission.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_access_permission.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_access_permission.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_access_permission.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_access_permission.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
