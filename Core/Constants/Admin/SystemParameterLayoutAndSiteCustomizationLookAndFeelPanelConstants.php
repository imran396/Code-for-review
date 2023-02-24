<?php
/**
 * SAM-9914: Move sections' logic to separate Panel classes at Manage settings system parameters layout and site customization page (/admin/manage-system-parameter/layout-and-site-customization)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 24, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLayoutAndSiteCustomizationLookAndFeelPanelConstants
 */
class SystemParameterLayoutAndSiteCustomizationLookAndFeelPanelConstants
{
    public const CID_TXT_PAGE_HEADER = 'scf2';
    public const CID_FLA_PAGE_HEADER = 'scf3';
    public const CID_RAD_PAGE_HEADER = 'scf4';
    public const CID_TXT_LOGO_LINK = 'scf5';
    public const CID_TXT_PAGE_FOOTER_RESPONSIVE = 'sc59';
    public const CID_TXT_RESPONSIVE_HEADER_ADDRESS = 'scf6b';
    public const CID_TXT_RESPONSIVE_HTML_HEAD_CODE = 'head-code';
    public const CID_TXT_RESPONSIVE_CSS_OVERRIDE = 'scf7b';
    public const CID_TXT_ADMIN_CSS_OVERRIDE = 'scf8';
    public const CID_TXT_ADMIN_CUSTOM_JS_URL = 'admin-custom-js-url';
    public const CID_TXT_EXTERNAL_JS = 'scf9';
    public const CID_TXT_MAIN_MENU_AUCTION_TARGET = 'scf61';
    public const CID_LST_SEARCH_RESULTS_FORMAT = 'scf13';
    public const CID_TXT_PAGE_REDIRECT = 'scf18';
    public const CID_CHK_AUCTION_DATE_IN_SEARCH = 'scf59';
    public const CID_RAD_LANDING_PAGE = 'scf47';
    public const CID_TXT_LANDING_PAGE_URL = 'scf57';
    public const CID_CHK_SHOW_MEMBER_MENU_ITEMS = 'scf50';
    public const CID_BLK_LOOK_FEEL = 'look-feel';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_PAGE_HEADER => Constants\Setting::PAGE_HEADER,
        self::CID_RAD_PAGE_HEADER => Constants\Setting::PAGE_HEADER_TYPE,
        self::CID_TXT_LOGO_LINK => Constants\Setting::LOGO_LINK,
        self::CID_TXT_RESPONSIVE_HEADER_ADDRESS => Constants\Setting::RESPONSIVE_HEADER_ADDRESS,
        self::CID_TXT_RESPONSIVE_HTML_HEAD_CODE => Constants\Setting::RESPONSIVE_HTML_HEAD_CODE,
        self::CID_TXT_RESPONSIVE_CSS_OVERRIDE => Constants\Setting::RESPONSIVE_CSS_FILE,
        self::CID_TXT_ADMIN_CSS_OVERRIDE => Constants\Setting::ADMIN_CSS_FILE,
        self::CID_TXT_ADMIN_CUSTOM_JS_URL => Constants\Setting::ADMIN_CUSTOM_JS_URL,
        self::CID_TXT_EXTERNAL_JS => Constants\Setting::EXTERNAL_JAVASCRIPT,
        self::CID_TXT_MAIN_MENU_AUCTION_TARGET => Constants\Setting::MAIN_MENU_AUCTION_TARGET,
        self::CID_LST_SEARCH_RESULTS_FORMAT => Constants\Setting::SEARCH_RESULTS_FORMAT,
        self::CID_TXT_PAGE_REDIRECT => Constants\Setting::PAGE_REDIRECTION,
        self::CID_CHK_AUCTION_DATE_IN_SEARCH => Constants\Setting::AUCTION_DATE_IN_SEARCH,
        self::CID_RAD_LANDING_PAGE => Constants\Setting::LANDING_PAGE,
        self::CID_TXT_LANDING_PAGE_URL => Constants\Setting::LANDING_PAGE_URL,
        self::CID_CHK_SHOW_MEMBER_MENU_ITEMS => Constants\Setting::SHOW_MEMBER_MENU_ITEMS,
    ];

    public const CLASS_BLK_LANDING_PAGE_URL = 'landing-page-url';
    public const CLASS_BLK_LOGO = 'logo';
    public const CLASS_BLK_PAGE_LOGO = 'plogo';
    public const CLASS_BLK_PAGE_TEXT = 'ptext';
}
