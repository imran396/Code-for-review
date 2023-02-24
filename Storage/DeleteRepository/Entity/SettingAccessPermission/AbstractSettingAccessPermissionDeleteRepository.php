<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingAccessPermission;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingAccessPermissionDeleteRepository extends DeleteRepositoryBase
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
}
