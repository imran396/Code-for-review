<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LocationListFiltersPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class LocationListFiltersPanelConstants
{
    public const CID_LST_ACCOUNT = 'llfp_account';
    public const CID_TXT_COUNTRY = 'llfp_country';
    public const CID_HID_COUNTRY_CODE = 'llfp_country_code';
    public const CID_TXT_NAME = 'llfp_name';
    public const CID_TXT_STATE = 'llfp_state';
    public const CID_HID_STATE_CODE = 'llfp_state_code';
    public const CID_TXT_CITY = 'llfp_city';
    public const CID_TXT_COUNTY = 'llfp_county';
    public const CID_TXT_ADDRESS = 'llfp_address';
    public const CID_TXT_ZIP = 'llfp_zip';
    public const CID_BTN_SEARCH = 'llfp_search';
    public const CID_BTN_RESET = 'llfp_reset';
    public const CID_BTN_FILTER_SECTION_TOGGLE = 'llfp_filter_section_toggle';
    public const CID_BLK_SECTION_FILTER = 'llfp_filter';
}
