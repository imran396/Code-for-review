<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSeo;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSeoDeleteRepository extends DeleteRepositoryBase
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
}
