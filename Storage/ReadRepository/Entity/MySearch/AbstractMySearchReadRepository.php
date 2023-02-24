<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MySearch;

use MySearch;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractMySearchReadRepository
 * @method MySearch[] loadEntities()
 * @method MySearch|null loadEntity()
 */
abstract class AbstractMySearchReadRepository extends ReadRepositoryBase
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
     * Group by my_search.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by my_search.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by my_search.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by my_search.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by my_search.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by my_search.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by my_search.title
     * @return static
     */
    public function groupByTitle(): static
    {
        $this->group($this->alias . '.title');
        return $this;
    }

    /**
     * Order by my_search.title
     * @param bool $ascending
     * @return static
     */
    public function orderByTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.title', $ascending);
        return $this;
    }

    /**
     * Filter my_search.title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTitle(string $filterValue): static
    {
        $this->like($this->alias . '.title', "%{$filterValue}%");
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
     * Group by my_search.keywords
     * @return static
     */
    public function groupByKeywords(): static
    {
        $this->group($this->alias . '.keywords');
        return $this;
    }

    /**
     * Order by my_search.keywords
     * @param bool $ascending
     * @return static
     */
    public function orderByKeywords(bool $ascending = true): static
    {
        $this->order($this->alias . '.keywords', $ascending);
        return $this;
    }

    /**
     * Filter my_search.keywords by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeKeywords(string $filterValue): static
    {
        $this->like($this->alias . '.keywords', "%{$filterValue}%");
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
     * Group by my_search.live_bidding
     * @return static
     */
    public function groupByLiveBidding(): static
    {
        $this->group($this->alias . '.live_bidding');
        return $this;
    }

    /**
     * Order by my_search.live_bidding
     * @param bool $ascending
     * @return static
     */
    public function orderByLiveBidding(bool $ascending = true): static
    {
        $this->order($this->alias . '.live_bidding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.live_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveBiddingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_bidding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.live_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveBiddingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_bidding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.live_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveBiddingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_bidding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.live_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterLiveBiddingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.live_bidding', $filterValue, '<=');
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
     * Group by my_search.hybrid
     * @return static
     */
    public function groupByHybrid(): static
    {
        $this->group($this->alias . '.hybrid');
        return $this;
    }

    /**
     * Order by my_search.hybrid
     * @param bool $ascending
     * @return static
     */
    public function orderByHybrid(bool $ascending = true): static
    {
        $this->order($this->alias . '.hybrid', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.hybrid
     * @param bool $filterValue
     * @return static
     */
    public function filterHybridLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hybrid', $filterValue, '<=');
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
     * Group by my_search.timed
     * @return static
     */
    public function groupByTimed(): static
    {
        $this->group($this->alias . '.timed');
        return $this;
    }

    /**
     * Order by my_search.timed
     * @param bool $ascending
     * @return static
     */
    public function orderByTimed(bool $ascending = true): static
    {
        $this->order($this->alias . '.timed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.timed
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.timed
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.timed
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.timed
     * @param bool $filterValue
     * @return static
     */
    public function filterTimedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.timed', $filterValue, '<=');
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
     * Group by my_search.regular_bidding
     * @return static
     */
    public function groupByRegularBidding(): static
    {
        $this->group($this->alias . '.regular_bidding');
        return $this;
    }

    /**
     * Order by my_search.regular_bidding
     * @param bool $ascending
     * @return static
     */
    public function orderByRegularBidding(bool $ascending = true): static
    {
        $this->order($this->alias . '.regular_bidding', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.regular_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterRegularBiddingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regular_bidding', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.regular_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterRegularBiddingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regular_bidding', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.regular_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterRegularBiddingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regular_bidding', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.regular_bidding
     * @param bool $filterValue
     * @return static
     */
    public function filterRegularBiddingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.regular_bidding', $filterValue, '<=');
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
     * Group by my_search.buy_now
     * @return static
     */
    public function groupByBuyNow(): static
    {
        $this->group($this->alias . '.buy_now');
        return $this;
    }

    /**
     * Order by my_search.buy_now
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyNow(bool $ascending = true): static
    {
        $this->order($this->alias . '.buy_now', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.buy_now
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyNowLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buy_now', $filterValue, '<=');
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
     * Group by my_search.best_offer
     * @return static
     */
    public function groupByBestOffer(): static
    {
        $this->group($this->alias . '.best_offer');
        return $this;
    }

    /**
     * Order by my_search.best_offer
     * @param bool $ascending
     * @return static
     */
    public function orderByBestOffer(bool $ascending = true): static
    {
        $this->order($this->alias . '.best_offer', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.best_offer
     * @param bool $filterValue
     * @return static
     */
    public function filterBestOfferLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.best_offer', $filterValue, '<=');
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
     * Group by my_search.sort_order
     * @return static
     */
    public function groupBySortOrder(): static
    {
        $this->group($this->alias . '.sort_order');
        return $this;
    }

    /**
     * Order by my_search.sort_order
     * @param bool $ascending
     * @return static
     */
    public function orderBySortOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.sort_order', $ascending);
        return $this;
    }

    /**
     * Filter my_search.sort_order by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSortOrder(string $filterValue): static
    {
        $this->like($this->alias . '.sort_order', "%{$filterValue}%");
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
     * Group by my_search.send_mail
     * @return static
     */
    public function groupBySendMail(): static
    {
        $this->group($this->alias . '.send_mail');
        return $this;
    }

    /**
     * Order by my_search.send_mail
     * @param bool $ascending
     * @return static
     */
    public function orderBySendMail(bool $ascending = true): static
    {
        $this->order($this->alias . '.send_mail', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.send_mail
     * @param bool $filterValue
     * @return static
     */
    public function filterSendMailGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_mail', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.send_mail
     * @param bool $filterValue
     * @return static
     */
    public function filterSendMailGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_mail', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.send_mail
     * @param bool $filterValue
     * @return static
     */
    public function filterSendMailLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_mail', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.send_mail
     * @param bool $filterValue
     * @return static
     */
    public function filterSendMailLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_mail', $filterValue, '<=');
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
     * Group by my_search.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by my_search.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by my_search.my_search_auctioneer_id
     * @return static
     */
    public function groupByMySearchAuctioneerId(): static
    {
        $this->group($this->alias . '.my_search_auctioneer_id');
        return $this;
    }

    /**
     * Order by my_search.my_search_auctioneer_id
     * @param bool $ascending
     * @return static
     */
    public function orderByMySearchAuctioneerId(bool $ascending = true): static
    {
        $this->order($this->alias . '.my_search_auctioneer_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.my_search_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchAuctioneerIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_auctioneer_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.my_search_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchAuctioneerIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_auctioneer_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.my_search_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchAuctioneerIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_auctioneer_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.my_search_auctioneer_id
     * @param int $filterValue
     * @return static
     */
    public function filterMySearchAuctioneerIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_auctioneer_id', $filterValue, '<=');
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
     * Group by my_search.category_match
     * @return static
     */
    public function groupByCategoryMatch(): static
    {
        $this->group($this->alias . '.category_match');
        return $this;
    }

    /**
     * Order by my_search.category_match
     * @param bool $ascending
     * @return static
     */
    public function orderByCategoryMatch(bool $ascending = true): static
    {
        $this->order($this->alias . '.category_match', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.category_match
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryMatchGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_match', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.category_match
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryMatchGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_match', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.category_match
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryMatchLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_match', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.category_match
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryMatchLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_match', $filterValue, '<=');
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
     * Group by my_search.my_search_exclude_closed
     * @return static
     */
    public function groupByMySearchExcludeClosed(): static
    {
        $this->group($this->alias . '.my_search_exclude_closed');
        return $this;
    }

    /**
     * Order by my_search.my_search_exclude_closed
     * @param bool $ascending
     * @return static
     */
    public function orderByMySearchExcludeClosed(bool $ascending = true): static
    {
        $this->order($this->alias . '.my_search_exclude_closed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.my_search_exclude_closed
     * @param bool $filterValue
     * @return static
     */
    public function filterMySearchExcludeClosedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_exclude_closed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.my_search_exclude_closed
     * @param bool $filterValue
     * @return static
     */
    public function filterMySearchExcludeClosedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_exclude_closed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.my_search_exclude_closed
     * @param bool $filterValue
     * @return static
     */
    public function filterMySearchExcludeClosedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_exclude_closed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.my_search_exclude_closed
     * @param bool $filterValue
     * @return static
     */
    public function filterMySearchExcludeClosedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.my_search_exclude_closed', $filterValue, '<=');
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
     * Group by my_search.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by my_search.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by my_search.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by my_search.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by my_search.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by my_search.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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

    /**
     * Group by my_search.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by my_search.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than my_search.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than my_search.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than my_search.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than my_search.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }
}
