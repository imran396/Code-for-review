<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidder;

use AuctionBidder;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionBidderReadRepository
 * @method AuctionBidder[] loadEntities()
 * @method AuctionBidder|null loadEntity()
 */
abstract class AbstractAuctionBidderReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_BIDDER;
    protected string $alias = Db::A_AUCTION_BIDDER;

    /**
     * Filter by auction_bidder.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction_bidder.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by auction_bidder.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by auction_bidder.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.bidder_num
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterBidderNum(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bidder_num', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.bidder_num from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipBidderNum(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bidder_num', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.bidder_num
     * @return static
     */
    public function groupByBidderNum(): static
    {
        $this->group($this->alias . '.bidder_num');
        return $this;
    }

    /**
     * Order by auction_bidder.bidder_num
     * @param bool $ascending
     * @return static
     */
    public function orderByBidderNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.bidder_num', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.bidder_num by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeBidderNum(string $filterValue): static
    {
        $this->like($this->alias . '.bidder_num', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.bid_budget
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBidBudget(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bid_budget', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.bid_budget from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBidBudget(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bid_budget', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.bid_budget
     * @return static
     */
    public function groupByBidBudget(): static
    {
        $this->group($this->alias . '.bid_budget');
        return $this;
    }

    /**
     * Order by auction_bidder.bid_budget
     * @param bool $ascending
     * @return static
     */
    public function orderByBidBudget(bool $ascending = true): static
    {
        $this->order($this->alias . '.bid_budget', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.bid_budget
     * @param float $filterValue
     * @return static
     */
    public function filterBidBudgetGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_budget', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.bid_budget
     * @param float $filterValue
     * @return static
     */
    public function filterBidBudgetGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_budget', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.bid_budget
     * @param float $filterValue
     * @return static
     */
    public function filterBidBudgetLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_budget', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.bid_budget
     * @param float $filterValue
     * @return static
     */
    public function filterBidBudgetLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bid_budget', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.registered_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterRegisteredOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.registered_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.registered_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipRegisteredOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.registered_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.registered_on
     * @return static
     */
    public function groupByRegisteredOn(): static
    {
        $this->group($this->alias . '.registered_on');
        return $this;
    }

    /**
     * Order by auction_bidder.registered_on
     * @param bool $ascending
     * @return static
     */
    public function orderByRegisteredOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.registered_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.registered_on
     * @param string $filterValue
     * @return static
     */
    public function filterRegisteredOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.registered_on
     * @param string $filterValue
     * @return static
     */
    public function filterRegisteredOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.registered_on
     * @param string $filterValue
     * @return static
     */
    public function filterRegisteredOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.registered_on
     * @param string $filterValue
     * @return static
     */
    public function filterRegisteredOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.registered_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.is_reseller
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIsReseller(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.is_reseller', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.is_reseller from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIsReseller(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.is_reseller', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.is_reseller
     * @return static
     */
    public function groupByIsReseller(): static
    {
        $this->group($this->alias . '.is_reseller');
        return $this;
    }

    /**
     * Order by auction_bidder.is_reseller
     * @param bool $ascending
     * @return static
     */
    public function orderByIsReseller(bool $ascending = true): static
    {
        $this->order($this->alias . '.is_reseller', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.is_reseller
     * @param bool $filterValue
     * @return static
     */
    public function filterIsResellerGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_reseller', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.is_reseller
     * @param bool $filterValue
     * @return static
     */
    public function filterIsResellerGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_reseller', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.is_reseller
     * @param bool $filterValue
     * @return static
     */
    public function filterIsResellerLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_reseller', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.is_reseller
     * @param bool $filterValue
     * @return static
     */
    public function filterIsResellerLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.is_reseller', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.reseller_approved
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResellerApproved(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_approved', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.reseller_approved from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResellerApproved(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_approved', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.reseller_approved
     * @return static
     */
    public function groupByResellerApproved(): static
    {
        $this->group($this->alias . '.reseller_approved');
        return $this;
    }

    /**
     * Order by auction_bidder.reseller_approved
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerApproved(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_approved', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.reseller_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerApprovedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_approved', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.reseller_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerApprovedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_approved', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.reseller_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerApprovedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_approved', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.reseller_approved
     * @param bool $filterValue
     * @return static
     */
    public function filterResellerApprovedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.reseller_approved', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.reseller_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResellerId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.reseller_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResellerId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.reseller_id
     * @return static
     */
    public function groupByResellerId(): static
    {
        $this->group($this->alias . '.reseller_id');
        return $this;
    }

    /**
     * Order by auction_bidder.reseller_id
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerId(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_id', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.reseller_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResellerId(string $filterValue): static
    {
        $this->like($this->alias . '.reseller_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.reseller_certificate
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResellerCertificate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_certificate', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.reseller_certificate from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResellerCertificate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_certificate', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.reseller_certificate
     * @return static
     */
    public function groupByResellerCertificate(): static
    {
        $this->group($this->alias . '.reseller_certificate');
        return $this;
    }

    /**
     * Order by auction_bidder.reseller_certificate
     * @param bool $ascending
     * @return static
     */
    public function orderByResellerCertificate(bool $ascending = true): static
    {
        $this->order($this->alias . '.reseller_certificate', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.reseller_certificate by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResellerCertificate(string $filterValue): static
    {
        $this->like($this->alias . '.reseller_certificate', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.auth_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAuthAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.auth_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAuthAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_amount', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.auth_amount
     * @return static
     */
    public function groupByAuthAmount(): static
    {
        $this->group($this->alias . '.auth_amount');
        return $this;
    }

    /**
     * Order by auction_bidder.auth_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.auth_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.auth_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.auth_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.auth_amount
     * @param float $filterValue
     * @return static
     */
    public function filterAuthAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.auth_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAuthDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_date', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.auth_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAuthDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_date', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.auth_date
     * @return static
     */
    public function groupByAuthDate(): static
    {
        $this->group($this->alias . '.auth_date');
        return $this;
    }

    /**
     * Order by auction_bidder.auth_date
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.auth_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterAuthDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterAuthDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterAuthDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.auth_date
     * @param string $filterValue
     * @return static
     */
    public function filterAuthDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.auth_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.carrier_method
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCarrierMethod(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.carrier_method', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.carrier_method from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCarrierMethod(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.carrier_method', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.carrier_method
     * @return static
     */
    public function groupByCarrierMethod(): static
    {
        $this->group($this->alias . '.carrier_method');
        return $this;
    }

    /**
     * Order by auction_bidder.carrier_method
     * @param bool $ascending
     * @return static
     */
    public function orderByCarrierMethod(bool $ascending = true): static
    {
        $this->order($this->alias . '.carrier_method', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.carrier_method by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCarrierMethod(string $filterValue): static
    {
        $this->like($this->alias . '.carrier_method', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.post_auc_import_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterPostAucImportPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.post_auc_import_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.post_auc_import_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipPostAucImportPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.post_auc_import_premium', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.post_auc_import_premium
     * @return static
     */
    public function groupByPostAucImportPremium(): static
    {
        $this->group($this->alias . '.post_auc_import_premium');
        return $this;
    }

    /**
     * Order by auction_bidder.post_auc_import_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByPostAucImportPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.post_auc_import_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterPostAucImportPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.post_auc_import_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.spent
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSpent(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.spent', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.spent from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSpent(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.spent', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.spent
     * @return static
     */
    public function groupBySpent(): static
    {
        $this->group($this->alias . '.spent');
        return $this;
    }

    /**
     * Order by auction_bidder.spent
     * @param bool $ascending
     * @return static
     */
    public function orderBySpent(bool $ascending = true): static
    {
        $this->order($this->alias . '.spent', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.spent
     * @param float $filterValue
     * @return static
     */
    public function filterSpentGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.spent', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.spent
     * @param float $filterValue
     * @return static
     */
    public function filterSpentGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.spent', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.spent
     * @param float $filterValue
     * @return static
     */
    public function filterSpentLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.spent', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.spent
     * @param float $filterValue
     * @return static
     */
    public function filterSpentLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.spent', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.collected
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCollected(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.collected', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.collected from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCollected(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.collected', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.collected
     * @return static
     */
    public function groupByCollected(): static
    {
        $this->group($this->alias . '.collected');
        return $this;
    }

    /**
     * Order by auction_bidder.collected
     * @param bool $ascending
     * @return static
     */
    public function orderByCollected(bool $ascending = true): static
    {
        $this->order($this->alias . '.collected', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.collected
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.collected
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.collected
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.collected
     * @param float $filterValue
     * @return static
     */
    public function filterCollectedLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.collected', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.referrer
     * @return static
     */
    public function groupByReferrer(): static
    {
        $this->group($this->alias . '.referrer');
        return $this;
    }

    /**
     * Order by auction_bidder.referrer
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrer(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.referrer by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrer(string $filterValue): static
    {
        $this->like($this->alias . '.referrer', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.referrer_host
     * @return static
     */
    public function groupByReferrerHost(): static
    {
        $this->group($this->alias . '.referrer_host');
        return $this;
    }

    /**
     * Order by auction_bidder.referrer_host
     * @param bool $ascending
     * @return static
     */
    public function orderByReferrerHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.referrer_host', $ascending);
        return $this;
    }

    /**
     * Filter auction_bidder.referrer_host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeReferrerHost(string $filterValue): static
    {
        $this->like($this->alias . '.referrer_host', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_bidder.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_bidder.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_bidder.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_bidder.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_bidder.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_bidder.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_bidder.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_bidder.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
