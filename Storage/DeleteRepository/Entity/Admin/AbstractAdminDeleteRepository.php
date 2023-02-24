<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Admin;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAdminDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_ADMIN;
    protected string $alias = Db::A_ADMIN;

    /**
     * Filter by admin.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.admin_privileges
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAdminPrivileges(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_privileges', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.admin_privileges from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAdminPrivileges(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_privileges', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.sales_commission_stepdown
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSalesCommissionStepdown(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_commission_stepdown', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.sales_commission_stepdown from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSalesCommissionStepdown(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_commission_stepdown', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.gen_invoice_by_sold_date
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterGenInvoiceBySoldDate(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.gen_invoice_by_sold_date', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.gen_invoice_by_sold_date from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipGenInvoiceBySoldDate(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.gen_invoice_by_sold_date', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.manage_all_auctions
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterManageAllAuctions(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.manage_all_auctions', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.manage_all_auctions from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipManageAllAuctions(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.manage_all_auctions', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.delete_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDeleteAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.delete_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.delete_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDeleteAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.delete_auction', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.archive_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterArchiveAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.archive_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.archive_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipArchiveAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.archive_auction', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.reset_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResetAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reset_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.reset_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResetAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reset_auction', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.information
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInformation(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.information', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.information from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInformation(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.information', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.publish
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPublish(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.publish', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.publish from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPublish(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.publish', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lots', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lots', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.available_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAvailableLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.available_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.available_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAvailableLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.available_lots', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidders', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.remaining_users
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRemainingUsers(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.remaining_users', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.remaining_users from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRemainingUsers(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.remaining_users', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.run_live_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRunLiveAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.run_live_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.run_live_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRunLiveAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.run_live_auction', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.auctioneer_screen
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuctioneerScreen(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auctioneer_screen', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.auctioneer_screen from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuctioneerScreen(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auctioneer_screen', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.projector
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterProjector(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.projector', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.projector from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipProjector(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.projector', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.bid_increments
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBidIncrements(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_increments', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.bid_increments from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBidIncrements(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_increments', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.buyers_premium
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyersPremium(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.buyers_premium from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyersPremium(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.permissions
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPermissions(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.permissions', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.permissions from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPermissions(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.permissions', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.create_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCreateBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.create_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.create_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCreateBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.create_bidder', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.user_passwords
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUserPasswords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_passwords', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.user_passwords from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUserPasswords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_passwords', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.bulk_user_export
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBulkUserExport(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bulk_user_export', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.bulk_user_export from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBulkUserExport(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bulk_user_export', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.user_privileges
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUserPrivileges(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_privileges', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.user_privileges from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUserPrivileges(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_privileges', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.delete_user
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDeleteUser(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.delete_user', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.delete_user from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDeleteUser(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.delete_user', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by admin.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out admin.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
