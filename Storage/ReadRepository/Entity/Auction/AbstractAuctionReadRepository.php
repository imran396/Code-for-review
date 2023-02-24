<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Auction;

use Auction;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionReadRepository
 * @method Auction[] loadEntities()
 * @method Auction|null loadEntity()
 */
abstract class AbstractAuctionReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION;
    protected string $alias = Db::A_AUCTION;

    /**
     * Filter by auction.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by auction.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.sale_num
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterSaleNum(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_num from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipSaleNum(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_num', $skipValue);
        return $this;
    }

    /**
     * Group by auction.sale_num
     * @return static
     */
    public function groupBySaleNum(): static
    {
        $this->group($this->alias . '.sale_num');
        return $this;
    }

    /**
     * Order by auction.sale_num
     * @param bool $ascending
     * @return static
     */
    public function orderBySaleNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.sale_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.sale_num
     * @param int $filterValue
     * @return static
     */
    public function filterSaleNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.sale_num
     * @param int $filterValue
     * @return static
     */
    public function filterSaleNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.sale_num
     * @param int $filterValue
     * @return static
     */
    public function filterSaleNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.sale_num
     * @param int $filterValue
     * @return static
     */
    public function filterSaleNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.sale_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.sale_num_ext
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSaleNumExt(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_num_ext', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_num_ext from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSaleNumExt(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_num_ext', $skipValue);
        return $this;
    }

    /**
     * Group by auction.sale_num_ext
     * @return static
     */
    public function groupBySaleNumExt(): static
    {
        $this->group($this->alias . '.sale_num_ext');
        return $this;
    }

    /**
     * Order by auction.sale_num_ext
     * @param bool $ascending
     * @return static
     */
    public function orderBySaleNumExt(bool $ascending = true): static
    {
        $this->order($this->alias . '.sale_num_ext', $ascending);
        return $this;
    }

    /**
     * Filter auction.sale_num_ext by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSaleNumExt(string $filterValue): static
    {
        $this->like($this->alias . '.sale_num_ext', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by auction.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by auction.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter auction.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Group by auction.description
     * @return static
     */
    public function groupByDescription(): static
    {
        $this->group($this->alias . '.description');
        return $this;
    }

    /**
     * Order by auction.description
     * @param bool $ascending
     * @return static
     */
    public function orderByDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.description', $ascending);
        return $this;
    }

    /**
     * Filter auction.description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDescription(string $filterValue): static
    {
        $this->like($this->alias . '.description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.terms_and_conditions
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTermsAndConditions(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.terms_and_conditions', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.terms_and_conditions from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTermsAndConditions(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.terms_and_conditions', $skipValue);
        return $this;
    }

    /**
     * Group by auction.terms_and_conditions
     * @return static
     */
    public function groupByTermsAndConditions(): static
    {
        $this->group($this->alias . '.terms_and_conditions');
        return $this;
    }

    /**
     * Order by auction.terms_and_conditions
     * @param bool $ascending
     * @return static
     */
    public function orderByTermsAndConditions(bool $ascending = true): static
    {
        $this->order($this->alias . '.terms_and_conditions', $ascending);
        return $this;
    }

    /**
     * Filter auction.terms_and_conditions by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTermsAndConditions(string $filterValue): static
    {
        $this->like($this->alias . '.terms_and_conditions', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.start_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.start_date
     * @return static
     */
    public function groupByStartDate(): static
    {
        $this->group($this->alias . '.start_date');
        return $this;
    }

    /**
     * Order by auction.start_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.start_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.end_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.end_date
     * @return static
     */
    public function groupByEndDate(): static
    {
        $this->group($this->alias . '.end_date');
        return $this;
    }

    /**
     * Order by auction.end_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.end_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_type
     * @return static
     */
    public function groupByAuctionType(): static
    {
        $this->group($this->alias . '.auction_type');
        return $this;
    }

    /**
     * Order by auction.auction_type
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionType(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_type', $ascending);
        return $this;
    }

    /**
     * Filter auction.auction_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionType(string $filterValue): static
    {
        $this->like($this->alias . '.auction_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.event_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEventType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.event_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEventType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.event_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.event_type
     * @return static
     */
    public function groupByEventType(): static
    {
        $this->group($this->alias . '.event_type');
        return $this;
    }

    /**
     * Order by auction.event_type
     * @param bool $ascending
     * @return static
     */
    public function orderByEventType(bool $ascending = true): static
    {
        $this->order($this->alias . '.event_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.event_type
     * @param int $filterValue
     * @return static
     */
    public function filterEventTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.listing_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterListingOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.listing_only', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.listing_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipListingOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.listing_only', $skipValue);
        return $this;
    }

    /**
     * Group by auction.listing_only
     * @return static
     */
    public function groupByListingOnly(): static
    {
        $this->group($this->alias . '.listing_only');
        return $this;
    }

    /**
     * Order by auction.listing_only
     * @param bool $ascending
     * @return static
     */
    public function orderByListingOnly(bool $ascending = true): static
    {
        $this->order($this->alias . '.listing_only', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.listing_only
     * @param bool $filterValue
     * @return static
     */
    public function filterListingOnlyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.listing_only', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.timezone_id
     * @return static
     */
    public function groupByTimezoneId(): static
    {
        $this->group($this->alias . '.timezone_id');
        return $this;
    }

    /**
     * Order by auction.timezone_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneId(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_status_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_status_id
     * @return static
     */
    public function groupByAuctionStatusId(): static
    {
        $this->group($this->alias . '.auction_status_id');
        return $this;
    }

    /**
     * Order by auction.auction_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.auction_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.auction_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.auction_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.auction_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_status_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.cc_threshold_domestic
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCcThresholdDomestic(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_threshold_domestic', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.cc_threshold_domestic from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCcThresholdDomestic(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_threshold_domestic', $skipValue);
        return $this;
    }

    /**
     * Group by auction.cc_threshold_domestic
     * @return static
     */
    public function groupByCcThresholdDomestic(): static
    {
        $this->group($this->alias . '.cc_threshold_domestic');
        return $this;
    }

    /**
     * Order by auction.cc_threshold_domestic
     * @param bool $ascending
     * @return static
     */
    public function orderByCcThresholdDomestic(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_threshold_domestic', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.cc_threshold_domestic
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdDomesticGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_domestic', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.cc_threshold_domestic
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdDomesticGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_domestic', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.cc_threshold_domestic
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdDomesticLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_domestic', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.cc_threshold_domestic
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdDomesticLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_domestic', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.cc_threshold_international
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCcThresholdInternational(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_threshold_international', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.cc_threshold_international from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCcThresholdInternational(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_threshold_international', $skipValue);
        return $this;
    }

    /**
     * Group by auction.cc_threshold_international
     * @return static
     */
    public function groupByCcThresholdInternational(): static
    {
        $this->group($this->alias . '.cc_threshold_international');
        return $this;
    }

    /**
     * Order by auction.cc_threshold_international
     * @param bool $ascending
     * @return static
     */
    public function orderByCcThresholdInternational(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_threshold_international', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.cc_threshold_international
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdInternationalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_international', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.cc_threshold_international
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdInternationalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_international', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.cc_threshold_international
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdInternationalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_international', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.cc_threshold_international
     * @param float $filterValue
     * @return static
     */
    public function filterCcThresholdInternationalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_threshold_international', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_held_in
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionHeldIn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_held_in', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_held_in from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionHeldIn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_held_in', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_held_in
     * @return static
     */
    public function groupByAuctionHeldIn(): static
    {
        $this->group($this->alias . '.auction_held_in');
        return $this;
    }

    /**
     * Order by auction.auction_held_in
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionHeldIn(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_held_in', $ascending);
        return $this;
    }

    /**
     * Filter auction.auction_held_in by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionHeldIn(string $filterValue): static
    {
        $this->like($this->alias . '.auction_held_in', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.stream_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStreamDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stream_display', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.stream_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStreamDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stream_display', $skipValue);
        return $this;
    }

    /**
     * Group by auction.stream_display
     * @return static
     */
    public function groupByStreamDisplay(): static
    {
        $this->group($this->alias . '.stream_display');
        return $this;
    }

    /**
     * Order by auction.stream_display
     * @param bool $ascending
     * @return static
     */
    public function orderByStreamDisplay(bool $ascending = true): static
    {
        $this->order($this->alias . '.stream_display', $ascending);
        return $this;
    }

    /**
     * Filter auction.stream_display by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeStreamDisplay(string $filterValue): static
    {
        $this->like($this->alias . '.stream_display', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.parcel_choice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterParcelChoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parcel_choice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.parcel_choice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipParcelChoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parcel_choice', $skipValue);
        return $this;
    }

    /**
     * Group by auction.parcel_choice
     * @return static
     */
    public function groupByParcelChoice(): static
    {
        $this->group($this->alias . '.parcel_choice');
        return $this;
    }

    /**
     * Order by auction.parcel_choice
     * @param bool $ascending
     * @return static
     */
    public function orderByParcelChoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.parcel_choice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.parcel_choice
     * @param bool $filterValue
     * @return static
     */
    public function filterParcelChoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.parcel_choice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.parcel_choice
     * @param bool $filterValue
     * @return static
     */
    public function filterParcelChoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.parcel_choice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.parcel_choice
     * @param bool $filterValue
     * @return static
     */
    public function filterParcelChoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.parcel_choice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.parcel_choice
     * @param bool $filterValue
     * @return static
     */
    public function filterParcelChoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.parcel_choice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.date_assignment_strategy
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.date_assignment_strategy', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.date_assignment_strategy from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipDateAssignmentStrategy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.date_assignment_strategy', $skipValue);
        return $this;
    }

    /**
     * Group by auction.date_assignment_strategy
     * @return static
     */
    public function groupByDateAssignmentStrategy(): static
    {
        $this->group($this->alias . '.date_assignment_strategy');
        return $this;
    }

    /**
     * Order by auction.date_assignment_strategy
     * @param bool $ascending
     * @return static
     */
    public function orderByDateAssignmentStrategy(bool $ascending = true): static
    {
        $this->order($this->alias . '.date_assignment_strategy', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.date_assignment_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_assignment_strategy', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.date_assignment_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_assignment_strategy', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.date_assignment_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_assignment_strategy', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.date_assignment_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterDateAssignmentStrategyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_assignment_strategy', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_auctioneer_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_auctioneer_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_auctioneer_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionAuctioneerId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_auctioneer_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_auctioneer_id
     * @return static
     */
    public function groupByAuctionAuctioneerId(): static
    {
        $this->group($this->alias . '.auction_auctioneer_id');
        return $this;
    }

    /**
     * Order by auction.auction_auctioneer_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionAuctioneerId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_auctioneer_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.auction_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_auctioneer_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.auction_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_auctioneer_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.auction_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_auctioneer_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.auction_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionAuctioneerIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_auctioneer_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.clerking_style
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterClerkingStyle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clerking_style', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.clerking_style from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipClerkingStyle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clerking_style', $skipValue);
        return $this;
    }

    /**
     * Group by auction.clerking_style
     * @return static
     */
    public function groupByClerkingStyle(): static
    {
        $this->group($this->alias . '.clerking_style');
        return $this;
    }

    /**
     * Order by auction.clerking_style
     * @param bool $ascending
     * @return static
     */
    public function orderByClerkingStyle(bool $ascending = true): static
    {
        $this->order($this->alias . '.clerking_style', $ascending);
        return $this;
    }

    /**
     * Filter auction.clerking_style by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeClerkingStyle(string $filterValue): static
    {
        $this->like($this->alias . '.clerking_style', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.stagger_closing
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStaggerClosing(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stagger_closing', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.stagger_closing from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStaggerClosing(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stagger_closing', $skipValue);
        return $this;
    }

    /**
     * Group by auction.stagger_closing
     * @return static
     */
    public function groupByStaggerClosing(): static
    {
        $this->group($this->alias . '.stagger_closing');
        return $this;
    }

    /**
     * Order by auction.stagger_closing
     * @param bool $ascending
     * @return static
     */
    public function orderByStaggerClosing(bool $ascending = true): static
    {
        $this->order($this->alias . '.stagger_closing', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.stagger_closing
     * @param int $filterValue
     * @return static
     */
    public function filterStaggerClosingGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stagger_closing', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.stagger_closing
     * @param int $filterValue
     * @return static
     */
    public function filterStaggerClosingGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stagger_closing', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.stagger_closing
     * @param int $filterValue
     * @return static
     */
    public function filterStaggerClosingLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stagger_closing', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.stagger_closing
     * @param int $filterValue
     * @return static
     */
    public function filterStaggerClosingLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.stagger_closing', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lots_per_interval
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotsPerInterval(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lots_per_interval', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lots_per_interval from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotsPerInterval(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lots_per_interval', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lots_per_interval
     * @return static
     */
    public function groupByLotsPerInterval(): static
    {
        $this->group($this->alias . '.lots_per_interval');
        return $this;
    }

    /**
     * Order by auction.lots_per_interval
     * @param bool $ascending
     * @return static
     */
    public function orderByLotsPerInterval(bool $ascending = true): static
    {
        $this->order($this->alias . '.lots_per_interval', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lots_per_interval
     * @param int $filterValue
     * @return static
     */
    public function filterLotsPerIntervalGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_per_interval', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lots_per_interval
     * @param int $filterValue
     * @return static
     */
    public function filterLotsPerIntervalGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_per_interval', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lots_per_interval
     * @param int $filterValue
     * @return static
     */
    public function filterLotsPerIntervalLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_per_interval', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lots_per_interval
     * @param int $filterValue
     * @return static
     */
    public function filterLotsPerIntervalLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lots_per_interval', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.gcal_event_key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGcalEventKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.gcal_event_key', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.gcal_event_key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGcalEventKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.gcal_event_key', $skipValue);
        return $this;
    }

    /**
     * Group by auction.gcal_event_key
     * @return static
     */
    public function groupByGcalEventKey(): static
    {
        $this->group($this->alias . '.gcal_event_key');
        return $this;
    }

    /**
     * Order by auction.gcal_event_key
     * @param bool $ascending
     * @return static
     */
    public function orderByGcalEventKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.gcal_event_key', $ascending);
        return $this;
    }

    /**
     * Filter auction.gcal_event_key by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeGcalEventKey(string $filterValue): static
    {
        $this->like($this->alias . '.gcal_event_key', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.gcal_event_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGcalEventId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.gcal_event_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.gcal_event_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGcalEventId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.gcal_event_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.gcal_event_id
     * @return static
     */
    public function groupByGcalEventId(): static
    {
        $this->group($this->alias . '.gcal_event_id');
        return $this;
    }

    /**
     * Order by auction.gcal_event_id
     * @param bool $ascending
     * @return static
     */
    public function orderByGcalEventId(bool $ascending = true): static
    {
        $this->order($this->alias . '.gcal_event_id', $ascending);
        return $this;
    }

    /**
     * Filter auction.gcal_event_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeGcalEventId(string $filterValue): static
    {
        $this->like($this->alias . '.gcal_event_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.additional_bp_internet
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAdditionalBpInternet(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.additional_bp_internet', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.additional_bp_internet from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAdditionalBpInternet(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.additional_bp_internet', $skipValue);
        return $this;
    }

    /**
     * Group by auction.additional_bp_internet
     * @return static
     */
    public function groupByAdditionalBpInternet(): static
    {
        $this->group($this->alias . '.additional_bp_internet');
        return $this;
    }

    /**
     * Order by auction.additional_bp_internet
     * @param bool $ascending
     * @return static
     */
    public function orderByAdditionalBpInternet(bool $ascending = true): static
    {
        $this->order($this->alias . '.additional_bp_internet', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.additional_bp_internet
     * @param float $filterValue
     * @return static
     */
    public function filterAdditionalBpInternetLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.additional_bp_internet', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.bp_range_calculation
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBpRangeCalculation(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_range_calculation', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bp_range_calculation from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBpRangeCalculation(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_range_calculation', $skipValue);
        return $this;
    }

    /**
     * Group by auction.bp_range_calculation
     * @return static
     */
    public function groupByBpRangeCalculation(): static
    {
        $this->group($this->alias . '.bp_range_calculation');
        return $this;
    }

    /**
     * Order by auction.bp_range_calculation
     * @param bool $ascending
     * @return static
     */
    public function orderByBpRangeCalculation(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_range_calculation', $ascending);
        return $this;
    }

    /**
     * Filter by auction.buyers_premium_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremiumId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.buyers_premium_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremiumId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.buyers_premium_id
     * @return static
     */
    public function groupByBuyersPremiumId(): static
    {
        $this->group($this->alias . '.buyers_premium_id');
        return $this;
    }

    /**
     * Order by auction.buyers_premium_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremiumId(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.buyers_premium_id
     * @param int $filterValue
     * @return static
     */
    public function filterBuyersPremiumIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Group by auction.email
     * @return static
     */
    public function groupByEmail(): static
    {
        $this->group($this->alias . '.email');
        return $this;
    }

    /**
     * Order by auction.email
     * @param bool $ascending
     * @return static
     */
    public function orderByEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.email', $ascending);
        return $this;
    }

    /**
     * Filter auction.email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmail(string $filterValue): static
    {
        $this->like($this->alias . '.email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.authorization_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAuthorizationAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.authorization_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.authorization_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAuthorizationAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.authorization_amount', $skipValue);
        return $this;
    }

    /**
     * Group by auction.authorization_amount
     * @return static
     */
    public function groupByAuthorizationAmount(): static
    {
        $this->group($this->alias . '.authorization_amount');
        return $this;
    }

    /**
     * Order by auction.authorization_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthorizationAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.authorization_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.authorization_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthorizationAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.authorization_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.authorization_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthorizationAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.authorization_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.authorization_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthorizationAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.authorization_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.authorization_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthorizationAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.authorization_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.sale_group
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSaleGroup(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sale_group', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.sale_group from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSaleGroup(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sale_group', $skipValue);
        return $this;
    }

    /**
     * Group by auction.sale_group
     * @return static
     */
    public function groupBySaleGroup(): static
    {
        $this->group($this->alias . '.sale_group');
        return $this;
    }

    /**
     * Order by auction.sale_group
     * @param bool $ascending
     * @return static
     */
    public function orderBySaleGroup(bool $ascending = true): static
    {
        $this->order($this->alias . '.sale_group', $ascending);
        return $this;
    }

    /**
     * Filter auction.sale_group by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSaleGroup(string $filterValue): static
    {
        $this->like($this->alias . '.sale_group', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.payment_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaymentTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.payment_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaymentTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Group by auction.payment_tracking_code
     * @return static
     */
    public function groupByPaymentTrackingCode(): static
    {
        $this->group($this->alias . '.payment_tracking_code');
        return $this;
    }

    /**
     * Order by auction.payment_tracking_code
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentTrackingCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_tracking_code', $ascending);
        return $this;
    }

    /**
     * Filter auction.payment_tracking_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaymentTrackingCode(string $filterValue): static
    {
        $this->like($this->alias . '.payment_tracking_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.simultaneous
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSimultaneous(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.simultaneous', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.simultaneous from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSimultaneous(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.simultaneous', $skipValue);
        return $this;
    }

    /**
     * Group by auction.simultaneous
     * @return static
     */
    public function groupBySimultaneous(): static
    {
        $this->group($this->alias . '.simultaneous');
        return $this;
    }

    /**
     * Order by auction.simultaneous
     * @param bool $ascending
     * @return static
     */
    public function orderBySimultaneous(bool $ascending = true): static
    {
        $this->order($this->alias . '.simultaneous', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.simultaneous
     * @param bool $filterValue
     * @return static
     */
    public function filterSimultaneousGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simultaneous', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.simultaneous
     * @param bool $filterValue
     * @return static
     */
    public function filterSimultaneousGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simultaneous', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.simultaneous
     * @param bool $filterValue
     * @return static
     */
    public function filterSimultaneousLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simultaneous', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.simultaneous
     * @param bool $filterValue
     * @return static
     */
    public function filterSimultaneousLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simultaneous', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.currency
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCurrency(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.currency', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.currency from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCurrency(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.currency', $skipValue);
        return $this;
    }

    /**
     * Group by auction.currency
     * @return static
     */
    public function groupByCurrency(): static
    {
        $this->group($this->alias . '.currency');
        return $this;
    }

    /**
     * Order by auction.currency
     * @param bool $ascending
     * @return static
     */
    public function orderByCurrency(bool $ascending = true): static
    {
        $this->order($this->alias . '.currency', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.currency
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.currency
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.currency
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.currency
     * @param int $filterValue
     * @return static
     */
    public function filterCurrencyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.currency', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.tax_percent
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterTaxPercent(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_percent', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.tax_percent from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipTaxPercent(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_percent', $skipValue);
        return $this;
    }

    /**
     * Group by auction.tax_percent
     * @return static
     */
    public function groupByTaxPercent(): static
    {
        $this->group($this->alias . '.tax_percent');
        return $this;
    }

    /**
     * Order by auction.tax_percent
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxPercent(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_percent', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.tax_percent
     * @param float $filterValue
     * @return static
     */
    public function filterTaxPercentGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_percent', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.tax_percent
     * @param float $filterValue
     * @return static
     */
    public function filterTaxPercentGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_percent', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.tax_percent
     * @param float $filterValue
     * @return static
     */
    public function filterTaxPercentLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_percent', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.tax_percent
     * @param float $filterValue
     * @return static
     */
    public function filterTaxPercentLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_percent', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.invoice_location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.invoice_location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_location_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.invoice_location_id
     * @return static
     */
    public function groupByInvoiceLocationId(): static
    {
        $this->group($this->alias . '.invoice_location_id');
        return $this;
    }

    /**
     * Order by auction.invoice_location_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceLocationId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_location_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.invoice_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLocationIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_location_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.invoice_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLocationIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_location_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.invoice_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLocationIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_location_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.invoice_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLocationIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_location_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.event_location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEventLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.event_location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEventLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.event_location_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.event_location_id
     * @return static
     */
    public function groupByEventLocationId(): static
    {
        $this->group($this->alias . '.event_location_id');
        return $this;
    }

    /**
     * Order by auction.event_location_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEventLocationId(bool $ascending = true): static
    {
        $this->order($this->alias . '.event_location_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.event_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterEventLocationIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_location_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.event_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterEventLocationIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_location_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.event_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterEventLocationIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_location_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.event_location_id
     * @param int $filterValue
     * @return static
     */
    public function filterEventLocationIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.event_location_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.only_ongoing_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnlyOngoingLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.only_ongoing_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.only_ongoing_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnlyOngoingLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.only_ongoing_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction.only_ongoing_lots
     * @return static
     */
    public function groupByOnlyOngoingLots(): static
    {
        $this->group($this->alias . '.only_ongoing_lots');
        return $this;
    }

    /**
     * Order by auction.only_ongoing_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByOnlyOngoingLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.only_ongoing_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.only_ongoing_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOngoingLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_ongoing_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.only_ongoing_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOngoingLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_ongoing_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.only_ongoing_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOngoingLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_ongoing_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.only_ongoing_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterOnlyOngoingLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.only_ongoing_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.not_show_upcoming_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.not_show_upcoming_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.not_show_upcoming_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotShowUpcomingLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.not_show_upcoming_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction.not_show_upcoming_lots
     * @return static
     */
    public function groupByNotShowUpcomingLots(): static
    {
        $this->group($this->alias . '.not_show_upcoming_lots');
        return $this;
    }

    /**
     * Order by auction.not_show_upcoming_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByNotShowUpcomingLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.not_show_upcoming_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.not_show_upcoming_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.not_show_upcoming_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.not_show_upcoming_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.not_show_upcoming_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.not_show_upcoming_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.not_show_upcoming_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.not_show_upcoming_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterNotShowUpcomingLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.not_show_upcoming_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.notify_x_lots
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNotifyXLots(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_x_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_x_lots from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNotifyXLots(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_x_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction.notify_x_lots
     * @return static
     */
    public function groupByNotifyXLots(): static
    {
        $this->group($this->alias . '.notify_x_lots');
        return $this;
    }

    /**
     * Order by auction.notify_x_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByNotifyXLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.notify_x_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.notify_x_lots
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXLotsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.notify_x_lots
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXLotsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.notify_x_lots
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXLotsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.notify_x_lots
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXLotsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.notify_x_minutes
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNotifyXMinutes(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_x_minutes', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_x_minutes from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNotifyXMinutes(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_x_minutes', $skipValue);
        return $this;
    }

    /**
     * Group by auction.notify_x_minutes
     * @return static
     */
    public function groupByNotifyXMinutes(): static
    {
        $this->group($this->alias . '.notify_x_minutes');
        return $this;
    }

    /**
     * Order by auction.notify_x_minutes
     * @param bool $ascending
     * @return static
     */
    public function orderByNotifyXMinutes(bool $ascending = true): static
    {
        $this->order($this->alias . '.notify_x_minutes', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.notify_x_minutes
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXMinutesGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_minutes', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.notify_x_minutes
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXMinutesGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_minutes', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.notify_x_minutes
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXMinutesLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_minutes', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.notify_x_minutes
     * @param int $filterValue
     * @return static
     */
    public function filterNotifyXMinutesLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_x_minutes', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.text_msg_notification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgNotification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.text_msg_notification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgNotification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_notification', $skipValue);
        return $this;
    }

    /**
     * Group by auction.text_msg_notification
     * @return static
     */
    public function groupByTextMsgNotification(): static
    {
        $this->group($this->alias . '.text_msg_notification');
        return $this;
    }

    /**
     * Order by auction.text_msg_notification
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgNotification(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_notification', $ascending);
        return $this;
    }

    /**
     * Filter auction.text_msg_notification by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTextMsgNotification(string $filterValue): static
    {
        $this->like($this->alias . '.text_msg_notification', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.event_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEventId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.event_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.event_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEventId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.event_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.event_id
     * @return static
     */
    public function groupByEventId(): static
    {
        $this->group($this->alias . '.event_id');
        return $this;
    }

    /**
     * Order by auction.event_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEventId(bool $ascending = true): static
    {
        $this->order($this->alias . '.event_id', $ascending);
        return $this;
    }

    /**
     * Filter auction.event_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEventId(string $filterValue): static
    {
        $this->like($this->alias . '.event_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_primary_type
     * @return static
     */
    public function groupByLotOrderPrimaryType(): static
    {
        $this->group($this->alias . '.lot_order_primary_type');
        return $this;
    }

    /**
     * Order by auction.lot_order_primary_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderPrimaryType(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_primary_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_primary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_primary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_primary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_primary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_primary_cust_field_id
     * @return static
     */
    public function groupByLotOrderPrimaryCustFieldId(): static
    {
        $this->group($this->alias . '.lot_order_primary_cust_field_id');
        return $this;
    }

    /**
     * Order by auction.lot_order_primary_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderPrimaryCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_primary_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_primary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_primary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_primary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_primary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_cust_field_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_primary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_primary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderPrimaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_primary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_primary_ignore_stop_words
     * @return static
     */
    public function groupByLotOrderPrimaryIgnoreStopWords(): static
    {
        $this->group($this->alias . '.lot_order_primary_ignore_stop_words');
        return $this;
    }

    /**
     * Order by auction.lot_order_primary_ignore_stop_words
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderPrimaryIgnoreStopWords(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_primary_ignore_stop_words', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_primary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWordsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_primary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWordsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_primary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWordsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_primary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderPrimaryIgnoreStopWordsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_primary_ignore_stop_words', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_secondary_type
     * @return static
     */
    public function groupByLotOrderSecondaryType(): static
    {
        $this->group($this->alias . '.lot_order_secondary_type');
        return $this;
    }

    /**
     * Order by auction.lot_order_secondary_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderSecondaryType(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_secondary_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_secondary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_secondary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_secondary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_secondary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_secondary_cust_field_id
     * @return static
     */
    public function groupByLotOrderSecondaryCustFieldId(): static
    {
        $this->group($this->alias . '.lot_order_secondary_cust_field_id');
        return $this;
    }

    /**
     * Order by auction.lot_order_secondary_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderSecondaryCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_secondary_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_secondary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_secondary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_secondary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_secondary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_cust_field_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_secondary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_secondary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderSecondaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_secondary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_secondary_ignore_stop_words
     * @return static
     */
    public function groupByLotOrderSecondaryIgnoreStopWords(): static
    {
        $this->group($this->alias . '.lot_order_secondary_ignore_stop_words');
        return $this;
    }

    /**
     * Order by auction.lot_order_secondary_ignore_stop_words
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderSecondaryIgnoreStopWords(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_secondary_ignore_stop_words', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_secondary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWordsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_secondary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWordsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_secondary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWordsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_secondary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderSecondaryIgnoreStopWordsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_secondary_ignore_stop_words', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_tertiary_type
     * @return static
     */
    public function groupByLotOrderTertiaryType(): static
    {
        $this->group($this->alias . '.lot_order_tertiary_type');
        return $this;
    }

    /**
     * Order by auction.lot_order_tertiary_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderTertiaryType(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_tertiary_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_tertiary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_tertiary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_tertiary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_tertiary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_tertiary_cust_field_id
     * @return static
     */
    public function groupByLotOrderTertiaryCustFieldId(): static
    {
        $this->group($this->alias . '.lot_order_tertiary_cust_field_id');
        return $this;
    }

    /**
     * Order by auction.lot_order_tertiary_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderTertiaryCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_tertiary_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_tertiary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_tertiary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_tertiary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_tertiary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_cust_field_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_tertiary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_tertiary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderTertiaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_tertiary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_tertiary_ignore_stop_words
     * @return static
     */
    public function groupByLotOrderTertiaryIgnoreStopWords(): static
    {
        $this->group($this->alias . '.lot_order_tertiary_ignore_stop_words');
        return $this;
    }

    /**
     * Order by auction.lot_order_tertiary_ignore_stop_words
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderTertiaryIgnoreStopWords(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_tertiary_ignore_stop_words', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_tertiary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWordsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_tertiary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWordsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_tertiary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWordsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_tertiary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderTertiaryIgnoreStopWordsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_tertiary_ignore_stop_words', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_type', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_quaternary_type
     * @return static
     */
    public function groupByLotOrderQuaternaryType(): static
    {
        $this->group($this->alias . '.lot_order_quaternary_type');
        return $this;
    }

    /**
     * Order by auction.lot_order_quaternary_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderQuaternaryType(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_quaternary_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_quaternary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_quaternary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_quaternary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_quaternary_type
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_quaternary_cust_field_id
     * @return static
     */
    public function groupByLotOrderQuaternaryCustFieldId(): static
    {
        $this->group($this->alias . '.lot_order_quaternary_cust_field_id');
        return $this;
    }

    /**
     * Order by auction.lot_order_quaternary_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderQuaternaryCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_quaternary_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_quaternary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_quaternary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_quaternary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_quaternary_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_cust_field_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_order_quaternary_ignore_stop_words
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWords(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_order_quaternary_ignore_stop_words from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotOrderQuaternaryIgnoreStopWords(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_order_quaternary_ignore_stop_words', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_order_quaternary_ignore_stop_words
     * @return static
     */
    public function groupByLotOrderQuaternaryIgnoreStopWords(): static
    {
        $this->group($this->alias . '.lot_order_quaternary_ignore_stop_words');
        return $this;
    }

    /**
     * Order by auction.lot_order_quaternary_ignore_stop_words
     * @param bool $ascending
     * @return static
     */
    public function orderByLotOrderQuaternaryIgnoreStopWords(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_order_quaternary_ignore_stop_words', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_order_quaternary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWordsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_order_quaternary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWordsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_order_quaternary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWordsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_order_quaternary_ignore_stop_words
     * @param bool $filterValue
     * @return static
     */
    public function filterLotOrderQuaternaryIgnoreStopWordsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_order_quaternary_ignore_stop_words', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.concatenate_lot_order_columns
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumns(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.concatenate_lot_order_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.concatenate_lot_order_columns from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConcatenateLotOrderColumns(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.concatenate_lot_order_columns', $skipValue);
        return $this;
    }

    /**
     * Group by auction.concatenate_lot_order_columns
     * @return static
     */
    public function groupByConcatenateLotOrderColumns(): static
    {
        $this->group($this->alias . '.concatenate_lot_order_columns');
        return $this;
    }

    /**
     * Order by auction.concatenate_lot_order_columns
     * @param bool $ascending
     * @return static
     */
    public function orderByConcatenateLotOrderColumns(bool $ascending = true): static
    {
        $this->order($this->alias . '.concatenate_lot_order_columns', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.concatenate_lot_order_columns
     * @param bool $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumnsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.concatenate_lot_order_columns', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.concatenate_lot_order_columns
     * @param bool $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumnsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.concatenate_lot_order_columns', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.concatenate_lot_order_columns
     * @param bool $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumnsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.concatenate_lot_order_columns', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.concatenate_lot_order_columns
     * @param bool $filterValue
     * @return static
     */
    public function filterConcatenateLotOrderColumnsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.concatenate_lot_order_columns', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_visibility_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionVisibilityAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_visibility_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_visibility_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionVisibilityAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_visibility_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_visibility_access
     * @return static
     */
    public function groupByAuctionVisibilityAccess(): static
    {
        $this->group($this->alias . '.auction_visibility_access');
        return $this;
    }

    /**
     * Order by auction.auction_visibility_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionVisibilityAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_visibility_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.auction_info_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionInfoAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_info_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionInfoAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_info_access
     * @return static
     */
    public function groupByAuctionInfoAccess(): static
    {
        $this->group($this->alias . '.auction_info_access');
        return $this;
    }

    /**
     * Order by auction.auction_info_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionInfoAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_info_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.auction_catalog_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuctionCatalogAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_catalog_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_catalog_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuctionCatalogAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_catalog_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_catalog_access
     * @return static
     */
    public function groupByAuctionCatalogAccess(): static
    {
        $this->group($this->alias . '.auction_catalog_access');
        return $this;
    }

    /**
     * Order by auction.auction_catalog_access
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionCatalogAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_catalog_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.live_view_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLiveViewAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.live_view_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.live_view_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLiveViewAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.live_view_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.live_view_access
     * @return static
     */
    public function groupByLiveViewAccess(): static
    {
        $this->group($this->alias . '.live_view_access');
        return $this;
    }

    /**
     * Order by auction.live_view_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveViewAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_view_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.lot_details_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotDetailsAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_details_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_details_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotDetailsAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_details_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_details_access
     * @return static
     */
    public function groupByLotDetailsAccess(): static
    {
        $this->group($this->alias . '.lot_details_access');
        return $this;
    }

    /**
     * Order by auction.lot_details_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotDetailsAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_details_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.lot_bidding_history_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotBiddingHistoryAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_history_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_bidding_history_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotBiddingHistoryAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_history_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_bidding_history_access
     * @return static
     */
    public function groupByLotBiddingHistoryAccess(): static
    {
        $this->group($this->alias . '.lot_bidding_history_access');
        return $this;
    }

    /**
     * Order by auction.lot_bidding_history_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotBiddingHistoryAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_bidding_history_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.lot_bidding_info_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotBiddingInfoAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_bidding_info_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_bidding_info_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotBiddingInfoAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_bidding_info_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_bidding_info_access
     * @return static
     */
    public function groupByLotBiddingInfoAccess(): static
    {
        $this->group($this->alias . '.lot_bidding_info_access');
        return $this;
    }

    /**
     * Order by auction.lot_bidding_info_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotBiddingInfoAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_bidding_info_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.lot_starting_bid_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotStartingBidAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_starting_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_starting_bid_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotStartingBidAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_starting_bid_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_starting_bid_access
     * @return static
     */
    public function groupByLotStartingBidAccess(): static
    {
        $this->group($this->alias . '.lot_starting_bid_access');
        return $this;
    }

    /**
     * Order by auction.lot_starting_bid_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStartingBidAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_starting_bid_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.bidding_paused
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBiddingPaused(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bidding_paused', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bidding_paused from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBiddingPaused(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bidding_paused', $skipValue);
        return $this;
    }

    /**
     * Group by auction.bidding_paused
     * @return static
     */
    public function groupByBiddingPaused(): static
    {
        $this->group($this->alias . '.bidding_paused');
        return $this;
    }

    /**
     * Order by auction.bidding_paused
     * @param bool $ascending
     * @return static
     */
    public function orderByBiddingPaused(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidding_paused', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.bidding_paused
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddingPausedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_paused', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.bidding_paused
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddingPausedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_paused', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.bidding_paused
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddingPausedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_paused', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.bidding_paused
     * @param bool $filterValue
     * @return static
     */
    public function filterBiddingPausedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_paused', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.default_lot_period
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterDefaultLotPeriod(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_lot_period', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.default_lot_period from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipDefaultLotPeriod(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_lot_period', $skipValue);
        return $this;
    }

    /**
     * Group by auction.default_lot_period
     * @return static
     */
    public function groupByDefaultLotPeriod(): static
    {
        $this->group($this->alias . '.default_lot_period');
        return $this;
    }

    /**
     * Order by auction.default_lot_period
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultLotPeriod(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_lot_period', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.default_lot_period
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultLotPeriodGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_period', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.default_lot_period
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultLotPeriodGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_period', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.default_lot_period
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultLotPeriodLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_period', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.default_lot_period
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultLotPeriodLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_period', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auto_populate_lot_from_category
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategory(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_populate_lot_from_category', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auto_populate_lot_from_category from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPopulateLotFromCategory(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_populate_lot_from_category', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auto_populate_lot_from_category
     * @return static
     */
    public function groupByAutoPopulateLotFromCategory(): static
    {
        $this->group($this->alias . '.auto_populate_lot_from_category');
        return $this;
    }

    /**
     * Order by auction.auto_populate_lot_from_category
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoPopulateLotFromCategory(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_populate_lot_from_category', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.auto_populate_lot_from_category
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategoryGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_lot_from_category', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.auto_populate_lot_from_category
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategoryGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_lot_from_category', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.auto_populate_lot_from_category
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategoryLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_lot_from_category', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.auto_populate_lot_from_category
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateLotFromCategoryLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_lot_from_category', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auto_populate_empty_lot_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_populate_empty_lot_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auto_populate_empty_lot_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPopulateEmptyLotNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_populate_empty_lot_num', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auto_populate_empty_lot_num
     * @return static
     */
    public function groupByAutoPopulateEmptyLotNum(): static
    {
        $this->group($this->alias . '.auto_populate_empty_lot_num');
        return $this;
    }

    /**
     * Order by auction.auto_populate_empty_lot_num
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoPopulateEmptyLotNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_populate_empty_lot_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.auto_populate_empty_lot_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNumGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_empty_lot_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.auto_populate_empty_lot_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNumGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_empty_lot_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.auto_populate_empty_lot_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNumLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_empty_lot_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.auto_populate_empty_lot_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPopulateEmptyLotNumLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_populate_empty_lot_num', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.blacklist_phrase
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBlacklistPhrase(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.blacklist_phrase', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.blacklist_phrase from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBlacklistPhrase(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.blacklist_phrase', $skipValue);
        return $this;
    }

    /**
     * Group by auction.blacklist_phrase
     * @return static
     */
    public function groupByBlacklistPhrase(): static
    {
        $this->group($this->alias . '.blacklist_phrase');
        return $this;
    }

    /**
     * Order by auction.blacklist_phrase
     * @param bool $ascending
     * @return static
     */
    public function orderByBlacklistPhrase(bool $ascending = true): static
    {
        $this->order($this->alias . '.blacklist_phrase', $ascending);
        return $this;
    }

    /**
     * Filter auction.blacklist_phrase by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBlacklistPhrase(string $filterValue): static
    {
        $this->like($this->alias . '.blacklist_phrase', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.default_lot_postal_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultLotPostalCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_lot_postal_code', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.default_lot_postal_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultLotPostalCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_lot_postal_code', $skipValue);
        return $this;
    }

    /**
     * Group by auction.default_lot_postal_code
     * @return static
     */
    public function groupByDefaultLotPostalCode(): static
    {
        $this->group($this->alias . '.default_lot_postal_code');
        return $this;
    }

    /**
     * Order by auction.default_lot_postal_code
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultLotPostalCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_lot_postal_code', $ascending);
        return $this;
    }

    /**
     * Filter auction.default_lot_postal_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDefaultLotPostalCode(string $filterValue): static
    {
        $this->like($this->alias . '.default_lot_postal_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.require_lot_change_confirmation
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmation(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_lot_change_confirmation', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.require_lot_change_confirmation from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireLotChangeConfirmation(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_lot_change_confirmation', $skipValue);
        return $this;
    }

    /**
     * Group by auction.require_lot_change_confirmation
     * @return static
     */
    public function groupByRequireLotChangeConfirmation(): static
    {
        $this->group($this->alias . '.require_lot_change_confirmation');
        return $this;
    }

    /**
     * Order by auction.require_lot_change_confirmation
     * @param bool $ascending
     * @return static
     */
    public function orderByRequireLotChangeConfirmation(bool $ascending = true): static
    {
        $this->order($this->alias . '.require_lot_change_confirmation', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.require_lot_change_confirmation
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_lot_change_confirmation', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.require_lot_change_confirmation
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_lot_change_confirmation', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.require_lot_change_confirmation
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_lot_change_confirmation', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.require_lot_change_confirmation
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireLotChangeConfirmationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_lot_change_confirmation', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.exclude_closed_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExcludeClosedLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.exclude_closed_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.exclude_closed_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExcludeClosedLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.exclude_closed_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction.exclude_closed_lots
     * @return static
     */
    public function groupByExcludeClosedLots(): static
    {
        $this->group($this->alias . '.exclude_closed_lots');
        return $this;
    }

    /**
     * Order by auction.exclude_closed_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByExcludeClosedLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.exclude_closed_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.exclude_closed_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeClosedLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_closed_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.exclude_closed_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeClosedLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_closed_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.exclude_closed_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeClosedLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_closed_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.exclude_closed_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterExcludeClosedLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.exclude_closed_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_winning_bid_access
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLotWinningBidAccess(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_winning_bid_access', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_winning_bid_access from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLotWinningBidAccess(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_winning_bid_access', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_winning_bid_access
     * @return static
     */
    public function groupByLotWinningBidAccess(): static
    {
        $this->group($this->alias . '.lot_winning_bid_access');
        return $this;
    }

    /**
     * Order by auction.lot_winning_bid_access
     * @param bool $ascending
     * @return static
     */
    public function orderByLotWinningBidAccess(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_winning_bid_access', $ascending);
        return $this;
    }

    /**
     * Filter by auction.test_auction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTestAuction(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.test_auction', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.test_auction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTestAuction(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.test_auction', $skipValue);
        return $this;
    }

    /**
     * Group by auction.test_auction
     * @return static
     */
    public function groupByTestAuction(): static
    {
        $this->group($this->alias . '.test_auction');
        return $this;
    }

    /**
     * Order by auction.test_auction
     * @param bool $ascending
     * @return static
     */
    public function orderByTestAuction(bool $ascending = true): static
    {
        $this->order($this->alias . '.test_auction', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.test_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterTestAuctionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.test_auction', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.test_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterTestAuctionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.test_auction', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.test_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterTestAuctionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.test_auction', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.test_auction
     * @param bool $filterValue
     * @return static
     */
    public function filterTestAuctionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.test_auction', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.reverse
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReverse(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reverse', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reverse from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReverse(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reverse', $skipValue);
        return $this;
    }

    /**
     * Group by auction.reverse
     * @return static
     */
    public function groupByReverse(): static
    {
        $this->group($this->alias . '.reverse');
        return $this;
    }

    /**
     * Order by auction.reverse
     * @param bool $ascending
     * @return static
     */
    public function orderByReverse(bool $ascending = true): static
    {
        $this->order($this->alias . '.reverse', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.reverse
     * @param bool $filterValue
     * @return static
     */
    public function filterReverseGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reverse', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.reverse
     * @param bool $filterValue
     * @return static
     */
    public function filterReverseGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reverse', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.reverse
     * @param bool $filterValue
     * @return static
     */
    public function filterReverseLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reverse', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.reverse
     * @param bool $filterValue
     * @return static
     */
    public function filterReverseLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reverse', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.invoice_notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterInvoiceNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_notes', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.invoice_notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipInvoiceNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_notes', $skipValue);
        return $this;
    }

    /**
     * Group by auction.invoice_notes
     * @return static
     */
    public function groupByInvoiceNotes(): static
    {
        $this->group($this->alias . '.invoice_notes');
        return $this;
    }

    /**
     * Order by auction.invoice_notes
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceNotes(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_notes', $ascending);
        return $this;
    }

    /**
     * Filter auction.invoice_notes by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeInvoiceNotes(string $filterValue): static
    {
        $this->like($this->alias . '.invoice_notes', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.shipping_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShippingInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_info', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.shipping_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShippingInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_info', $skipValue);
        return $this;
    }

    /**
     * Group by auction.shipping_info
     * @return static
     */
    public function groupByShippingInfo(): static
    {
        $this->group($this->alias . '.shipping_info');
        return $this;
    }

    /**
     * Order by auction.shipping_info
     * @param bool $ascending
     * @return static
     */
    public function orderByShippingInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping_info', $ascending);
        return $this;
    }

    /**
     * Filter auction.shipping_info by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeShippingInfo(string $filterValue): static
    {
        $this->like($this->alias . '.shipping_info', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.tax_default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTaxDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.tax_default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTaxDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_default_country', $skipValue);
        return $this;
    }

    /**
     * Group by auction.tax_default_country
     * @return static
     */
    public function groupByTaxDefaultCountry(): static
    {
        $this->group($this->alias . '.tax_default_country');
        return $this;
    }

    /**
     * Order by auction.tax_default_country
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxDefaultCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_default_country', $ascending);
        return $this;
    }

    /**
     * Filter auction.tax_default_country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTaxDefaultCountry(string $filterValue): static
    {
        $this->like($this->alias . '.tax_default_country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.allow_force_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowForceBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_force_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.allow_force_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowForceBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_force_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction.allow_force_bid
     * @return static
     */
    public function groupByAllowForceBid(): static
    {
        $this->group($this->alias . '.allow_force_bid');
        return $this;
    }

    /**
     * Order by auction.allow_force_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowForceBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_force_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.allow_force_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowForceBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_force_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.take_max_bids_under_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.take_max_bids_under_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.take_max_bids_under_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTakeMaxBidsUnderReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.take_max_bids_under_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by auction.take_max_bids_under_reserve
     * @return static
     */
    public function groupByTakeMaxBidsUnderReserve(): static
    {
        $this->group($this->alias . '.take_max_bids_under_reserve');
        return $this;
    }

    /**
     * Order by auction.take_max_bids_under_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByTakeMaxBidsUnderReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.take_max_bids_under_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.take_max_bids_under_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterTakeMaxBidsUnderReserveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.take_max_bids_under_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.post_auc_import_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterPostAucImportPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.post_auc_import_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.post_auc_import_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipPostAucImportPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.post_auc_import_premium', $skipValue);
        return $this;
    }

    /**
     * Group by auction.post_auc_import_premium
     * @return static
     */
    public function groupByPostAucImportPremium(): static
    {
        $this->group($this->alias . '.post_auc_import_premium');
        return $this;
    }

    /**
     * Order by auction.post_auc_import_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByPostAucImportPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.post_auc_import_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.absentee_bids_display
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAbsenteeBidsDisplay(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.absentee_bids_display', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.absentee_bids_display from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAbsenteeBidsDisplay(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.absentee_bids_display', $skipValue);
        return $this;
    }

    /**
     * Group by auction.absentee_bids_display
     * @return static
     */
    public function groupByAbsenteeBidsDisplay(): static
    {
        $this->group($this->alias . '.absentee_bids_display');
        return $this;
    }

    /**
     * Order by auction.absentee_bids_display
     * @param bool $ascending
     * @return static
     */
    public function orderByAbsenteeBidsDisplay(bool $ascending = true): static
    {
        $this->order($this->alias . '.absentee_bids_display', $ascending);
        return $this;
    }

    /**
     * Filter auction.absentee_bids_display by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAbsenteeBidsDisplay(string $filterValue): static
    {
        $this->like($this->alias . '.absentee_bids_display', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.above_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.above_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction.above_starting_bid
     * @return static
     */
    public function groupByAboveStartingBid(): static
    {
        $this->group($this->alias . '.above_starting_bid');
        return $this;
    }

    /**
     * Order by auction.above_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderByAboveStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.above_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.above_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveStartingBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.above_reserve
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAboveReserve(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.above_reserve', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.above_reserve from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAboveReserve(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.above_reserve', $skipValue);
        return $this;
    }

    /**
     * Group by auction.above_reserve
     * @return static
     */
    public function groupByAboveReserve(): static
    {
        $this->group($this->alias . '.above_reserve');
        return $this;
    }

    /**
     * Order by auction.above_reserve
     * @param bool $ascending
     * @return static
     */
    public function orderByAboveReserve(bool $ascending = true): static
    {
        $this->order($this->alias . '.above_reserve', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.above_reserve
     * @param bool $filterValue
     * @return static
     */
    public function filterAboveReserveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.above_reserve', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.notify_absentee_bidders
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBidders(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_absentee_bidders', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.notify_absentee_bidders from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyAbsenteeBidders(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_absentee_bidders', $skipValue);
        return $this;
    }

    /**
     * Group by auction.notify_absentee_bidders
     * @return static
     */
    public function groupByNotifyAbsenteeBidders(): static
    {
        $this->group($this->alias . '.notify_absentee_bidders');
        return $this;
    }

    /**
     * Order by auction.notify_absentee_bidders
     * @param bool $ascending
     * @return static
     */
    public function orderByNotifyAbsenteeBidders(bool $ascending = true): static
    {
        $this->order($this->alias . '.notify_absentee_bidders', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.notify_absentee_bidders
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyAbsenteeBiddersLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_absentee_bidders', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.reserve_not_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveNotMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_not_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reserve_not_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveNotMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_not_met_notice', $skipValue);
        return $this;
    }

    /**
     * Group by auction.reserve_not_met_notice
     * @return static
     */
    public function groupByReserveNotMetNotice(): static
    {
        $this->group($this->alias . '.reserve_not_met_notice');
        return $this;
    }

    /**
     * Order by auction.reserve_not_met_notice
     * @param bool $ascending
     * @return static
     */
    public function orderByReserveNotMetNotice(bool $ascending = true): static
    {
        $this->order($this->alias . '.reserve_not_met_notice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.reserve_not_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveNotMetNoticeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_not_met_notice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.reserve_met_notice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReserveMetNotice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reserve_met_notice', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.reserve_met_notice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReserveMetNotice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reserve_met_notice', $skipValue);
        return $this;
    }

    /**
     * Group by auction.reserve_met_notice
     * @return static
     */
    public function groupByReserveMetNotice(): static
    {
        $this->group($this->alias . '.reserve_met_notice');
        return $this;
    }

    /**
     * Order by auction.reserve_met_notice
     * @param bool $ascending
     * @return static
     */
    public function orderByReserveMetNotice(bool $ascending = true): static
    {
        $this->order($this->alias . '.reserve_met_notice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.reserve_met_notice
     * @param bool $filterValue
     * @return static
     */
    public function filterReserveMetNoticeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reserve_met_notice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.no_lower_maxbid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoLowerMaxbid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_lower_maxbid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.no_lower_maxbid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoLowerMaxbid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_lower_maxbid', $skipValue);
        return $this;
    }

    /**
     * Group by auction.no_lower_maxbid
     * @return static
     */
    public function groupByNoLowerMaxbid(): static
    {
        $this->group($this->alias . '.no_lower_maxbid');
        return $this;
    }

    /**
     * Order by auction.no_lower_maxbid
     * @param bool $ascending
     * @return static
     */
    public function orderByNoLowerMaxbid(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_lower_maxbid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.no_lower_maxbid
     * @param bool $filterValue
     * @return static
     */
    public function filterNoLowerMaxbidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_lower_maxbid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.suggested_starting_bid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSuggestedStartingBid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.suggested_starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.suggested_starting_bid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSuggestedStartingBid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.suggested_starting_bid', $skipValue);
        return $this;
    }

    /**
     * Group by auction.suggested_starting_bid
     * @return static
     */
    public function groupBySuggestedStartingBid(): static
    {
        $this->group($this->alias . '.suggested_starting_bid');
        return $this;
    }

    /**
     * Order by auction.suggested_starting_bid
     * @param bool $ascending
     * @return static
     */
    public function orderBySuggestedStartingBid(bool $ascending = true): static
    {
        $this->order($this->alias . '.suggested_starting_bid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.suggested_starting_bid
     * @param bool $filterValue
     * @return static
     */
    public function filterSuggestedStartingBidLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.suggested_starting_bid', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.extend_all
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExtendAll(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_all', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_all from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExtendAll(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_all', $skipValue);
        return $this;
    }

    /**
     * Group by auction.extend_all
     * @return static
     */
    public function groupByExtendAll(): static
    {
        $this->group($this->alias . '.extend_all');
        return $this;
    }

    /**
     * Order by auction.extend_all
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendAll(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_all', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.extend_all
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendAllGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_all', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.extend_all
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendAllGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_all', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.extend_all
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendAllLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_all', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.extend_all
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendAllLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_all', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.extend_from_current_time
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTime(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_from_current_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_from_current_time from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipExtendFromCurrentTime(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_from_current_time', $skipValue);
        return $this;
    }

    /**
     * Group by auction.extend_from_current_time
     * @return static
     */
    public function groupByExtendFromCurrentTime(): static
    {
        $this->group($this->alias . '.extend_from_current_time');
        return $this;
    }

    /**
     * Order by auction.extend_from_current_time
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendFromCurrentTime(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_from_current_time', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.extend_from_current_time
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTimeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_from_current_time', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.extend_from_current_time
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTimeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_from_current_time', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.extend_from_current_time
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTimeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_from_current_time', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.extend_from_current_time
     * @param bool $filterValue
     * @return static
     */
    public function filterExtendFromCurrentTimeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_from_current_time', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.extend_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterExtendTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.extend_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.extend_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipExtendTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.extend_time', $skipValue);
        return $this;
    }

    /**
     * Group by auction.extend_time
     * @return static
     */
    public function groupByExtendTime(): static
    {
        $this->group($this->alias . '.extend_time');
        return $this;
    }

    /**
     * Order by auction.extend_time
     * @param bool $ascending
     * @return static
     */
    public function orderByExtendTime(bool $ascending = true): static
    {
        $this->order($this->alias . '.extend_time', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.extend_time
     * @param int $filterValue
     * @return static
     */
    public function filterExtendTimeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.extend_time', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.manual_bidder_approval_only
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnly(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.manual_bidder_approval_only', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.manual_bidder_approval_only from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipManualBidderApprovalOnly(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.manual_bidder_approval_only', $skipValue);
        return $this;
    }

    /**
     * Group by auction.manual_bidder_approval_only
     * @return static
     */
    public function groupByManualBidderApprovalOnly(): static
    {
        $this->group($this->alias . '.manual_bidder_approval_only');
        return $this;
    }

    /**
     * Order by auction.manual_bidder_approval_only
     * @param bool $ascending
     * @return static
     */
    public function orderByManualBidderApprovalOnly(bool $ascending = true): static
    {
        $this->order($this->alias . '.manual_bidder_approval_only', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.manual_bidder_approval_only
     * @param bool $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnlyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manual_bidder_approval_only', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.manual_bidder_approval_only
     * @param bool $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnlyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manual_bidder_approval_only', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.manual_bidder_approval_only
     * @param bool $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnlyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manual_bidder_approval_only', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.manual_bidder_approval_only
     * @param bool $filterValue
     * @return static
     */
    public function filterManualBidderApprovalOnlyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.manual_bidder_approval_only', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.max_clerk
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMaxClerk(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_clerk', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.max_clerk from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMaxClerk(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_clerk', $skipValue);
        return $this;
    }

    /**
     * Group by auction.max_clerk
     * @return static
     */
    public function groupByMaxClerk(): static
    {
        $this->group($this->alias . '.max_clerk');
        return $this;
    }

    /**
     * Order by auction.max_clerk
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxClerk(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_clerk', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.max_clerk
     * @param int $filterValue
     * @return static
     */
    public function filterMaxClerkGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_clerk', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.max_clerk
     * @param int $filterValue
     * @return static
     */
    public function filterMaxClerkGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_clerk', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.max_clerk
     * @param int $filterValue
     * @return static
     */
    public function filterMaxClerkLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_clerk', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.max_clerk
     * @param int $filterValue
     * @return static
     */
    public function filterMaxClerkLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_clerk', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_spacing
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotSpacing(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_spacing', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_spacing from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotSpacing(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_spacing', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_spacing
     * @return static
     */
    public function groupByLotSpacing(): static
    {
        $this->group($this->alias . '.lot_spacing');
        return $this;
    }

    /**
     * Order by auction.lot_spacing
     * @param bool $ascending
     * @return static
     */
    public function orderByLotSpacing(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_spacing', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_spacing
     * @param int $filterValue
     * @return static
     */
    public function filterLotSpacingGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_spacing', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_spacing
     * @param int $filterValue
     * @return static
     */
    public function filterLotSpacingGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_spacing', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_spacing
     * @param int $filterValue
     * @return static
     */
    public function filterLotSpacingLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_spacing', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_spacing
     * @param int $filterValue
     * @return static
     */
    public function filterLotSpacingLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_spacing', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.fb_og_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_title', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_title', $skipValue);
        return $this;
    }

    /**
     * Group by auction.fb_og_title
     * @return static
     */
    public function groupByFbOgTitle(): static
    {
        $this->group($this->alias . '.fb_og_title');
        return $this;
    }

    /**
     * Order by auction.fb_og_title
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_title', $ascending);
        return $this;
    }

    /**
     * Filter auction.fb_og_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgTitle(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.fb_og_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_description', $skipValue);
        return $this;
    }

    /**
     * Group by auction.fb_og_description
     * @return static
     */
    public function groupByFbOgDescription(): static
    {
        $this->group($this->alias . '.fb_og_description');
        return $this;
    }

    /**
     * Order by auction.fb_og_description
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_description', $ascending);
        return $this;
    }

    /**
     * Filter auction.fb_og_description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgDescription(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.fb_og_image_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFbOgImageUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fb_og_image_url', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.fb_og_image_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFbOgImageUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fb_og_image_url', $skipValue);
        return $this;
    }

    /**
     * Group by auction.fb_og_image_url
     * @return static
     */
    public function groupByFbOgImageUrl(): static
    {
        $this->group($this->alias . '.fb_og_image_url');
        return $this;
    }

    /**
     * Order by auction.fb_og_image_url
     * @param bool $ascending
     * @return static
     */
    public function orderByFbOgImageUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.fb_og_image_url', $ascending);
        return $this;
    }

    /**
     * Filter auction.fb_og_image_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeFbOgImageUrl(string $filterValue): static
    {
        $this->like($this->alias . '.fb_og_image_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.max_outstanding
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxOutstanding(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_outstanding', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.max_outstanding from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxOutstanding(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_outstanding', $skipValue);
        return $this;
    }

    /**
     * Group by auction.max_outstanding
     * @return static
     */
    public function groupByMaxOutstanding(): static
    {
        $this->group($this->alias . '.max_outstanding');
        return $this;
    }

    /**
     * Order by auction.max_outstanding
     * @param bool $ascending
     * @return static
     */
    public function orderByMaxOutstanding(bool $ascending = true): static
    {
        $this->order($this->alias . '.max_outstanding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.max_outstanding
     * @param float $filterValue
     * @return static
     */
    public function filterMaxOutstandingLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.max_outstanding', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.hide_unsold_lots
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideUnsoldLots(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_unsold_lots', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.hide_unsold_lots from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideUnsoldLots(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_unsold_lots', $skipValue);
        return $this;
    }

    /**
     * Group by auction.hide_unsold_lots
     * @return static
     */
    public function groupByHideUnsoldLots(): static
    {
        $this->group($this->alias . '.hide_unsold_lots');
        return $this;
    }

    /**
     * Order by auction.hide_unsold_lots
     * @param bool $ascending
     * @return static
     */
    public function orderByHideUnsoldLots(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_unsold_lots', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.hide_unsold_lots
     * @param bool $filterValue
     * @return static
     */
    public function filterHideUnsoldLotsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_unsold_lots', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.next_bid_button
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNextBidButton(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.next_bid_button', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.next_bid_button from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNextBidButton(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.next_bid_button', $skipValue);
        return $this;
    }

    /**
     * Group by auction.next_bid_button
     * @return static
     */
    public function groupByNextBidButton(): static
    {
        $this->group($this->alias . '.next_bid_button');
        return $this;
    }

    /**
     * Order by auction.next_bid_button
     * @param bool $ascending
     * @return static
     */
    public function orderByNextBidButton(bool $ascending = true): static
    {
        $this->order($this->alias . '.next_bid_button', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.next_bid_button
     * @param bool $filterValue
     * @return static
     */
    public function filterNextBidButtonLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.next_bid_button', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.bidding_console_access_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidding_console_access_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bidding_console_access_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipBiddingConsoleAccessDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidding_console_access_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.bidding_console_access_date
     * @return static
     */
    public function groupByBiddingConsoleAccessDate(): static
    {
        $this->group($this->alias . '.bidding_console_access_date');
        return $this;
    }

    /**
     * Order by auction.bidding_console_access_date
     * @param bool $ascending
     * @return static
     */
    public function orderByBiddingConsoleAccessDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidding_console_access_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.bidding_console_access_date
     * @param string $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_console_access_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.bidding_console_access_date
     * @param string $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_console_access_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.bidding_console_access_date
     * @param string $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_console_access_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.bidding_console_access_date
     * @param string $filterValue
     * @return static
     */
    public function filterBiddingConsoleAccessDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.bidding_console_access_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.lot_start_gap_time
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotStartGapTime(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_start_gap_time', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.lot_start_gap_time from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotStartGapTime(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_start_gap_time', $skipValue);
        return $this;
    }

    /**
     * Group by auction.lot_start_gap_time
     * @return static
     */
    public function groupByLotStartGapTime(): static
    {
        $this->group($this->alias . '.lot_start_gap_time');
        return $this;
    }

    /**
     * Order by auction.lot_start_gap_time
     * @param bool $ascending
     * @return static
     */
    public function orderByLotStartGapTime(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_start_gap_time', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.lot_start_gap_time
     * @param int $filterValue
     * @return static
     */
    public function filterLotStartGapTimeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_start_gap_time', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.allow_bidding_during_start_gap
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGap(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.allow_bidding_during_start_gap', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.allow_bidding_during_start_gap from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAllowBiddingDuringStartGap(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.allow_bidding_during_start_gap', $skipValue);
        return $this;
    }

    /**
     * Group by auction.allow_bidding_during_start_gap
     * @return static
     */
    public function groupByAllowBiddingDuringStartGap(): static
    {
        $this->group($this->alias . '.allow_bidding_during_start_gap');
        return $this;
    }

    /**
     * Order by auction.allow_bidding_during_start_gap
     * @param bool $ascending
     * @return static
     */
    public function orderByAllowBiddingDuringStartGap(bool $ascending = true): static
    {
        $this->order($this->alias . '.allow_bidding_during_start_gap', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.allow_bidding_during_start_gap
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.allow_bidding_during_start_gap
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.allow_bidding_during_start_gap
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.allow_bidding_during_start_gap
     * @param bool $filterValue
     * @return static
     */
    public function filterAllowBiddingDuringStartGapLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.allow_bidding_during_start_gap', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.auction_info_link
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionInfoLink(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_info_link', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.auction_info_link from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionInfoLink(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_info_link', $skipValue);
        return $this;
    }

    /**
     * Group by auction.auction_info_link
     * @return static
     */
    public function groupByAuctionInfoLink(): static
    {
        $this->group($this->alias . '.auction_info_link');
        return $this;
    }

    /**
     * Order by auction.auction_info_link
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionInfoLink(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_info_link', $ascending);
        return $this;
    }

    /**
     * Filter auction.auction_info_link by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionInfoLink(string $filterValue): static
    {
        $this->like($this->alias . '.auction_info_link', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.seo_meta_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_title', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_title', $skipValue);
        return $this;
    }

    /**
     * Group by auction.seo_meta_title
     * @return static
     */
    public function groupBySeoMetaTitle(): static
    {
        $this->group($this->alias . '.seo_meta_title');
        return $this;
    }

    /**
     * Order by auction.seo_meta_title
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_title', $ascending);
        return $this;
    }

    /**
     * Filter auction.seo_meta_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaTitle(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.seo_meta_keywords
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaKeywords(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_keywords', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_keywords from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaKeywords(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_keywords', $skipValue);
        return $this;
    }

    /**
     * Group by auction.seo_meta_keywords
     * @return static
     */
    public function groupBySeoMetaKeywords(): static
    {
        $this->group($this->alias . '.seo_meta_keywords');
        return $this;
    }

    /**
     * Order by auction.seo_meta_keywords
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaKeywords(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_keywords', $ascending);
        return $this;
    }

    /**
     * Filter auction.seo_meta_keywords by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaKeywords(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_keywords', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.seo_meta_description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSeoMetaDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.seo_meta_description', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.seo_meta_description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSeoMetaDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.seo_meta_description', $skipValue);
        return $this;
    }

    /**
     * Group by auction.seo_meta_description
     * @return static
     */
    public function groupBySeoMetaDescription(): static
    {
        $this->group($this->alias . '.seo_meta_description');
        return $this;
    }

    /**
     * Order by auction.seo_meta_description
     * @param bool $ascending
     * @return static
     */
    public function orderBySeoMetaDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.seo_meta_description', $ascending);
        return $this;
    }

    /**
     * Filter auction.seo_meta_description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSeoMetaDescription(string $filterValue): static
    {
        $this->like($this->alias . '.seo_meta_description', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.wavebid_auction_guid
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWavebidAuctionGuid(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.wavebid_auction_guid', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.wavebid_auction_guid from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWavebidAuctionGuid(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.wavebid_auction_guid', $skipValue);
        return $this;
    }

    /**
     * Group by auction.wavebid_auction_guid
     * @return static
     */
    public function groupByWavebidAuctionGuid(): static
    {
        $this->group($this->alias . '.wavebid_auction_guid');
        return $this;
    }

    /**
     * Order by auction.wavebid_auction_guid
     * @param bool $ascending
     * @return static
     */
    public function orderByWavebidAuctionGuid(bool $ascending = true): static
    {
        $this->order($this->alias . '.wavebid_auction_guid', $ascending);
        return $this;
    }

    /**
     * Filter auction.wavebid_auction_guid by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeWavebidAuctionGuid(string $filterValue): static
    {
        $this->like($this->alias . '.wavebid_auction_guid', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction.publish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.publish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.publish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.publish_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.publish_date
     * @return static
     */
    public function groupByPublishDate(): static
    {
        $this->group($this->alias . '.publish_date');
        return $this;
    }

    /**
     * Order by auction.publish_date
     * @param bool $ascending
     * @return static
     */
    public function orderByPublishDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.publish_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.publish_date
     * @param string $filterValue
     * @return static
     */
    public function filterPublishDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.publish_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.start_register_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartRegisterDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_register_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_register_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartRegisterDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_register_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.start_register_date
     * @return static
     */
    public function groupByStartRegisterDate(): static
    {
        $this->group($this->alias . '.start_register_date');
        return $this;
    }

    /**
     * Order by auction.start_register_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartRegisterDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_register_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.start_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartRegisterDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_register_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.start_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartRegisterDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_register_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.start_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartRegisterDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_register_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.start_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartRegisterDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_register_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.end_register_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndRegisterDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_register_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_register_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndRegisterDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_register_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.end_register_date
     * @return static
     */
    public function groupByEndRegisterDate(): static
    {
        $this->group($this->alias . '.end_register_date');
        return $this;
    }

    /**
     * Order by auction.end_register_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndRegisterDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_register_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.end_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndRegisterDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_register_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.end_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndRegisterDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_register_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.end_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndRegisterDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_register_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.end_register_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndRegisterDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_register_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.start_bidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartBiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_bidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_bidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartBiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_bidding_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.start_bidding_date
     * @return static
     */
    public function groupByStartBiddingDate(): static
    {
        $this->group($this->alias . '.start_bidding_date');
        return $this;
    }

    /**
     * Order by auction.start_bidding_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartBiddingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_bidding_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.start_bidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartBiddingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_bidding_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.end_prebidding_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterEndPrebiddingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.end_prebidding_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.end_prebidding_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipEndPrebiddingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.end_prebidding_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.end_prebidding_date
     * @return static
     */
    public function groupByEndPrebiddingDate(): static
    {
        $this->group($this->alias . '.end_prebidding_date');
        return $this;
    }

    /**
     * Order by auction.end_prebidding_date
     * @param bool $ascending
     * @return static
     */
    public function orderByEndPrebiddingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.end_prebidding_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.end_prebidding_date
     * @param string $filterValue
     * @return static
     */
    public function filterEndPrebiddingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.end_prebidding_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.unpublish_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterUnpublishDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.unpublish_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.unpublish_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipUnpublishDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.unpublish_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.unpublish_date
     * @return static
     */
    public function groupByUnpublishDate(): static
    {
        $this->group($this->alias . '.unpublish_date');
        return $this;
    }

    /**
     * Order by auction.unpublish_date
     * @param bool $ascending
     * @return static
     */
    public function orderByUnpublishDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.unpublish_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.unpublish_date
     * @param string $filterValue
     * @return static
     */
    public function filterUnpublishDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.unpublish_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.start_closing_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStartClosingDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.start_closing_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.start_closing_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStartClosingDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.start_closing_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction.start_closing_date
     * @return static
     */
    public function groupByStartClosingDate(): static
    {
        $this->group($this->alias . '.start_closing_date');
        return $this;
    }

    /**
     * Order by auction.start_closing_date
     * @param bool $ascending
     * @return static
     */
    public function orderByStartClosingDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.start_closing_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.start_closing_date
     * @param string $filterValue
     * @return static
     */
    public function filterStartClosingDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.start_closing_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.consignor_commission_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorCommissionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_commission_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_commission_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorCommissionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_commission_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.consignor_commission_id
     * @return static
     */
    public function groupByConsignorCommissionId(): static
    {
        $this->group($this->alias . '.consignor_commission_id');
        return $this;
    }

    /**
     * Order by auction.consignor_commission_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorCommissionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_commission_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.consignor_commission_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorCommissionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_commission_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.consignor_sold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_sold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_sold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorSoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_sold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.consignor_sold_fee_id
     * @return static
     */
    public function groupByConsignorSoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_sold_fee_id');
        return $this;
    }

    /**
     * Order by auction.consignor_sold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorSoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_sold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.consignor_sold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorSoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_sold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.consignor_unsold_fee_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_unsold_fee_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.consignor_unsold_fee_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorUnsoldFeeId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_unsold_fee_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.consignor_unsold_fee_id
     * @return static
     */
    public function groupByConsignorUnsoldFeeId(): static
    {
        $this->group($this->alias . '.consignor_unsold_fee_id');
        return $this;
    }

    /**
     * Order by auction.consignor_unsold_fee_id
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorUnsoldFeeId(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_unsold_fee_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.consignor_unsold_fee_id
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorUnsoldFeeIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_unsold_fee_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.hp_tax_schema_id
     * @return static
     */
    public function groupByHpTaxSchemaId(): static
    {
        $this->group($this->alias . '.hp_tax_schema_id');
        return $this;
    }

    /**
     * Order by auction.hp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.bp_tax_schema_id
     * @return static
     */
    public function groupByBpTaxSchemaId(): static
    {
        $this->group($this->alias . '.bp_tax_schema_id');
        return $this;
    }

    /**
     * Order by auction.bp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction.services_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.services_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction.services_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipServicesTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.services_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction.services_tax_schema_id
     * @return static
     */
    public function groupByServicesTaxSchemaId(): static
    {
        $this->group($this->alias . '.services_tax_schema_id');
        return $this;
    }

    /**
     * Order by auction.services_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByServicesTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.services_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction.services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction.services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction.services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction.services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterServicesTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.services_tax_schema_id', $filterValue, '<=');
        return $this;
    }
}
