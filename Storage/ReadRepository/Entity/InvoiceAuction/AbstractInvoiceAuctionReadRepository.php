<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceAuction;

use InvoiceAuction;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceAuctionReadRepository
 * @method InvoiceAuction[] loadEntities()
 * @method InvoiceAuction|null loadEntity()
 */
abstract class AbstractInvoiceAuctionReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_AUCTION;
    protected string $alias = Db::A_INVOICE_AUCTION;

    /**
     * Filter by invoice_auction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice_auction.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.invoice_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.invoice_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by invoice_auction.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by invoice_auction.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.auction_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.auction_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_type', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.auction_type
     * @return static
     */
    public function groupByAuctionType(): static
    {
        $this->group($this->alias . '.auction_type');
        return $this;
    }

    /**
     * Order by invoice_auction.auction_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_type', $ascending);
        return $this;
    }

    /**
     * Filter invoice_auction.auction_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionType(string $filterValue): static
    {
        $this->like($this->alias . '.auction_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_auction.event_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEventType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.event_type', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.event_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEventType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.event_type', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.event_type
     * @return static
     */
    public function groupByEventType(): static
    {
        $this->group($this->alias . '.event_type');
        return $this;
    }

    /**
     * Order by invoice_auction.event_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEventType(bool $ascending = true): static
    {
        $this->order($this->alias . '.event_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.sale_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterSaleDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_date', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.sale_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipSaleDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_date', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.sale_date
     * @return static
     */
    public function groupBySaleDate(): static
    {
        $this->group($this->alias . '.sale_date');
        return $this;
    }

    /**
     * Order by invoice_auction.sale_date
     * @param bool $ascending
     * @return static
     */
    public function orderBySaleDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.sale_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.sale_date
     * @param string $filterValue
     * @return static
     */
    public function filterSaleDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.sale_date
     * @param string $filterValue
     * @return static
     */
    public function filterSaleDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.sale_date
     * @param string $filterValue
     * @return static
     */
    public function filterSaleDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.sale_date
     * @param string $filterValue
     * @return static
     */
    public function filterSaleDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.timezone_location
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTimezoneLocation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_location', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.timezone_location from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTimezoneLocation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_location', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.timezone_location
     * @return static
     */
    public function groupByTimezoneLocation(): static
    {
        $this->group($this->alias . '.timezone_location');
        return $this;
    }

    /**
     * Order by invoice_auction.timezone_location
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneLocation(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_location', $ascending);
        return $this;
    }

    /**
     * Filter invoice_auction.timezone_location by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTimezoneLocation(string $filterValue): static
    {
        $this->like($this->alias . '.timezone_location', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_auction.sale_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSaleNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.sale_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSaleNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_no', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.sale_no
     * @return static
     */
    public function groupBySaleNo(): static
    {
        $this->group($this->alias . '.sale_no');
        return $this;
    }

    /**
     * Order by invoice_auction.sale_no
     * @param bool $ascending
     * @return static
     */
    public function orderBySaleNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.sale_no', $ascending);
        return $this;
    }

    /**
     * Filter invoice_auction.sale_no by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSaleNo(string $filterValue): static
    {
        $this->like($this->alias . '.sale_no', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_auction.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by invoice_auction.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_auction.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_auction.bidder_num
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBidderNum(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_num', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.bidder_num from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBidderNum(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_num', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.bidder_num
     * @return static
     */
    public function groupByBidderNum(): static
    {
        $this->group($this->alias . '.bidder_num');
        return $this;
    }

    /**
     * Order by invoice_auction.bidder_num
     * @param bool $ascending
     * @return static
     */
    public function orderByBidderNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidder_num', $ascending);
        return $this;
    }

    /**
     * Filter invoice_auction.bidder_num by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidderNum(string $filterValue): static
    {
        $this->like($this->alias . '.bidder_num', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_auction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_auction.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_auction.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_auction.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_auction.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_auction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_auction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_auction.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_auction.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
