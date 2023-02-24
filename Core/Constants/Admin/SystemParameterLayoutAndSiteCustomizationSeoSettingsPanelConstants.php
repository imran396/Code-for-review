<?php
/**
 * SAM-9914: Move sections' logic to separate Panel classes at Manage settings system parameters layout and site customization page (/admin/manage-system-parameter/layout-and-site-customization)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLayoutAndSiteCustomizationSeoSettingsPanelConstants
 */
class SystemParameterLayoutAndSiteCustomizationSeoSettingsPanelConstants
{
    public const CID_TXT_AUC_PAGE_TITLE = 'scf36';
    public const CID_TXT_AUC_PAGE_DESC = 'scf37';
    public const CID_TXT_AUC_PAGE_KEYWORD = 'scf38';
    public const CID_TXT_LOT_PAGE_TITLE = 'scf39';
    public const CID_TXT_LOT_PAGE_DESC = 'scf40';
    public const CID_TXT_LOT_PAGE_KEYWORD = 'scf41';
    public const CID_TXT_AUC_LISTING_PAGE_TITLE = 'scf20';
    public const CID_TXT_AUC_LISTING_PAGE_DESC = 'scf21';
    public const CID_TXT_AUC_LISTING_PAGE_KEYWORD = 'scf22';
    public const CID_TXT_SEARCH_RESULTS_PAGE_TITLE = 'scf23';
    public const CID_TXT_SEARCH_RESULTS_PAGE_DESC = 'scf24';
    public const CID_TXT_SEARCH_RESULTS_PAGE_KEYWORD = 'scf25';
    public const CID_TXT_SIGNUP_TITLE = 'scf26';
    public const CID_TXT_SIGNUP_DESC = 'scf27';
    public const CID_TXT_SIGNUP_KEYWORD = 'scf28';
    public const CID_TXT_LOGIN_TITLE = 'scf29';
    public const CID_TXT_LOGIN_DESC = 'scf30';
    public const CID_TXT_LOGIN_KEYWORD = 'scf31';
    public const CID_TXT_AUCTION_SEO_URL_TEMPLATE = 'scf42';
    public const CID_TXT_LOT_SEO_URL_TEMPLATE = 'scf43';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_AUC_PAGE_TITLE => Constants\Setting::AUCTION_PAGE_TITLE,
        self::CID_TXT_AUC_PAGE_DESC => Constants\Setting::AUCTION_PAGE_DESC,
        self::CID_TXT_AUC_PAGE_KEYWORD => Constants\Setting::AUCTION_PAGE_KEYWORD,
        self::CID_TXT_LOT_PAGE_TITLE => Constants\Setting::LOT_PAGE_TITLE,
        self::CID_TXT_LOT_PAGE_DESC => Constants\Setting::LOT_PAGE_DESC,
        self::CID_TXT_LOT_PAGE_KEYWORD => Constants\Setting::LOT_PAGE_KEYWORD,
        self::CID_TXT_AUC_LISTING_PAGE_TITLE => Constants\Setting::AUCTION_LISTING_PAGE_TITLE,
        self::CID_TXT_AUC_LISTING_PAGE_DESC => Constants\Setting::AUCTION_LISTING_PAGE_DESC,
        self::CID_TXT_AUC_LISTING_PAGE_KEYWORD => Constants\Setting::AUCTION_LISTING_PAGE_KEYWORD,
        self::CID_TXT_SEARCH_RESULTS_PAGE_TITLE => Constants\Setting::SEARCH_RESULTS_PAGE_TITLE,
        self::CID_TXT_SEARCH_RESULTS_PAGE_DESC => Constants\Setting::SEARCH_RESULTS_PAGE_DESC,
        self::CID_TXT_SEARCH_RESULTS_PAGE_KEYWORD => Constants\Setting::SEARCH_RESULTS_PAGE_KEYWORD,
        self::CID_TXT_SIGNUP_TITLE => Constants\Setting::SIGNUP_TITLE,
        self::CID_TXT_SIGNUP_DESC => Constants\Setting::SIGNUP_DESC,
        self::CID_TXT_SIGNUP_KEYWORD => Constants\Setting::SIGNUP_KEYWORD,
        self::CID_TXT_LOGIN_TITLE => Constants\Setting::LOGIN_TITLE,
        self::CID_TXT_LOGIN_DESC => Constants\Setting::LOGIN_DESC,
        self::CID_TXT_LOGIN_KEYWORD => Constants\Setting::LOGIN_KEYWORD,
        self::CID_TXT_AUCTION_SEO_URL_TEMPLATE => Constants\Setting::AUCTION_SEO_URL_TEMPLATE,
        self::CID_TXT_LOT_SEO_URL_TEMPLATE => Constants\Setting::LOT_SEO_URL_TEMPLATE
    ];
}
