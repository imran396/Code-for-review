<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class TaxSchemaListFilterPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class TaxSchemaListFilterPanelConstants
{
    public const CID_LST_ACCOUNT = 'tslfp_account';
    public const CID_LST_GEO_TYPE = 'tslfp_geo_type';
    public const CID_LST_AMOUNT_SOURCE = 'tslfp-amount-source';
    public const CID_TXT_COUNTRY = 'tslfp_country';
    public const CID_HID_COUNTRY_CODE = 'tslfp_country_code';
    public const CID_TXT_NAME = 'tslfp_name';
    public const CID_TXT_STATE = 'tslfp_state';
    public const CID_HID_STATE_CODE = 'tslfp_state_code';
    public const CID_TXT_CITY = 'tslfp_city';
    public const CID_TXT_COUNTY = 'tslfp_county';
    public const CID_BTN_SEARCH = 'tslfp_search';
    public const CID_BTN_RESET = 'tslfp_reset';
    public const CID_CHK_FOR_INVOICE = 'tslfp-for-invoice';
    public const CID_CHK_FOR_SETTLEMENT = 'tslfp-for-settlement';
    public const CID_BTN_FILTER_SECTION_TOGGLE = 'tslfp_filter_section_toggle';
    public const CID_BLK_SECTION_FILTER = 'tslfp_filter';
}
