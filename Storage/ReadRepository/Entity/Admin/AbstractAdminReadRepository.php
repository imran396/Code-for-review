<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Admin;

use Admin;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAdminReadRepository
 * @method Admin[] loadEntities()
 * @method Admin|null loadEntity()
 */
abstract class AbstractAdminReadRepository extends ReadRepositoryBase
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
     * Group by admin.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by admin.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by admin.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by admin.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by admin.admin_privileges
     * @return static
     */
    public function groupByAdminPrivileges(): static
    {
        $this->group($this->alias . '.admin_privileges');
        return $this;
    }

    /**
     * Order by admin.admin_privileges
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminPrivileges(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_privileges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.admin_privileges
     * @param int $filterValue
     * @return static
     */
    public function filterAdminPrivilegesGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_privileges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.admin_privileges
     * @param int $filterValue
     * @return static
     */
    public function filterAdminPrivilegesGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_privileges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.admin_privileges
     * @param int $filterValue
     * @return static
     */
    public function filterAdminPrivilegesLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_privileges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.admin_privileges
     * @param int $filterValue
     * @return static
     */
    public function filterAdminPrivilegesLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_privileges', $filterValue, '<=');
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
     * Group by admin.sales_commission_stepdown
     * @return static
     */
    public function groupBySalesCommissionStepdown(): static
    {
        $this->group($this->alias . '.sales_commission_stepdown');
        return $this;
    }

    /**
     * Order by admin.sales_commission_stepdown
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesCommissionStepdown(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_commission_stepdown', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.sales_commission_stepdown
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesCommissionStepdownGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_commission_stepdown', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.sales_commission_stepdown
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesCommissionStepdownGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_commission_stepdown', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.sales_commission_stepdown
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesCommissionStepdownLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_commission_stepdown', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.sales_commission_stepdown
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesCommissionStepdownLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_commission_stepdown', $filterValue, '<=');
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
     * Group by admin.gen_invoice_by_sold_date
     * @return static
     */
    public function groupByGenInvoiceBySoldDate(): static
    {
        $this->group($this->alias . '.gen_invoice_by_sold_date');
        return $this;
    }

    /**
     * Order by admin.gen_invoice_by_sold_date
     * @param bool $ascending
     * @return static
     */
    public function orderByGenInvoiceBySoldDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.gen_invoice_by_sold_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.gen_invoice_by_sold_date
     * @param bool $filterValue
     * @return static
     */
    public function filterGenInvoiceBySoldDateGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.gen_invoice_by_sold_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.gen_invoice_by_sold_date
     * @param bool $filterValue
     * @return static
     */
    public function filterGenInvoiceBySoldDateGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.gen_invoice_by_sold_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.gen_invoice_by_sold_date
     * @param bool $filterValue
     * @return static
     */
    public function filterGenInvoiceBySoldDateLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.gen_invoice_by_sold_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.gen_invoice_by_sold_date
     * @param bool $filterValue
     * @return static
     */
    public function filterGenInvoiceBySoldDateLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.gen_invoice_by_sold_date', $filterValue, '<=');
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
     * Group by admin.manage_all_auctions
     * @return static
     */
    public function groupByManageAllAuctions(): static
    {
        $this->group($this->alias . '.manage_all_auctions');
        return $this;
    }

    /**
     * Order by admin.manage_all_auctions
     * @param bool $ascending
     * @return static
     */
    public function orderByManageAllAuctions(bool $ascending = true): static
    {
        $this->order($this->alias . '.manage_all_auctions', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.manage_all_auctions
     * @param bool $filterValue
     * @return static
     */
    public function filterManageAllAuctionsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manage_all_auctions', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.manage_all_auctions
     * @param bool $filterValue
     * @return static
     */
    public function filterManageAllAuctionsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manage_all_auctions', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.manage_all_auctions
     * @param bool $filterValue
     * @return static
     */
    public function filterManageAllAuctionsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manage_all_auctions', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.manage_all_auctions
     * @param bool $filterValue
     * @return static
     */
    public function filterManageAllAuctionsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manage_all_auctions', $filterValue, '<=');
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
     * Group by admin.delete_auction
     * @return static
     */
    public function groupByDeleteAuction(): static
    {
        $this->group($this->alias . '.delete_auction');
        return $this;
    }

    /**
     * Order by admin.delete_auction
     * @param bool $ascending
     * @return static
     */
    public function orderByDeleteAuction(bool $ascending = true): static
    {
        $this->order($this->alias . '.delete_auction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.delete_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteAuctionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_auction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.delete_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteAuctionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_auction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.delete_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteAuctionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_auction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.delete_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteAuctionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_auction', $filterValue, '<=');
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
     * Group by admin.archive_auction
     * @return static
     */
    public function groupByArchiveAuction(): static
    {
        $this->group($this->alias . '.archive_auction');
        return $this;
    }

    /**
     * Order by admin.archive_auction
     * @param bool $ascending
     * @return static
     */
    public function orderByArchiveAuction(bool $ascending = true): static
    {
        $this->order($this->alias . '.archive_auction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.archive_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterArchiveAuctionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.archive_auction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.archive_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterArchiveAuctionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.archive_auction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.archive_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterArchiveAuctionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.archive_auction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.archive_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterArchiveAuctionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.archive_auction', $filterValue, '<=');
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
     * Group by admin.reset_auction
     * @return static
     */
    public function groupByResetAuction(): static
    {
        $this->group($this->alias . '.reset_auction');
        return $this;
    }

    /**
     * Order by admin.reset_auction
     * @param bool $ascending
     * @return static
     */
    public function orderByResetAuction(bool $ascending = true): static
    {
        $this->order($this->alias . '.reset_auction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.reset_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterResetAuctionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_auction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.reset_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterResetAuctionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_auction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.reset_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterResetAuctionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_auction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.reset_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterResetAuctionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reset_auction', $filterValue, '<=');
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
     * Group by admin.information
     * @return static
     */
    public function groupByInformation(): static
    {
        $this->group($this->alias . '.information');
        return $this;
    }

    /**
     * Order by admin.information
     * @param bool $ascending
     * @return static
     */
    public function orderByInformation(bool $ascending = true): static
    {
        $this->order($this->alias . '.information', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.information
     * @param bool $filterValue
     * @return static
     */
    public function filterInformationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.information', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.information
     * @param bool $filterValue
     * @return static
     */
    public function filterInformationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.information', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.information
     * @param bool $filterValue
     * @return static
     */
    public function filterInformationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.information', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.information
     * @param bool $filterValue
     * @return static
     */
    public function filterInformationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.information', $filterValue, '<=');
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
     * Group by admin.publish
     * @return static
     */
    public function groupByPublish(): static
    {
        $this->group($this->alias . '.publish');
        return $this;
    }

    /**
     * Order by admin.publish
     * @param bool $ascending
     * @return static
     */
    public function orderByPublish(bool $ascending = true): static
    {
        $this->order($this->alias . '.publish', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.publish
     * @param bool $filterValue
     * @return static
     */
    public function filterPublishGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.publish
     * @param bool $filterValue
     * @return static
     */
    public function filterPublishGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.publish
     * @param bool $filterValue
     * @return static
     */
    public function filterPublishLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.publish
     * @param bool $filterValue
     * @return static
     */
    public function filterPublishLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish', $filterValue, '<=');
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
     * Group by admin.lots
     * @return static
     */
    public function groupByLots(): static
    {
        $this->group($this->alias . '.lots');
        return $this;
    }

    /**
     * Order by admin.lots
     * @param bool $ascending
     * @return static
     */
    public function orderByLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.lots
     * @param bool $filterValue
     * @return static
     */
    public function filterLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.lots
     * @param bool $filterValue
     * @return static
     */
    public function filterLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.lots
     * @param bool $filterValue
     * @return static
     */
    public function filterLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.lots
     * @param bool $filterValue
     * @return static
     */
    public function filterLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots', $filterValue, '<=');
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
     * Group by admin.available_lots
     * @return static
     */
    public function groupByAvailableLots(): static
    {
        $this->group($this->alias . '.available_lots');
        return $this;
    }

    /**
     * Order by admin.available_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByAvailableLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.available_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.available_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterAvailableLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.available_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.available_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterAvailableLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.available_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.available_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterAvailableLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.available_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.available_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterAvailableLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.available_lots', $filterValue, '<=');
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
     * Group by admin.bidders
     * @return static
     */
    public function groupByBidders(): static
    {
        $this->group($this->alias . '.bidders');
        return $this;
    }

    /**
     * Order by admin.bidders
     * @param bool $ascending
     * @return static
     */
    public function orderByBidders(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidders', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddersGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddersGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddersLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddersLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidders', $filterValue, '<=');
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
     * Group by admin.remaining_users
     * @return static
     */
    public function groupByRemainingUsers(): static
    {
        $this->group($this->alias . '.remaining_users');
        return $this;
    }

    /**
     * Order by admin.remaining_users
     * @param bool $ascending
     * @return static
     */
    public function orderByRemainingUsers(bool $ascending = true): static
    {
        $this->order($this->alias . '.remaining_users', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.remaining_users
     * @param bool $filterValue
     * @return static
     */
    public function filterRemainingUsersGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.remaining_users', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.remaining_users
     * @param bool $filterValue
     * @return static
     */
    public function filterRemainingUsersGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.remaining_users', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.remaining_users
     * @param bool $filterValue
     * @return static
     */
    public function filterRemainingUsersLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.remaining_users', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.remaining_users
     * @param bool $filterValue
     * @return static
     */
    public function filterRemainingUsersLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.remaining_users', $filterValue, '<=');
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
     * Group by admin.run_live_auction
     * @return static
     */
    public function groupByRunLiveAuction(): static
    {
        $this->group($this->alias . '.run_live_auction');
        return $this;
    }

    /**
     * Order by admin.run_live_auction
     * @param bool $ascending
     * @return static
     */
    public function orderByRunLiveAuction(bool $ascending = true): static
    {
        $this->order($this->alias . '.run_live_auction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.run_live_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterRunLiveAuctionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.run_live_auction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.run_live_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterRunLiveAuctionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.run_live_auction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.run_live_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterRunLiveAuctionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.run_live_auction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.run_live_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterRunLiveAuctionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.run_live_auction', $filterValue, '<=');
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
     * Group by admin.auctioneer_screen
     * @return static
     */
    public function groupByAuctioneerScreen(): static
    {
        $this->group($this->alias . '.auctioneer_screen');
        return $this;
    }

    /**
     * Order by admin.auctioneer_screen
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctioneerScreen(bool $ascending = true): static
    {
        $this->order($this->alias . '.auctioneer_screen', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.auctioneer_screen
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerScreenGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_screen', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.auctioneer_screen
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerScreenGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_screen', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.auctioneer_screen
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerScreenLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_screen', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.auctioneer_screen
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerScreenLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_screen', $filterValue, '<=');
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
     * Group by admin.projector
     * @return static
     */
    public function groupByProjector(): static
    {
        $this->group($this->alias . '.projector');
        return $this;
    }

    /**
     * Order by admin.projector
     * @param bool $ascending
     * @return static
     */
    public function orderByProjector(bool $ascending = true): static
    {
        $this->order($this->alias . '.projector', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.projector
     * @param bool $filterValue
     * @return static
     */
    public function filterProjectorGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.projector', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.projector
     * @param bool $filterValue
     * @return static
     */
    public function filterProjectorGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.projector', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.projector
     * @param bool $filterValue
     * @return static
     */
    public function filterProjectorLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.projector', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.projector
     * @param bool $filterValue
     * @return static
     */
    public function filterProjectorLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.projector', $filterValue, '<=');
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
     * Group by admin.bid_increments
     * @return static
     */
    public function groupByBidIncrements(): static
    {
        $this->group($this->alias . '.bid_increments');
        return $this;
    }

    /**
     * Order by admin.bid_increments
     * @param bool $ascending
     * @return static
     */
    public function orderByBidIncrements(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_increments', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.bid_increments
     * @param bool $filterValue
     * @return static
     */
    public function filterBidIncrementsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_increments', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.bid_increments
     * @param bool $filterValue
     * @return static
     */
    public function filterBidIncrementsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_increments', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.bid_increments
     * @param bool $filterValue
     * @return static
     */
    public function filterBidIncrementsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_increments', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.bid_increments
     * @param bool $filterValue
     * @return static
     */
    public function filterBidIncrementsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_increments', $filterValue, '<=');
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
     * Group by admin.buyers_premium
     * @return static
     */
    public function groupByBuyersPremium(): static
    {
        $this->group($this->alias . '.buyers_premium');
        return $this;
    }

    /**
     * Order by admin.buyers_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.buyers_premium
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.buyers_premium
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.buyers_premium
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyersPremiumLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.buyers_premium
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyersPremiumLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<=');
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
     * Group by admin.permissions
     * @return static
     */
    public function groupByPermissions(): static
    {
        $this->group($this->alias . '.permissions');
        return $this;
    }

    /**
     * Order by admin.permissions
     * @param bool $ascending
     * @return static
     */
    public function orderByPermissions(bool $ascending = true): static
    {
        $this->order($this->alias . '.permissions', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.permissions
     * @param bool $filterValue
     * @return static
     */
    public function filterPermissionsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.permissions', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.permissions
     * @param bool $filterValue
     * @return static
     */
    public function filterPermissionsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.permissions', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.permissions
     * @param bool $filterValue
     * @return static
     */
    public function filterPermissionsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.permissions', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.permissions
     * @param bool $filterValue
     * @return static
     */
    public function filterPermissionsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.permissions', $filterValue, '<=');
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
     * Group by admin.create_bidder
     * @return static
     */
    public function groupByCreateBidder(): static
    {
        $this->group($this->alias . '.create_bidder');
        return $this;
    }

    /**
     * Order by admin.create_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByCreateBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.create_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.create_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterCreateBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.create_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.create_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterCreateBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.create_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.create_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterCreateBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.create_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.create_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterCreateBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.create_bidder', $filterValue, '<=');
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
     * Group by admin.user_passwords
     * @return static
     */
    public function groupByUserPasswords(): static
    {
        $this->group($this->alias . '.user_passwords');
        return $this;
    }

    /**
     * Order by admin.user_passwords
     * @param bool $ascending
     * @return static
     */
    public function orderByUserPasswords(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_passwords', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.user_passwords
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPasswordsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_passwords', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.user_passwords
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPasswordsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_passwords', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.user_passwords
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPasswordsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_passwords', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.user_passwords
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPasswordsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_passwords', $filterValue, '<=');
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
     * Group by admin.bulk_user_export
     * @return static
     */
    public function groupByBulkUserExport(): static
    {
        $this->group($this->alias . '.bulk_user_export');
        return $this;
    }

    /**
     * Order by admin.bulk_user_export
     * @param bool $ascending
     * @return static
     */
    public function orderByBulkUserExport(bool $ascending = true): static
    {
        $this->order($this->alias . '.bulk_user_export', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.bulk_user_export
     * @param bool $filterValue
     * @return static
     */
    public function filterBulkUserExportGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_user_export', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.bulk_user_export
     * @param bool $filterValue
     * @return static
     */
    public function filterBulkUserExportGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_user_export', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.bulk_user_export
     * @param bool $filterValue
     * @return static
     */
    public function filterBulkUserExportLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_user_export', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.bulk_user_export
     * @param bool $filterValue
     * @return static
     */
    public function filterBulkUserExportLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bulk_user_export', $filterValue, '<=');
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
     * Group by admin.user_privileges
     * @return static
     */
    public function groupByUserPrivileges(): static
    {
        $this->group($this->alias . '.user_privileges');
        return $this;
    }

    /**
     * Order by admin.user_privileges
     * @param bool $ascending
     * @return static
     */
    public function orderByUserPrivileges(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_privileges', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.user_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPrivilegesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_privileges', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.user_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPrivilegesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_privileges', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.user_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPrivilegesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_privileges', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.user_privileges
     * @param bool $filterValue
     * @return static
     */
    public function filterUserPrivilegesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_privileges', $filterValue, '<=');
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
     * Group by admin.delete_user
     * @return static
     */
    public function groupByDeleteUser(): static
    {
        $this->group($this->alias . '.delete_user');
        return $this;
    }

    /**
     * Order by admin.delete_user
     * @param bool $ascending
     * @return static
     */
    public function orderByDeleteUser(bool $ascending = true): static
    {
        $this->order($this->alias . '.delete_user', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.delete_user
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteUserGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_user', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.delete_user
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteUserGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_user', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.delete_user
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteUserLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_user', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.delete_user
     * @param bool $filterValue
     * @return static
     */
    public function filterDeleteUserLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.delete_user', $filterValue, '<=');
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
     * Group by admin.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by admin.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by admin.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by admin.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by admin.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by admin.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by admin.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by admin.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by admin.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by admin.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than admin.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than admin.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than admin.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than admin.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
