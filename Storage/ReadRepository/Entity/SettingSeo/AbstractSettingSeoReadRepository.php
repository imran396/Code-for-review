<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSeo;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSeo;

/**
 * Abstract class AbstractSettingSeoReadRepository
 * @method SettingSeo[] loadEntities()
 * @method SettingSeo|null loadEntity()
 */
abstract class AbstractSettingSeoReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SEO;
    protected string $alias = Db::A_SETTING_SEO;

    /**
     * Filter by setting_seo.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_seo.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_seo.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.auction_page_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionPageTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_page_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_page_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionPageTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_page_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_page_title
     * @return static
     */
    public function groupByAuctionPageTitle(): static
    {
        $this->group($this->alias . '.auction_page_title');
        return $this;
    }

    /**
     * Order by setting_seo.auction_page_title
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionPageTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_page_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_page_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionPageTitle(string $filterValue): static
    {
        $this->like($this->alias . '.auction_page_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_page_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionPageDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_page_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_page_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionPageDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_page_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_page_desc
     * @return static
     */
    public function groupByAuctionPageDesc(): static
    {
        $this->group($this->alias . '.auction_page_desc');
        return $this;
    }

    /**
     * Order by setting_seo.auction_page_desc
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionPageDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_page_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_page_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionPageDesc(string $filterValue): static
    {
        $this->like($this->alias . '.auction_page_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_page_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionPageKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_page_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_page_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionPageKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_page_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_page_keyword
     * @return static
     */
    public function groupByAuctionPageKeyword(): static
    {
        $this->group($this->alias . '.auction_page_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.auction_page_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionPageKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_page_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_page_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionPageKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.auction_page_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.lot_page_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotPageTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_page_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.lot_page_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotPageTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_page_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.lot_page_title
     * @return static
     */
    public function groupByLotPageTitle(): static
    {
        $this->group($this->alias . '.lot_page_title');
        return $this;
    }

    /**
     * Order by setting_seo.lot_page_title
     * @param bool $ascending
     * @return static
     */
    public function orderByLotPageTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_page_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.lot_page_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotPageTitle(string $filterValue): static
    {
        $this->like($this->alias . '.lot_page_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.lot_page_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotPageDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_page_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.lot_page_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotPageDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_page_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.lot_page_desc
     * @return static
     */
    public function groupByLotPageDesc(): static
    {
        $this->group($this->alias . '.lot_page_desc');
        return $this;
    }

    /**
     * Order by setting_seo.lot_page_desc
     * @param bool $ascending
     * @return static
     */
    public function orderByLotPageDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_page_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.lot_page_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotPageDesc(string $filterValue): static
    {
        $this->like($this->alias . '.lot_page_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.lot_page_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotPageKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_page_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.lot_page_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotPageKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_page_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.lot_page_keyword
     * @return static
     */
    public function groupByLotPageKeyword(): static
    {
        $this->group($this->alias . '.lot_page_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.lot_page_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderByLotPageKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_page_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.lot_page_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotPageKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.lot_page_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_listing_page_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionListingPageTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_listing_page_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_listing_page_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionListingPageTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_listing_page_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_listing_page_title
     * @return static
     */
    public function groupByAuctionListingPageTitle(): static
    {
        $this->group($this->alias . '.auction_listing_page_title');
        return $this;
    }

    /**
     * Order by setting_seo.auction_listing_page_title
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionListingPageTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_listing_page_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_listing_page_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionListingPageTitle(string $filterValue): static
    {
        $this->like($this->alias . '.auction_listing_page_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_listing_page_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionListingPageDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_listing_page_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_listing_page_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionListingPageDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_listing_page_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_listing_page_desc
     * @return static
     */
    public function groupByAuctionListingPageDesc(): static
    {
        $this->group($this->alias . '.auction_listing_page_desc');
        return $this;
    }

    /**
     * Order by setting_seo.auction_listing_page_desc
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionListingPageDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_listing_page_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_listing_page_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionListingPageDesc(string $filterValue): static
    {
        $this->like($this->alias . '.auction_listing_page_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_listing_page_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionListingPageKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_listing_page_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_listing_page_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionListingPageKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_listing_page_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_listing_page_keyword
     * @return static
     */
    public function groupByAuctionListingPageKeyword(): static
    {
        $this->group($this->alias . '.auction_listing_page_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.auction_listing_page_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionListingPageKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_listing_page_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_listing_page_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionListingPageKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.auction_listing_page_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.search_results_page_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSearchResultsPageTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_results_page_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.search_results_page_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSearchResultsPageTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_results_page_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.search_results_page_title
     * @return static
     */
    public function groupBySearchResultsPageTitle(): static
    {
        $this->group($this->alias . '.search_results_page_title');
        return $this;
    }

    /**
     * Order by setting_seo.search_results_page_title
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchResultsPageTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_results_page_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.search_results_page_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSearchResultsPageTitle(string $filterValue): static
    {
        $this->like($this->alias . '.search_results_page_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.search_results_page_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSearchResultsPageDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_results_page_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.search_results_page_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSearchResultsPageDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_results_page_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.search_results_page_desc
     * @return static
     */
    public function groupBySearchResultsPageDesc(): static
    {
        $this->group($this->alias . '.search_results_page_desc');
        return $this;
    }

    /**
     * Order by setting_seo.search_results_page_desc
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchResultsPageDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_results_page_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.search_results_page_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSearchResultsPageDesc(string $filterValue): static
    {
        $this->like($this->alias . '.search_results_page_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.search_results_page_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSearchResultsPageKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_results_page_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.search_results_page_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSearchResultsPageKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_results_page_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.search_results_page_keyword
     * @return static
     */
    public function groupBySearchResultsPageKeyword(): static
    {
        $this->group($this->alias . '.search_results_page_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.search_results_page_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchResultsPageKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_results_page_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.search_results_page_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSearchResultsPageKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.search_results_page_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.signup_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSignupTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.signup_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.signup_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSignupTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.signup_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.signup_title
     * @return static
     */
    public function groupBySignupTitle(): static
    {
        $this->group($this->alias . '.signup_title');
        return $this;
    }

    /**
     * Order by setting_seo.signup_title
     * @param bool $ascending
     * @return static
     */
    public function orderBySignupTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.signup_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.signup_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSignupTitle(string $filterValue): static
    {
        $this->like($this->alias . '.signup_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.signup_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSignupDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.signup_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.signup_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSignupDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.signup_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.signup_desc
     * @return static
     */
    public function groupBySignupDesc(): static
    {
        $this->group($this->alias . '.signup_desc');
        return $this;
    }

    /**
     * Order by setting_seo.signup_desc
     * @param bool $ascending
     * @return static
     */
    public function orderBySignupDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.signup_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.signup_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSignupDesc(string $filterValue): static
    {
        $this->like($this->alias . '.signup_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.signup_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSignupKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.signup_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.signup_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSignupKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.signup_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.signup_keyword
     * @return static
     */
    public function groupBySignupKeyword(): static
    {
        $this->group($this->alias . '.signup_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.signup_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderBySignupKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.signup_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.signup_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSignupKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.signup_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.login_title
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLoginTitle(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.login_title', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.login_title from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLoginTitle(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.login_title', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.login_title
     * @return static
     */
    public function groupByLoginTitle(): static
    {
        $this->group($this->alias . '.login_title');
        return $this;
    }

    /**
     * Order by setting_seo.login_title
     * @param bool $ascending
     * @return static
     */
    public function orderByLoginTitle(bool $ascending = true): static
    {
        $this->order($this->alias . '.login_title', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.login_title by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLoginTitle(string $filterValue): static
    {
        $this->like($this->alias . '.login_title', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.login_desc
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLoginDesc(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.login_desc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.login_desc from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLoginDesc(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.login_desc', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.login_desc
     * @return static
     */
    public function groupByLoginDesc(): static
    {
        $this->group($this->alias . '.login_desc');
        return $this;
    }

    /**
     * Order by setting_seo.login_desc
     * @param bool $ascending
     * @return static
     */
    public function orderByLoginDesc(bool $ascending = true): static
    {
        $this->order($this->alias . '.login_desc', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.login_desc by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLoginDesc(string $filterValue): static
    {
        $this->like($this->alias . '.login_desc', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.login_keyword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLoginKeyword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.login_keyword', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.login_keyword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLoginKeyword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.login_keyword', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.login_keyword
     * @return static
     */
    public function groupByLoginKeyword(): static
    {
        $this->group($this->alias . '.login_keyword');
        return $this;
    }

    /**
     * Order by setting_seo.login_keyword
     * @param bool $ascending
     * @return static
     */
    public function orderByLoginKeyword(bool $ascending = true): static
    {
        $this->order($this->alias . '.login_keyword', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.login_keyword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLoginKeyword(string $filterValue): static
    {
        $this->like($this->alias . '.login_keyword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.auction_seo_url_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionSeoUrlTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_seo_url_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.auction_seo_url_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionSeoUrlTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_seo_url_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.auction_seo_url_template
     * @return static
     */
    public function groupByAuctionSeoUrlTemplate(): static
    {
        $this->group($this->alias . '.auction_seo_url_template');
        return $this;
    }

    /**
     * Order by setting_seo.auction_seo_url_template
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionSeoUrlTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_seo_url_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.auction_seo_url_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionSeoUrlTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.auction_seo_url_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.lot_seo_url_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotSeoUrlTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_seo_url_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.lot_seo_url_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotSeoUrlTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_seo_url_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.lot_seo_url_template
     * @return static
     */
    public function groupByLotSeoUrlTemplate(): static
    {
        $this->group($this->alias . '.lot_seo_url_template');
        return $this;
    }

    /**
     * Order by setting_seo.lot_seo_url_template
     * @param bool $ascending
     * @return static
     */
    public function orderByLotSeoUrlTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_seo_url_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_seo.lot_seo_url_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotSeoUrlTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.lot_seo_url_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_seo.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_seo.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_seo.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_seo.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_seo.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_seo.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_seo.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_seo.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_seo.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_seo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_seo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_seo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_seo.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
