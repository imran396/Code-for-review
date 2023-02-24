<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingUi;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingUiDeleteRepository extends DeleteRepositoryBase
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
}
