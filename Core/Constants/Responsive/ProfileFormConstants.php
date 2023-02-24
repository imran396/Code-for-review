<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         Sep 4, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

use Sam\Core\Constants;

/**
 * Class ProfileFormConstants
 */
class ProfileFormConstants
{
    public const CID_PROFILE_PERSONAL_PANEL = 'ProfilePersonalPanel';
    public const CID_PROFILE_BILLING_PANEL = 'ProfileBillingPanel';
    public const CID_PROFILE_SHIPPING_PANEL = 'ProfileShippingPanel';
    public const CID_PROFILE_RESUME_PANEL = 'ProfileResumePanel';
    public const CID_PROFILE_PAYMENT_PANEL = 'ProfilePaymentPanel';
    public const CID_PROFILE_CREDIT_PANEL = 'ProfileCreditPanel';
    public const CID_BLK_FLASH_NOTIFICATION = 'flash-notification';
    public const CID_LBL_NOTICE = 'pfl001';
    public const CID_BTN_SAVE_CHANGES = 'pf1';
    public const CID_BTN_CANCEL = 'pf2';
    public const CID_LST_LANGUAGE = 'pf3';
    public const CID_BLK_SIGN_FST = 'sign_fst';
    public const CID_BLK_SCN_OPEN = 'scnopen';
    public const CID_USER_CUST_FIELD_EDIT_TPL = 'UsrCustFldEdt%s';
    public const CID_CHANGE_PASSWORD_CANCEL = 'change_password_cancel';
    public const CID_TXT_EWAY_CARDNUMBER = Constants\UrlParam::EWAY_CARDNUMBER;
    public const CID_TXT_EWAY_CARDCVN = Constants\UrlParam::EWAY_CARDCVN;

    public const CLASS_BLK_COMBOBOX = 'combobox';
    public const CLASS_BLK_CUSTOM_COMBOBOX_INPUT = 'custom-combobox-input';
    public const CLASS_BLK_SAVED_NOTICE = 'saved-notice';
    public const CLASS_BLK_TITLE = 'title';
    public const CLASS_LST_PROFILE_BILLING_PANEL = 'profile-billing-panel';
}
