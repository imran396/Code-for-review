<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceAuction;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceAuctionDeleteRepository extends DeleteRepositoryBase
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
}
