<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         May 29, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

use Sam\Core\Constants;

/**
 * Class SignupFormConstants
 */
class SignupFormConstants
{
    public const CID_PNL_PERSONAL = 'RegisterPersonalPanel';
    public const CID_PNL_BILLING = 'RegisterBillingPanel';
    public const CID_PNL_SHIPPING = 'RegisterShippingPanel';
    public const CID_CHK_AGREE_TERMS = 'rf1';
    public const CID_BTN_REGISTER = 'rf2';
    public const CID_TXT_CAPTCHA = 'rf3';
    public const CID_TXT_ALTERNATIVE = 'rf4';
    public const CID_LBL_EMAIL_RESULT = 'rf5';
    public const CID_LBL_USER_RESULT = 'rf6';
    public const CID_ICO_DTG_WAIT = 'rf7';
    public const CID_CHK_CONFIRMATIONS = 'rf8';
    public const CID_CHK_SEND_NEWS_LETTER = 'rf9';
    public const CID_CHK_INCLUDE_PREFERENCES = 'rf10';
    public const CID_PNL_PREFERENCES = 'RegisterPreferencePanel';
    public const CID_CHK_CONFIRMATION_TPL = '%ss%s';
    public const CID_BTN_SEARCH = 'btnSearch';
    public const CID_CHK_EXCLUDE_CLOSED = 'excludeClosed';
    public const CID_HID_EXCLUDE_CLOSED = 'hid_excludeClosed';
    public const CID_LST_SEARCH_FILTER = 'searchFilter';
    public const CID_WRAPPER = 'wrapper';
    public const CID_FLASH_NOTIFICATION = 'flash-notification';
    public const CID_DIALOG_MESSAGE = 'dialog-message';
    public const CID_BLK_REGISTRATION_CONFIRM = 'reg-confirm';
    public const CID_BLK_IMAGE = 'q2';
    public const CID_BTN_RESET = 'q1';
    public const CID_BLK_SIGN_FST = 'sign_fst';
    public const CID_BLK_SCN_OPEN = 'scnopen';
    public const CID_USER_CUST_FIELD_EDIT_TPL = 'UsrCustFldEdt%s';
    public const CID_TXT_EWAY_CARDNUMBER = Constants\UrlParam::EWAY_CARDNUMBER;
    public const CID_TXT_EWAY_CARDCVN = Constants\UrlParam::EWAY_CARDCVN;
    public const CID_SIGNUP_FORM = 'SignupForm';
    public const CID_BLK_COOKIES_DISABLED = 'cookiesDisabled';

    public const CLASS_BTN_HIDE_BTN = 'hidebtn';
    public const CLASS_BTN_SHOW = 'show';
    public const CLASS_BTN_SHOW_BTN = 'showbtn';
    public const CLASS_BLK_COMBOBOX = 'combobox';
    public const CLASS_LNK_BACK_TO_TOP = 'back-to-top';
}
