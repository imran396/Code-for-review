<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUi;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingUi;

/**
 * Abstract class AbstractSettingUiReadRepository
 * @method SettingUi[] loadEntities()
 * @method SettingUi|null loadEntity()
 */
abstract class AbstractSettingUiReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_UI;
    protected string $alias = Db::A_SETTING_UI;

    /**
     * Filter by setting_ui.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_ui.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_ui.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.page_header
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPageHeader(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.page_header', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.page_header from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPageHeader(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.page_header', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.page_header
     * @return static
     */
    public function groupByPageHeader(): static
    {
        $this->group($this->alias . '.page_header');
        return $this;
    }

    /**
     * Order by setting_ui.page_header
     * @param bool $ascending
     * @return static
     */
    public function orderByPageHeader(bool $ascending = true): static
    {
        $this->order($this->alias . '.page_header', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.page_header by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePageHeader(string $filterValue): static
    {
        $this->like($this->alias . '.page_header', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.page_header_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPageHeaderType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.page_header_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.page_header_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPageHeaderType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.page_header_type', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.page_header_type
     * @return static
     */
    public function groupByPageHeaderType(): static
    {
        $this->group($this->alias . '.page_header_type');
        return $this;
    }

    /**
     * Order by setting_ui.page_header_type
     * @param bool $ascending
     * @return static
     */
    public function orderByPageHeaderType(bool $ascending = true): static
    {
        $this->order($this->alias . '.page_header_type', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.page_header_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePageHeaderType(string $filterValue): static
    {
        $this->like($this->alias . '.page_header_type', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.responsive_css_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResponsiveCssFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.responsive_css_file', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.responsive_css_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResponsiveCssFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.responsive_css_file', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.responsive_css_file
     * @return static
     */
    public function groupByResponsiveCssFile(): static
    {
        $this->group($this->alias . '.responsive_css_file');
        return $this;
    }

    /**
     * Order by setting_ui.responsive_css_file
     * @param bool $ascending
     * @return static
     */
    public function orderByResponsiveCssFile(bool $ascending = true): static
    {
        $this->order($this->alias . '.responsive_css_file', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.responsive_css_file by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResponsiveCssFile(string $filterValue): static
    {
        $this->like($this->alias . '.responsive_css_file', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.items_per_page
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterItemsPerPage(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.items_per_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.items_per_page from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipItemsPerPage(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.items_per_page', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.items_per_page
     * @return static
     */
    public function groupByItemsPerPage(): static
    {
        $this->group($this->alias . '.items_per_page');
        return $this;
    }

    /**
     * Order by setting_ui.items_per_page
     * @param bool $ascending
     * @return static
     */
    public function orderByItemsPerPage(bool $ascending = true): static
    {
        $this->order($this->alias . '.items_per_page', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.items_per_page
     * @param int $filterValue
     * @return static
     */
    public function filterItemsPerPageLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.items_per_page', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.logo_link
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLogoLink(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.logo_link', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.logo_link from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLogoLink(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.logo_link', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.logo_link
     * @return static
     */
    public function groupByLogoLink(): static
    {
        $this->group($this->alias . '.logo_link');
        return $this;
    }

    /**
     * Order by setting_ui.logo_link
     * @param bool $ascending
     * @return static
     */
    public function orderByLogoLink(bool $ascending = true): static
    {
        $this->order($this->alias . '.logo_link', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.logo_link by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLogoLink(string $filterValue): static
    {
        $this->like($this->alias . '.logo_link', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.responsive_header_address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResponsiveHeaderAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.responsive_header_address', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.responsive_header_address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResponsiveHeaderAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.responsive_header_address', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.responsive_header_address
     * @return static
     */
    public function groupByResponsiveHeaderAddress(): static
    {
        $this->group($this->alias . '.responsive_header_address');
        return $this;
    }

    /**
     * Order by setting_ui.responsive_header_address
     * @param bool $ascending
     * @return static
     */
    public function orderByResponsiveHeaderAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.responsive_header_address', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.responsive_header_address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResponsiveHeaderAddress(string $filterValue): static
    {
        $this->like($this->alias . '.responsive_header_address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.responsive_html_head_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResponsiveHtmlHeadCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.responsive_html_head_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.responsive_html_head_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResponsiveHtmlHeadCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.responsive_html_head_code', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.responsive_html_head_code
     * @return static
     */
    public function groupByResponsiveHtmlHeadCode(): static
    {
        $this->group($this->alias . '.responsive_html_head_code');
        return $this;
    }

    /**
     * Order by setting_ui.responsive_html_head_code
     * @param bool $ascending
     * @return static
     */
    public function orderByResponsiveHtmlHeadCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.responsive_html_head_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.responsive_html_head_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeResponsiveHtmlHeadCode(string $filterValue): static
    {
        $this->like($this->alias . '.responsive_html_head_code', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.external_javascript
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterExternalJavascript(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.external_javascript', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.external_javascript from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipExternalJavascript(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.external_javascript', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.external_javascript
     * @return static
     */
    public function groupByExternalJavascript(): static
    {
        $this->group($this->alias . '.external_javascript');
        return $this;
    }

    /**
     * Order by setting_ui.external_javascript
     * @param bool $ascending
     * @return static
     */
    public function orderByExternalJavascript(bool $ascending = true): static
    {
        $this->order($this->alias . '.external_javascript', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.external_javascript by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeExternalJavascript(string $filterValue): static
    {
        $this->like($this->alias . '.external_javascript', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.admin_custom_js_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAdminCustomJsUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_custom_js_url', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.admin_custom_js_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAdminCustomJsUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_custom_js_url', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.admin_custom_js_url
     * @return static
     */
    public function groupByAdminCustomJsUrl(): static
    {
        $this->group($this->alias . '.admin_custom_js_url');
        return $this;
    }

    /**
     * Order by setting_ui.admin_custom_js_url
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminCustomJsUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_custom_js_url', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.admin_custom_js_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAdminCustomJsUrl(string $filterValue): static
    {
        $this->like($this->alias . '.admin_custom_js_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.page_redirection
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPageRedirection(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.page_redirection', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.page_redirection from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPageRedirection(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.page_redirection', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.page_redirection
     * @return static
     */
    public function groupByPageRedirection(): static
    {
        $this->group($this->alias . '.page_redirection');
        return $this;
    }

    /**
     * Order by setting_ui.page_redirection
     * @param bool $ascending
     * @return static
     */
    public function orderByPageRedirection(bool $ascending = true): static
    {
        $this->order($this->alias . '.page_redirection', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.page_redirection by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePageRedirection(string $filterValue): static
    {
        $this->like($this->alias . '.page_redirection', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.admin_css_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAdminCssFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_css_file', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.admin_css_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAdminCssFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_css_file', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.admin_css_file
     * @return static
     */
    public function groupByAdminCssFile(): static
    {
        $this->group($this->alias . '.admin_css_file');
        return $this;
    }

    /**
     * Order by setting_ui.admin_css_file
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminCssFile(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_css_file', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.admin_css_file by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAdminCssFile(string $filterValue): static
    {
        $this->like($this->alias . '.admin_css_file', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.search_results_format
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSearchResultsFormat(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_results_format', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.search_results_format from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSearchResultsFormat(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_results_format', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.search_results_format
     * @return static
     */
    public function groupBySearchResultsFormat(): static
    {
        $this->group($this->alias . '.search_results_format');
        return $this;
    }

    /**
     * Order by setting_ui.search_results_format
     * @param bool $ascending
     * @return static
     */
    public function orderBySearchResultsFormat(bool $ascending = true): static
    {
        $this->order($this->alias . '.search_results_format', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.search_results_format by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSearchResultsFormat(string $filterValue): static
    {
        $this->like($this->alias . '.search_results_format', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.consignor_schedule_header
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterConsignorScheduleHeader(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_schedule_header', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.consignor_schedule_header from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipConsignorScheduleHeader(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_schedule_header', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.consignor_schedule_header
     * @return static
     */
    public function groupByConsignorScheduleHeader(): static
    {
        $this->group($this->alias . '.consignor_schedule_header');
        return $this;
    }

    /**
     * Order by setting_ui.consignor_schedule_header
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorScheduleHeader(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_schedule_header', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.consignor_schedule_header by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeConsignorScheduleHeader(string $filterValue): static
    {
        $this->like($this->alias . '.consignor_schedule_header', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.auctioneer_filter
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuctioneerFilter(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auctioneer_filter', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.auctioneer_filter from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuctioneerFilter(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auctioneer_filter', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.auctioneer_filter
     * @return static
     */
    public function groupByAuctioneerFilter(): static
    {
        $this->group($this->alias . '.auctioneer_filter');
        return $this;
    }

    /**
     * Order by setting_ui.auctioneer_filter
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctioneerFilter(bool $ascending = true): static
    {
        $this->order($this->alias . '.auctioneer_filter', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.auctioneer_filter
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerFilterGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_filter', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.auctioneer_filter
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerFilterGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_filter', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.auctioneer_filter
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerFilterLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_filter', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.auctioneer_filter
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctioneerFilterLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auctioneer_filter', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.landing_page
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLandingPage(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.landing_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.landing_page from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLandingPage(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.landing_page', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.landing_page
     * @return static
     */
    public function groupByLandingPage(): static
    {
        $this->group($this->alias . '.landing_page');
        return $this;
    }

    /**
     * Order by setting_ui.landing_page
     * @param bool $ascending
     * @return static
     */
    public function orderByLandingPage(bool $ascending = true): static
    {
        $this->order($this->alias . '.landing_page', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.landing_page by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLandingPage(string $filterValue): static
    {
        $this->like($this->alias . '.landing_page', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.landing_page_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLandingPageUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.landing_page_url', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.landing_page_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLandingPageUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.landing_page_url', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.landing_page_url
     * @return static
     */
    public function groupByLandingPageUrl(): static
    {
        $this->group($this->alias . '.landing_page_url');
        return $this;
    }

    /**
     * Order by setting_ui.landing_page_url
     * @param bool $ascending
     * @return static
     */
    public function orderByLandingPageUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.landing_page_url', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.landing_page_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLandingPageUrl(string $filterValue): static
    {
        $this->like($this->alias . '.landing_page_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.lot_item_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotItemDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.lot_item_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotItemDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_detail_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.lot_item_detail_template
     * @return static
     */
    public function groupByLotItemDetailTemplate(): static
    {
        $this->group($this->alias . '.lot_item_detail_template');
        return $this;
    }

    /**
     * Order by setting_ui.lot_item_detail_template
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemDetailTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_detail_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.lot_item_detail_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotItemDetailTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.lot_item_detail_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.rtb_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRtbDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.rtb_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRtbDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_detail_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.rtb_detail_template
     * @return static
     */
    public function groupByRtbDetailTemplate(): static
    {
        $this->group($this->alias . '.rtb_detail_template');
        return $this;
    }

    /**
     * Order by setting_ui.rtb_detail_template
     * @param bool $ascending
     * @return static
     */
    public function orderByRtbDetailTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.rtb_detail_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.rtb_detail_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRtbDetailTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.rtb_detail_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.auction_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuctionDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.auction_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuctionDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_detail_template', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.auction_detail_template
     * @return static
     */
    public function groupByAuctionDetailTemplate(): static
    {
        $this->group($this->alias . '.auction_detail_template');
        return $this;
    }

    /**
     * Order by setting_ui.auction_detail_template
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionDetailTemplate(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_detail_template', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.auction_detail_template by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuctionDetailTemplate(string $filterValue): static
    {
        $this->like($this->alias . '.auction_detail_template', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.show_member_menu_items
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterShowMemberMenuItems(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_member_menu_items', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.show_member_menu_items from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipShowMemberMenuItems(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_member_menu_items', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.show_member_menu_items
     * @return static
     */
    public function groupByShowMemberMenuItems(): static
    {
        $this->group($this->alias . '.show_member_menu_items');
        return $this;
    }

    /**
     * Order by setting_ui.show_member_menu_items
     * @param bool $ascending
     * @return static
     */
    public function orderByShowMemberMenuItems(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_member_menu_items', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.show_member_menu_items
     * @param bool $filterValue
     * @return static
     */
    public function filterShowMemberMenuItemsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_member_menu_items', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.show_member_menu_items
     * @param bool $filterValue
     * @return static
     */
    public function filterShowMemberMenuItemsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_member_menu_items', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.show_member_menu_items
     * @param bool $filterValue
     * @return static
     */
    public function filterShowMemberMenuItemsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_member_menu_items', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.show_member_menu_items
     * @param bool $filterValue
     * @return static
     */
    public function filterShowMemberMenuItemsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.show_member_menu_items', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.auction_date_in_search
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAuctionDateInSearch(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_date_in_search', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.auction_date_in_search from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAuctionDateInSearch(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_date_in_search', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.auction_date_in_search
     * @return static
     */
    public function groupByAuctionDateInSearch(): static
    {
        $this->group($this->alias . '.auction_date_in_search');
        return $this;
    }

    /**
     * Order by setting_ui.auction_date_in_search
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionDateInSearch(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_date_in_search', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.auction_date_in_search
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionDateInSearchGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_date_in_search', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.auction_date_in_search
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionDateInSearchGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_date_in_search', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.auction_date_in_search
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionDateInSearchLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_date_in_search', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.auction_date_in_search
     * @param bool $filterValue
     * @return static
     */
    public function filterAuctionDateInSearchLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_date_in_search', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.main_menu_auction_target
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMainMenuAuctionTarget(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.main_menu_auction_target', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.main_menu_auction_target from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMainMenuAuctionTarget(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.main_menu_auction_target', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.main_menu_auction_target
     * @return static
     */
    public function groupByMainMenuAuctionTarget(): static
    {
        $this->group($this->alias . '.main_menu_auction_target');
        return $this;
    }

    /**
     * Order by setting_ui.main_menu_auction_target
     * @param bool $ascending
     * @return static
     */
    public function orderByMainMenuAuctionTarget(bool $ascending = true): static
    {
        $this->order($this->alias . '.main_menu_auction_target', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.main_menu_auction_target by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeMainMenuAuctionTarget(string $filterValue): static
    {
        $this->like($this->alias . '.main_menu_auction_target', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForAllCategories(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.custom_template_hide_empty_fields_for_all_categories from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCustomTemplateHideEmptyFieldsForAllCategories(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @return static
     */
    public function groupByCustomTemplateHideEmptyFieldsForAllCategories(): static
    {
        $this->group($this->alias . '.custom_template_hide_empty_fields_for_all_categories');
        return $this;
    }

    /**
     * Order by setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool $ascending
     * @return static
     */
    public function orderByCustomTemplateHideEmptyFieldsForAllCategories(bool $ascending = true): static
    {
        $this->order($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForAllCategoriesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForAllCategoriesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForAllCategoriesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.custom_template_hide_empty_fields_for_all_categories
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForAllCategoriesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_all_categories', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForNoCategoryLot(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.custom_template_hide_empty_fields_for_no_category_lot from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCustomTemplateHideEmptyFieldsForNoCategoryLot(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @return static
     */
    public function groupByCustomTemplateHideEmptyFieldsForNoCategoryLot(): static
    {
        $this->group($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot');
        return $this;
    }

    /**
     * Order by setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool $ascending
     * @return static
     */
    public function orderByCustomTemplateHideEmptyFieldsForNoCategoryLot(bool $ascending = true): static
    {
        $this->order($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForNoCategoryLotGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForNoCategoryLotGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForNoCategoryLotLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.custom_template_hide_empty_fields_for_no_category_lot
     * @param bool $filterValue
     * @return static
     */
    public function filterCustomTemplateHideEmptyFieldsForNoCategoryLotLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.custom_template_hide_empty_fields_for_no_category_lot', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.lot_item_detail_template_for_no_category
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotItemDetailTemplateForNoCategory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_detail_template_for_no_category', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.lot_item_detail_template_for_no_category from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotItemDetailTemplateForNoCategory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_detail_template_for_no_category', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.lot_item_detail_template_for_no_category
     * @return static
     */
    public function groupByLotItemDetailTemplateForNoCategory(): static
    {
        $this->group($this->alias . '.lot_item_detail_template_for_no_category');
        return $this;
    }

    /**
     * Order by setting_ui.lot_item_detail_template_for_no_category
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemDetailTemplateForNoCategory(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_detail_template_for_no_category', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.lot_item_detail_template_for_no_category by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotItemDetailTemplateForNoCategory(string $filterValue): static
    {
        $this->like($this->alias . '.lot_item_detail_template_for_no_category', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.rtb_detail_template_for_no_category
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRtbDetailTemplateForNoCategory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_detail_template_for_no_category', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.rtb_detail_template_for_no_category from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRtbDetailTemplateForNoCategory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_detail_template_for_no_category', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.rtb_detail_template_for_no_category
     * @return static
     */
    public function groupByRtbDetailTemplateForNoCategory(): static
    {
        $this->group($this->alias . '.rtb_detail_template_for_no_category');
        return $this;
    }

    /**
     * Order by setting_ui.rtb_detail_template_for_no_category
     * @param bool $ascending
     * @return static
     */
    public function orderByRtbDetailTemplateForNoCategory(bool $ascending = true): static
    {
        $this->order($this->alias . '.rtb_detail_template_for_no_category', $ascending);
        return $this;
    }

    /**
     * Filter setting_ui.rtb_detail_template_for_no_category by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeRtbDetailTemplateForNoCategory(string $filterValue): static
    {
        $this->like($this->alias . '.rtb_detail_template_for_no_category', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_ui.image_auto_orient
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageAutoOrient(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_auto_orient', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.image_auto_orient from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageAutoOrient(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_auto_orient', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.image_auto_orient
     * @return static
     */
    public function groupByImageAutoOrient(): static
    {
        $this->group($this->alias . '.image_auto_orient');
        return $this;
    }

    /**
     * Order by setting_ui.image_auto_orient
     * @param bool $ascending
     * @return static
     */
    public function orderByImageAutoOrient(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_auto_orient', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.image_optimize
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageOptimize(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_optimize', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.image_optimize from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageOptimize(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_optimize', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.image_optimize
     * @return static
     */
    public function groupByImageOptimize(): static
    {
        $this->group($this->alias . '.image_optimize');
        return $this;
    }

    /**
     * Order by setting_ui.image_optimize
     * @param bool $ascending
     * @return static
     */
    public function orderByImageOptimize(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_optimize', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_ui.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_ui.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_ui.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_ui.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_ui.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_ui.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_ui.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_ui.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_ui.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_ui.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_ui.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_ui.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
