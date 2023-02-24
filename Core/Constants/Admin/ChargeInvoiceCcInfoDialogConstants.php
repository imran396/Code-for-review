<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class ChargeInvoiceCcInfoDialogConstants
 */
class ChargeInvoiceCcInfoDialogConstants
{
    public const CID_ICO_CONFIRM_WAIT = 'cicid0';
    public const CID_TXT_FIRST_NAME = 'cicid1';
    public const CID_TXT_LAST_NAME = 'cicid2';
    public const CID_LST_CC_TYPE = 'cicid19';
    public const CID_TXT_CC_NUMBER = 'cicid3';
    public const CID_LST_MONTH = 'cicid4';
    public const CID_LST_YEAR = 'cicid5';
    public const CID_TXT_CC_CODE = 'cicid6';
    public const CID_BTN_SUBMIT = 'cicid7';
    public const CID_BTN_CANCEL = 'cicid8';
    public const CID_CHK_REPLACE_OLD_CARD = 'cicid21';
    public const CID_LBL_MESSAGE = 'cicid22';
    public const CID_LST_PAYMENT_METHOD = 'cicid23';
    public const CID_TXT_EWAY_CARDNUMBER = Constants\UrlParam::EWAY_CARDNUMBER;
    public const CID_TXT_EWAY_CARDCVN = Constants\UrlParam::EWAY_CARDCVN;
    public const CID_LST_COUNTRY = 'cicid10';
    public const CID_TXT_ADDRESS = 'cicid11';
    public const CID_TXT_ADDRESS2 = 'cicid12';
    public const CID_TXT_CITY = 'cicid13';
    public const CID_LST_US_STATE = 'cicid14';
    public const CID_LST_CANADA_STATE = 'cicid18';
    public const CID_LST_MEXICO_STATE = 'cicid20';
    public const CID_TXT_STATE = 'cicid15';
    public const CID_TXT_ZIP = 'cicid16';
    public const CID_TXT_PHONE = 'cicid17';
}
