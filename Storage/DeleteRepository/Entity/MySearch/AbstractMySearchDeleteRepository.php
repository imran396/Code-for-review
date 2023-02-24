<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearch;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractMySearchDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_MY_SEARCH;
    protected string $alias = Db::A_MY_SEARCH;

    /**
     * Filter by my_search.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.title', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.title', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.keywords
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterKeywords(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.keywords', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.keywords from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipKeywords(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.keywords', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.live_bidding
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLiveBidding(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.live_bidding', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.live_bidding from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLiveBidding(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.live_bidding', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.hybrid
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHybrid(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hybrid', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.hybrid from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHybrid(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hybrid', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.timed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTimed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timed', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.timed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTimed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timed', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.regular_bidding
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegularBidding(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.regular_bidding', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.regular_bidding from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegularBidding(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.regular_bidding', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.buy_now
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyNow(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.buy_now from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyNow(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.best_offer
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBestOffer(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.best_offer', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.best_offer from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBestOffer(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.best_offer', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.sort_order
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSortOrder(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sort_order', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.sort_order from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSortOrder(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sort_order', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.send_mail
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendMail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_mail', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.send_mail from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendMail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_mail', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.my_search_auctioneer_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMySearchAuctioneerId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.my_search_auctioneer_id', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.my_search_auctioneer_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMySearchAuctioneerId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.my_search_auctioneer_id', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.category_match
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCategoryMatch(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.category_match', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.category_match from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCategoryMatch(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.category_match', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.my_search_exclude_closed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMySearchExcludeClosed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.my_search_exclude_closed', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.my_search_exclude_closed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMySearchExcludeClosed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.my_search_exclude_closed', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by my_search.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out my_search.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }
}
