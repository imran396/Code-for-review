<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection;

use AuctionBidderOptionSelection;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionBidderOptionSelectionReadRepository
 * @method AuctionBidderOptionSelection[] loadEntities()
 * @method AuctionBidderOptionSelection|null loadEntity()
 */
abstract class AbstractAuctionBidderOptionSelectionReadRepository extends ReadRepositoryBase
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
     * Group by auction_bidder_option_selection.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.auction_bidder_option_id
     * @return static
     */
    public function groupByAuctionBidderOptionId(): static
    {
        $this->group($this->alias . '.auction_bidder_option_id');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.auction_bidder_option_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionBidderOptionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_bidder_option_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.auction_bidder_option_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionBidderOptionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_bidder_option_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.auction_bidder_option_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionBidderOptionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_bidder_option_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.auction_bidder_option_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionBidderOptionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_bidder_option_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.auction_bidder_option_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionBidderOptionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_bidder_option_id', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.option
     * @return static
     */
    public function groupByOption(): static
    {
        $this->group($this->alias . '.option');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.option
     * @param bool $ascending
     * @return static
     */
    public function orderByOption(bool $ascending = true): static
    {
        $this->order($this->alias . '.option', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.option
     * @param bool $filterValue
     * @return static
     */
    public function filterOptionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.option', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.option
     * @param bool $filterValue
     * @return static
     */
    public function filterOptionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.option', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.option
     * @param bool $filterValue
     * @return static
     */
    public function filterOptionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.option', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.option
     * @param bool $filterValue
     * @return static
     */
    public function filterOptionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.option', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by auction_bidder_option_selection.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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

    /**
     * Group by auction_bidder_option_selection.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_bidder_option_selection.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_bidder_option_selection.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_bidder_option_selection.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_bidder_option_selection.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_bidder_option_selection.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
