<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionBidderOptionSelection;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuctionBidderOptionSelectionDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_BIDDER_OPTION_SELECTION;
    protected string $alias = Db::A_AUCTION_BIDDER_OPTION_SELECTION;

    /**
     * Filter by auction_bidder_option_selection.auction_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.auction_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.auction_bidder_option_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAuctionBidderOptionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_bidder_option_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.auction_bidder_option_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAuctionBidderOptionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_bidder_option_id', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.option
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOption(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.option', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.option from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOption(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.option', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by auction_bidder_option_selection.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_bidder_option_selection.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
