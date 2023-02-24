<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionBidder;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionBidderDeleteRepository extends DeleteRepositoryBase
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
}
