<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         Feb 4, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

/**
 * Class RegisterShippingPanelConstants
 */
class RegisterShippingPanelConstants
{
    public const CID_CHK_SAME_AS_BILLING = 'sip1';

    public const CID_LST_COUNTRY = 'sip7';
    public const CID_LST_US_STATE = 'sip11';
    public const CID_LST_CANADA_STATE = 'sip27';
    public const CID_LST_MEXICO_STATE = 'sip29';
    public const CID_LST_CONTACT_TYPE = 'sip15';
    public const CID_LST_SHIPPING_CARRIER_SERVICE = 'sip28';

    public const CID_TXT_COMPANY_NAME = 'sip2';
    public const CID_TXT_FIRST_NAME = 'sip3';
    public const CID_TXT_LAST_NAME = 'sip4';
    public const CID_TXT_ADDRESS = 'sip8';
    public const CID_TXT_ADDRESS2 = 'sip9';
    public const CID_TXT_ADDRESS3 = 'sip13';
    public const CID_TXT_CITY = 'sip10';
    public const CID_TXT_ZIP = 'sip12';
    public const CID_TXT_STATE = 'sip14';

    public const CID_PNL_PHONE = 'sip5';
    public const CID_PNL_FAX = 'sip6';
}
