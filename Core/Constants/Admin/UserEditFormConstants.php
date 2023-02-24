<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class UserEditFormConstants
 */
class UserEditFormConstants
{
    public const CID_PNL_INFO = 'uidic';
    public const CID_PNL_BILLING = 'uidbc';
    public const CID_PNL_SHIPPING = 'uidsc';
    public const CID_PNL_PRIVILEGES = 'uidpc';
    public const CID_PNL_RESUME = 'uidrc';
    public const CID_PNL_PAYMENT = 'uidyc';
    public const CID_PNL_SALES_COMMISSION = 'uidscc';
    public const CID_PNL_CONSIGNOR_COMMISSION_FEE = 'uidcccf';
    public const CID_PNL_CREDIT = 'uidcp';
    public const CID_PNL_DASHBOARD = 'uiddd';
    public const CID_PNL_BUYERS_PREMIUM = 'uidbp';
    public const CID_PNL_BUYERS_GROUP = 'uidbg';
    public const CID_PNL_LOG = 'uefUserLogPanel';
    public const CID_PNL_USER_EDIT_MORE_ACTIONS = 'pnlUserEditMoreAction';
    public const CID_CHK_HP_BP = 'uef11';
    public const CID_CHK_HP = 'uef12';
    public const CID_CHK_BP = 'uef13';
    public const CID_CHK_SERVICES = 'uef27';
    public const CID_LST_ADDED_BY = 'uef14';
    public const CID_LBL_ADDED_BY_OTHER = 'uef16';
    public const CID_DTG_IP_ADDRESS = 'uef17';
    public const DTG_IP_ADDRESS_QUERY_PARAMS_PREFIX = 'uip';
    public const CID_LBL_FAILED_ATTEMPTS = 'uef18';
    public const CID_LBL_LOCKOUT_UNTIL = 'uef19';
    public const CID_LST_AGENT = 'uef20';
    public const CID_TXT_AREA_NOTE = 'uef22';
    public const CID_BTN_SUBMIT = 'uef23';
    public const CID_DTG_USER_LOGS = 'uef24';
    public const DTG_USER_LOGS_QUERY_PARAMS_PREFIX = 'ul';
    public const CID_TXT_EXP_SECT = 'uef25';
    public const CID_TXT_MAX_OUTSTANDING = 'uef28';
    public const CID_TIME_LOG_COLUMN_TPL = 'txtTime%s';
    public const CID_NOTE_COLUMN_TPL = 'txtnote%s';
    public const CID_ACTION_COLUMN_BTN_S_TPL = 'btnS%s';
    public const CID_ACTION_COLUMN_BTN_C_TPL = 'btnC%s';
    public const CID_ACTION_COLUMN_BTN_D_TPL = 'btnD%s';
    public const CID_ACTION_COLUMN_BTN_E_TPL = 'btnE%s';
    public const CID_IP_ACTION_TPL = '%sbIpAdd%s';
    public const CID_BTN_SAVE = 'btnSave';
    public const CID_BTN_RESET_LOGIN_LOCKOUT = 'btnResetLoginLockout';
    public const CID_CHK_NO_TAX = 'uef30';
    public const CID_CHK_NO_TAX_BP = 'uef31';
    public const CID_TXT_SALES_TAX = 'uef32';
    public const CID_TXT_NOTES = 'uef33';
    public const CID_USER_CUST_FIELD_EDIT_TPL = 'UsrCustFldEdt%s';
    public const CID_TXT_AUTOCOMPLETE_ADDED_BY = 'uefTxtAutocompleteAddedBy';
    public const CID_HID_TXT_AUTOCOMPLETE_ADDED_BY = 'uefHidTxtAutocompleteAddedBy';
    public const CID_LBL_ADDED_BY_SALES_STAFF_AGENT = 'lblAddedBySalesStaffAgent';
    public const CID_LST_CONSIGNOR_COMMISSION_FEE_ACCOUNT = 'uef39';

    public const CLASS_BLK_CLOSE = 'close';
    public const CLASS_BLK_ERROR = 'error';
    public const CLASS_BLK_INPUT = 'input';
    public const CLASS_BLK_OPEN = 'open';
    public const CLASS_BLK_TEMP_MESSAGE = 'temp-msg';
    public const CLASS_BLK_USER_CONSIGNOR_COMMISSION_FEE = 'user-consignor-commission-fee';
}
